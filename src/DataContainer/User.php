<?php

/**
 * @copyright  Marko Cupic 2020 <m.cupic@gmx.ch>
 * @author     Marko Cupic
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

use Contao\BackendUser;
use Contao\Config;
use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\DataContainer;
use Contao\Email;
use Contao\Environment;
use Contao\FilesModel;
use Contao\Folder;
use Contao\Input;
use Contao\MemberModel;
use Contao\StringUtil;
use Contao\System;
use Contao\UserModel;
use Markocupic\RszBenutzerverwaltungBundle\Excel\PrepareExportFromSession;
use Markocupic\RszBenutzerverwaltungBundle\Excel\RszAdressenDownload;
use PhpOffice\PhpSpreadsheet\Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Security;


class User
{

    public const STR_INFO_FLASH_TYPE = 'contao.BE.info';

    private RequestStack $requestStack;
    private ?BackendUser $user = null;
    private PrepareExportFromSession $prepareExportFromSession;
    private LoggerInterface $contaoGeneralLogger;
    private string $projectDir;

    public function __construct(RequestStack $requestStack, Security $security, PrepareExportFromSession $prepareExportFromSession, LoggerInterface $contaoGeneralLogger, string $projectDir)
    {
        $this->projectDir = $projectDir;
        $this->prepareExportFromSession = $prepareExportFromSession;
        $this->contaoGeneralLogger = $contaoGeneralLogger;

        // Get backend user
        $user = $security->getUser();

        if($user instanceof BackendUser)
        {
            $this->user = $user;
        }

        $this->requestStack = $requestStack;
    }



    /**
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    #[AsCallback(table: 'tl_user', target: 'config.onload', priority: 100)]
    public function prepareExcelExport(): void
    {
        if ('user' === Input::get('do') && 'excelExport' === Input::get('act')) {

            $arrIds = $this->prepareExportFromSession->getIdsFromSession();
            $strOrderBy = $this->prepareExportFromSession->getOrderByFromSession();
            $export = new RszAdressenDownload($this->user);
            $export->downloadAddressesAsXlsx($arrIds, $strOrderBy);
        }
    }

    /**
     * Check for orphaned user directories from filesystem
     * onload callback for tl_user
     * sync tl_user with tl_member
     * create user directories
     * add filemounts for the user directories.
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

        // Synchronize all tl_user.passwords with tl_member.passwords
        $objUser = UserModel::findAll();

        if (null === $objUser) {
            return;
        }

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
                    if (\in_array($strFunction, StringUtil::deserialize($objUser->funktion, true), true)) {
                        $strFolder = System::getContainer()->getParameter('rsz-user-file-directory').'/'.strtolower($strFunction).'/'.$objUser->username.'/my_profile/my_pics';

                        if (!file_exists($this->projectDir.'/'.$strFolder)) {
                            // Create user directory
                            new Folder($strFolder);
                        }

                        // Add filemount for the user directory
                        $strFolder = System::getContainer()->getParameter('rsz-user-file-directory').'/'.strtolower($strFunction).'/'.$objUser->username;
                        $objFile = FilesModel::findByPath($strFolder);
                        $arrFileMounts = StringUtil::deserialize($objUser->filemounts, true);
                        $arrFileMounts[] = $objFile->uuid;
                        $objUser->filemounts = serialize(array_unique($arrFileMounts));
                        $objUser->inherit = 'extend';
                        $objUser->save();
                    }
                }
            }

            // Collect data
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
                // Sync tl_member with tl_user
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
                    $msg = sprintf('Hallo Admin'.\chr(10).'%s hat auf %s einen neuen Backend User angelegt.'.\chr(10).'Hier geht es zum User: '.\chr(10).'%s', $this->user->name, Environment::get('httpHost'), $link);

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

        foreach (Folder::scan($this->projectDir.'/'.$strFolder) as $strUserDir) {
            $objUser = UserModel::findByUsername($strUserDir);

            if (!$objUser && is_dir($this->projectDir.'/'.$strFolder.'/'.$strUserDir)) {
                $msg = $strFolder.'/'.$strUserDir.' kann gelöscht werden, da tl_user.'.$strUserDir.' gelöscht wurde.';
                $this->addInfoFlashMessage($msg);
            }

            // Display message if a directory must be deleted
            if ($this->user->isAdmin) {
                if (null !== $objUser && is_dir($this->projectDir.'/'.$strFolder.'/'.$strUserDir)) {
                    $arrGroups = array_map('strtolower', StringUtil::deserialize($objUser->funktion, true));

                    if (!\in_array($strFunktion, $arrGroups, true)) {
                        $msg = $strFolder.'/'.$strUserDir.' kann gelöscht werden, da '.$objUser->username.' keine '.ucfirst($strFunktion).'-Funktion (mehr) innehat!';
                        $this->addInfoFlashMessage($msg);
                    }
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
