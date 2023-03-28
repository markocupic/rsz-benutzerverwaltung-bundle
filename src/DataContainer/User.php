<?php

/**
 * @copyright  Marko Cupic 2023 <m.cupic@gmx.ch>
 * @license    MIT
 *
 * @see        https://github.com/markocupic/rsz-benutzerverwaltung-bundle
 */

declare(strict_types=1);

/*
 * This file is part of RSZ Benutzerverwaltung Bundle.
 *
 * (c) Marko Cupic 2023 <m.cupic@gmx.ch>
 * @license MIT
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 * @link https://github.com/markocupic/rsz-benutzerverwaltung-bundle
 */

namespace Markocupic\RszBenutzerverwaltungBundle\DataContainer;

use Contao\Config;
use Contao\Controller;
use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\CoreBundle\Exception\AccessDeniedException;
use Contao\CoreBundle\Framework\Adapter;
use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\DataContainer;
use Contao\Email;
use Contao\Environment;
use Contao\FilesModel;
use Contao\Folder;
use Contao\Image;
use Contao\MemberModel;
use Contao\StringUtil;
use Contao\System;
use Contao\UserModel;
use Markocupic\RszBenutzerverwaltungBundle\Security\RszBackendPermissions;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Security;

class User
{
    public const STR_INFO_FLASH_TYPE = 'contao.BE.info';

    private Adapter $controller;
    private Adapter $image;
    private Adapter $stringUtil;

    public function __construct(
        private readonly ContaoFramework $framework,
        private readonly RequestStack $requestStack,
        private readonly Security $security,
        private readonly LoggerInterface $contaoGeneralLogger,
        private readonly string $projectDir,
    ) {
        $this->controller = $this->framework->getAdapter(Controller::class);
        $this->image = $this->framework->getAdapter(Image::class);
        $this->stringUtil = $this->framework->getAdapter(StringUtil::class);
    }

    #[AsCallback(table: 'tl_user', target: 'config.onload', priority: 101)]
    public function checkPermission(): void
    {
        $request = $this->requestStack->getCurrentRequest();

        if ($this->security->isGranted('ROLE_ADMIN')) {
            return;
        }

        if (!$this->security->isGranted(RszBackendPermissions::RSZ_USERS_PERMISSIONS, 'can_edit_rsz_users')) {
            $GLOBALS['TL_DCA']['tl_user']['config']['notEditable'] = true;
            $GLOBALS['TL_DCA']['tl_user']['config']['notCopyable'] = true;
            $GLOBALS['TL_DCA']['tl_user']['config']['notCreatable'] = true;
        }

        if (!$this->security->isGranted(RszBackendPermissions::RSZ_USERS_PERMISSIONS, 'can_delete_rsz_users')) {
            $GLOBALS['TL_DCA']['tl_user']['config']['notDeletable'] = true;
        }

        // Check current action
        switch ($request->query->get('act')) {
            case 'create':
            case 'edit':
            case 'copy':
            case 'overrideAll':
            case 'editAll':
            case 'toggle':
                if (!$this->security->isGranted(RszBackendPermissions::RSZ_USERS_PERMISSIONS, 'can_edit_rsz_users')) {
                    throw new AccessDeniedException('Not enough permissions to '.$request->query->get('act').' user with ID '.$request->query->get('id').'.');
                }
                break;
            case 'delete':
            case 'deleteAll':
            if (!$this->security->isGranted(RszBackendPermissions::RSZ_USERS_PERMISSIONS, 'can_delete_rsz_users')) {
                throw new AccessDeniedException('Not enough permissions to '.$request->query->get('act').' user with ID '.$request->query->get('id').'.');
            }
        }
    }

    #[AsCallback(table: 'tl_user', target: 'list.operations.delete.button', priority: 101)]
    public function deleteUser($row, $href, $label, $title, $icon, $attributes)
    {
        $grant = false;

        if ($this->security->isGranted('ROLE_ADMIN')) {
            $grant = true;
        } elseif ($this->security->isGranted(RszBackendPermissions::RSZ_USERS_PERMISSIONS, 'can_delete_rsz_users')) {
            $grant = true;
        }

        return $grant ? '<a href="'.$this->controller->addToUrl($href.'&amp;id='.$row['id'],true).'" title="'.$this->stringUtil->specialchars($title).'"'.$attributes.'>'.$this->image->getHtml($icon, $label).'</a> ' : $this->image->getHtml(preg_replace('/\.svg$/i', '_.svg', $icon)).' ';
    }

    #[AsCallback(table: 'tl_user', target: 'list.operations.edit.button', priority: 101)]
    #[AsCallback(table: 'tl_user', target: 'list.operations.copy.button', priority: 101)]
    public function editOrCopyUser($row, $href, $label, $title, $icon, $attributes)
    {
        $grant = false;

        if ($this->security->isGranted('ROLE_ADMIN')) {
            $grant = true;
        } elseif ($this->security->isGranted(RszBackendPermissions::RSZ_USERS_PERMISSIONS, 'can_edit_rsz_users')) {
            $grant = true;
        }

        return $grant ? '<a href="'.$this->controller->addToUrl($href.'&amp;id='.$row['id'],true).'" title="'.$this->stringUtil->specialchars($title).'"'.$attributes.'>'.$this->image->getHtml($icon, $label).'</a> ' : $this->image->getHtml(preg_replace('/\.svg$/i', '_.svg', $icon)).' ';
    }

    /**
     * For each user do:
     * - Add correct file mount
     * - Sync tl_user with tl_member.
     *
     * @throws \Exception
     */
    #[AsCallback(table: 'tl_user', target: 'config.onload', priority: 101)]
    public function maintainUserProperties(): void
    {
        // Remove  orphaned user directories from filesystem
        $this->checkForOrphanedDirectories('athlet');
        $this->checkForOrphanedDirectories('trainer');
        $this->checkForOrphanedDirectories('vorstand');
        $this->checkForOrphanedDirectories('website');
        $this->checkForOrphanedDirectories('eltern');

        $objUser = UserModel::findAll();

        if (null === $objUser) {
            return;
        }

        // Traverse each user.
        while ($objUser->next()) {
            if (!$objUser->isRSZ || empty($objUser->username) || empty($objUser->name)) {
                continue;
            }

            // Create user directories
            $arrGroups = [
                'Athlet',
                'Trainer',
                'Vorstand',
                'Website',
                'Eltern',
            ];

            if (!empty($objUser->funktion)) {
                foreach ($arrGroups as $strFunction) {
                    if (\in_array($strFunction, $this->stringUtil->deserialize($objUser->funktion, true), true)) {
                        $strFolder = System::getContainer()->getParameter('rsz-user-file-directory').'/'.strtolower($strFunction).'/'.$objUser->username.'/my_profile/my_pics';

                        if (!file_exists($this->projectDir.'/'.$strFolder)) {
                            // Create user directory
                            new Folder($strFolder);
                        }

                        // Add file mount for the user directory
                        $strFolder = System::getContainer()->getParameter('rsz-user-file-directory').'/'.strtolower($strFunction).'/'.$objUser->username;
                        $objFile = FilesModel::findByPath($strFolder);
                        $arrFileMounts = $this->stringUtil->deserialize($objUser->filemounts, true);
                        $arrFileMounts[] = $objFile->uuid;
                        $objUser->filemounts = serialize(array_unique($arrFileMounts));
                        $objUser->inherit = 'extend';
                        $objUser->save();
                    }
                }
            }

            // Sync from tl_user -> tl_member
            unset($firstname, $lastname);
            $arrName = explode(' ', $objUser->name);

            // Bei 2-teiligen Nachnamen (z.B. Von Arx)
            if (3 === \count($arrName)) {
                $lastname = $arrName[0].' '.$arrName[1];
                $firstname = $arrName[2];
            } else {
                // Normalfall 2-teiliger Name
                $lastname = $arrName[0];
                $firstname = $arrName[1];
            }

            $set = [
                'username' => $objUser->username,
                'firstname' => '' !== $firstname ? $firstname : 'firstname',
                'lastname' => '' !== $lastname ? $lastname : 'lastname',
                'gender' => $objUser->gender,
                'email' => $objUser->email,
                'street' => $objUser->street,
                'postal' => $objUser->postal,
                'city' => $objUser->city,
                'mobile' => $objUser->mobile,
                'phone' => $objUser->telephone,
                // Allow the backend password: See check credentials listener
                //"password"    => $objUser->password,
                'dateOfBirth' => $objUser->dateOfBirth,
                'language' => $objUser->language,
                'website' => $objUser->url,
                'login' => 1,
            ];

            // Get assigned member
            $objDbMember = MemberModel::findByPk($objUser->assignedMember);

            if (null !== $objDbMember) {
                // Sync from tl_user -> tl_member
                $objDbMember->setRow($set);
                $objDbMember->save();
            } else {
                try {
                    // Create new member
                    $set['dateAdded'] = time();
                    $objNewMember = new MemberModel();

                    // Sync tl_member with tl_user
                    $objNewMember->setRow($set);
                    $objNewMember->save();

                    $objUser->assignedMember = $objNewMember->id;
                    $objUser->save();

                    $this->contaoGeneralLogger->info(sprintf('A new entry "tl_member.id=%s" has been created', $objNewMember->id));

                    // Notify Admin
                    $subject = sprintf('Neuer Backend User auf %s', Environment::get('httpHost'));
                    $link = sprintf('%scontao?do=user&act=edit&id=%s', Environment::get('base'), $objUser->id);

                    $msg = sprintf('Hallo Admin'.\chr(10).'%s hat auf %s einen neuen Backend User angelegt.'.\chr(10).'Hier geht es zum User: '.\chr(10).'%s', $this->security->getUser()->name, Environment::get('httpHost'), $link);

                    // Send E-Mail
                    $objEmail = new Email();
                    $objEmail->subject = $subject;
                    $objEmail->text = $msg;
                    $objEmail->from = Config::get('adminEmail');
                    $objEmail->sendTo(Config::get('adminEmail'));

                    $this->addInfoFlashMessage('Ein neues Mitglied mit dem Benutzernamen '.$objNewMember->username.' wurde automatisch erstellt.');
                } catch (\Exception $e) {
                    $this->addInfoFlashMessage($e->getMessage());
                }
            }
        }
    }

    #[AsCallback(table: 'tl_user', target: 'config.ondelete', priority: 100)]
    public function deleteAssignedMember(DataContainer $dc): void
    {
        $objUser = UserModel::findByPk($dc->id);

        if (null === $objUser) {
            return;
        }

        $objMember = MemberModel::findByPk($objUser->assignedMember);

        if (null === $objMember) {
            return;
        }

        // Log
        $this->contaoGeneralLogger->info(sprintf('DELETE FROM tl_member WHERE id=%s', $objMember->id));

        // Show message in the backend
        $this->addInfoFlashMessage(sprintf('Das mit dem Benutzer verknüpfte Mitglied "%s %s" wurde automatisch mitgelöscht.', $objMember->firstname, $objMember->lastname));
        $objMember->delete();
    }

    /**
     * Check for orphaned directories.
     *
     * @param $strFunktion
     */
    private function checkForOrphanedDirectories($strFunktion): void
    {
        $strFolder = System::getContainer()->getParameter('rsz-user-file-directory').'/'.$strFunktion;

        if (!file_exists($this->projectDir.'/'.$strFolder)) {
            return;
        }

        $request = $this->requestStack->getCurrentRequest();

        // Skip if the method has been called via cron job or if user is in detail mode.
        if (!$request || $request->query->has('act')) {
            return;
        }

        if ('user' !== $request->query->get('do')) {
            return;
        }

        foreach (Folder::scan($this->projectDir.'/'.$strFolder) as $strUserDir) {
            $objUser = UserModel::findByUsername($strUserDir);

            if (!$objUser && is_dir($this->projectDir.'/'.$strFolder.'/'.$strUserDir)) {
                $msg = $strFolder.'/'.$strUserDir.' kann gelöscht werden, da tl_user.'.$strUserDir.' gelöscht wurde.';
                $this->addInfoFlashMessage($msg);
            }

            // Display message if a directory must be deleted
            if ($this->security->isGranted('ROLE_ADMIN')) {
                if (null !== $objUser && is_dir($this->projectDir.'/'.$strFolder.'/'.$strUserDir)) {
                    $arrGroups = array_map('strtolower', $this->stringUtil->deserialize($objUser->funktion, true));

                    if (!\in_array($strFunktion, $arrGroups, true)) {
                        $msg = $strFolder.'/'.$strUserDir.' kann gelöscht werden, da '.$objUser->username.' keine '.ucfirst($strFunktion).'-Funktion (mehr) innehat!';
                        $this->addInfoFlashMessage($msg);
                    }
                }
            }
        }
    }

    private function addInfoFlashMessage(string $msg): void
    {
        // Get flash bag
        $session = $this->requestStack->getCurrentRequest()->getSession();

        $flashBag = $session->getFlashBag();
        $arrFlash = [];

        if ($flashBag->has(static::STR_INFO_FLASH_TYPE)) {
            $arrFlash = $flashBag->get(static::STR_INFO_FLASH_TYPE);
        }

        $arrFlash[] = $msg;

        $flashBag->set(static::STR_INFO_FLASH_TYPE, $arrFlash);
    }
}
