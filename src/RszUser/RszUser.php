<?php

/**
 * @copyright  Marko Cupic 2020 <m.cupic@gmx.ch>
 * @author     Marko Cupic
 * @package    RSZ Benutzerverwaltung
 * @license    MIT
 * @see        https://github.com/markocupic/rsz-benutzerverwaltung-bundle
 *
 */

declare(strict_types=1);

namespace Markocupic\RszBenutzerverwaltungBundle\RszUser;

use Contao\BackendUser;
use Contao\Config;
use Contao\DataContainer;
use Contao\Email;
use Contao\Environment;
use Contao\FilesModel;
use Contao\Folder;
use Contao\MemberModel;
use Contao\StringUtil;
use Contao\System;
use Contao\UserModel;

/**
 * Class RszUser
 * @package Markocupic\RszBenutzerverwaltungBundle\RszUser
 */
class RszUser
{
    /**
     * @var BackendUser|\Contao\User
     */
    protected $User;

    /**
     * @var string
     */
    private $projectDir;

    /**
     * @var string
     */
    const DEFAULT_AVATAR_FEMALE = 'bundles/markocupicrszbenutzerverwaltung/female-1.png';

    /**
     * @var string
     */
    const DEFAULT_AVATAR_MALE = 'bundles/markocupicrszbenutzerverwaltung/male-1.png';

    /**
     * @var string
     */
    const STR_INFO_FLASH_TYPE = 'contao.BE.info';

    /**
     * RszUser constructor.
     */
    public function __construct()
    {
        $this->projectDir = System::getContainer()->getParameter('kernel.project_dir');

        $this->User = BackendUser::getInstance();
    }

    /**
     * @param $userId
     * @return string
     */
    public static function getAvatar($userId): string
    {
        $objUser = UserModel::findByPk($userId);
        if ($objUser != null)
        {
            if ($objUser->avatar != '')
            {
                $objFile = FilesModel::findByUuid($objUser->avatar);
                if ($objFile !== null)
                {
                    return $objFile->path;
                }
            }
            else
            {
                if ($objUser->gender === 'female')
                {
                    return self::DEFAULT_AVATAR_FEMALE;
                }
                else
                {
                    return self::DEFAULT_AVATAR_MALE;
                }
            }
        }

        return self::DEFAULT_AVATAR_MALE;
    }

    /**
     * Check for orphaned user directories from filesystem
     * onload callback for tl_user
     * sync tl_user with tl_member
     * create user directories
     * add filemounts for the user directories
     *
     * @throws \Exception
     */
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
        if ($objUser === null)
        {
            return;
        }
        while ($objUser->next())
        {
            if (!$objUser->isRSZ || empty($objUser->username) || empty($objUser->name))
            {
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
            if (!empty($objUser->funktion))
            {
                foreach ($arrGroups as $strFunction)
                {
                    if (in_array($strFunction, StringUtil::deserialize($objUser->funktion, true)))
                    {
                        $strFolder = System::getContainer()->getParameter('rsz-user-file-directory') . '/' . strtolower($strFunction) . '/' . $objUser->username . '/my_profile/my_pics';
                        if (!file_exists($this->projectDir . '/' . $strFolder))
                        {
                            // Create user directory
                            new Folder($strFolder);
                        }

                        // Add filemount for the user directory
                        $strFolder = System::getContainer()->getParameter('rsz-user-file-directory') . '/' . strtolower($strFunction) . '/' . $objUser->username;
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
            $arrName = explode(" ", $objUser->name);

            // Bei 2-teiligen Nachnamen (z.B. Von Arx)
            if (count($arrName) == 3)
            {
                $lastname = $arrName[0] . ' ' . $arrName[1];
                $firstname = $arrName[2];
            }
            else
            {
                // Normalfall 2-teiliger Name
                $lastname = $arrName[0];
                $firstname = $arrName[1];
            }

            $set = [
                "username"    => $objUser->username,
                "firstname"   => $firstname != '' ? $firstname : 'firstname',
                "lastname"    => $lastname != '' ? $lastname : 'lastname',
                "gender"      => $objUser->gender,
                "email"       => $objUser->email,
                "street"      => $objUser->street,
                "postal"      => $objUser->postal,
                "city"        => $objUser->city,
                "mobile"      => $objUser->mobile,
                "phone"       => $objUser->telephone,
                // Allow the backend password: See check credentials listener
                //"password"    => $objUser->password,
                "dateOfBirth" => $objUser->dateOfBirth,
                "language"    => $objUser->language,
                "website"     => $objUser->url,
                "login"       => 1,
            ];

            // Get assigned member
            $objDbMember = MemberModel::findByPk($objUser->assignedMember);

            if ($objDbMember !== null)
            {
                // Sync tl_member with tl_user
                $objDbMember->setRow($set);
                $objDbMember->save();
            }
            else
            {
                try
                {
                    // Create new member
                    $set['dateAdded'] = time();
                    $objNewMember = new MemberModel();

                    // Sync tl_member with tl_user
                    $objNewMember->setRow($set);
                    $objNewMember->save();

                    $objUser->assignedMember = $objNewMember->id;
                    $objUser->save();

                    System::log(sprintf('A new entry "tl_member.id=%s" has been created', $objNewMember->id), __CLASS__ . ' ' . __FUNCTION__ . '()', TL_GENERAL);

                    // Notify Admin
                    $subject = sprintf('Neuer Backend User auf %s', Environment::get('httpHost'));
                    $link = sprintf('%s/contao?do=user&act=edit&id=%s', Environment::get('base'), $objUser->id);
                    $msg = sprintf('Hallo Admin' . chr(10) . '%s hat auf %s einen neuen Backend User angelegt.' . chr(10) . 'Hier geht es zum User: ' . chr(10) . '%s', $this->User->name, Environment::get('httpHost'), $link);

                    // Send E-Mail
                    $objEmail = new Email();
                    $objEmail->subject = $subject;
                    $objEmail->text = $msg;
                    $objEmail->from = Config::get('adminEmail');
                    $objEmail->sendTo(Config::get('adminEmail'));

                    $this->addInfoFlashMessage('Ein neues Mitglied mit dem Benutzernamen ' . $objNewMember->username . ' wurde automatisch erstellt');
                } catch (\Exception $e)
                {
                    $this->addInfoFlashMessage($e->getMessage());
                }
            }
        }
    }

    /**
     * Check for orphaned directories
     * @param $strFunktion
     */
    public function checkForOrphanedDirectories($strFunktion)
    {
        $strFolder = System::getContainer()->getParameter('rsz-user-file-directory') . '/' . $strFunktion;
        if (!file_exists($this->projectDir . '/' . $strFolder))
        {
            return;
        }

        foreach (scan($this->projectDir . '/' . $strFolder) as $strUserDir)
        {
            $objUser = UserModel::findByUsername($strUserDir);
            if (!$objUser && is_dir($this->projectDir . '/' . $strFolder . '/' . $strUserDir))
            {
                $msg = $strFolder . '/' . $strUserDir . ' kann gelöscht werden, da tl_user.' . $strUserDir . ' gelöscht wurde.';
                $this->addInfoFlashMessage($msg);
            }

            // Display message if a directory must be deleted
            if ($this->User->isAdmin)
            {
                if ($objUser !== null && is_dir($this->projectDir . '/' . $strFolder . '/' . $strUserDir))
                {
                    $arrGroups = array_map('strtolower', StringUtil::deserialize($objUser->funktion, true));

                    if (!in_array($strFunktion, $arrGroups))
                    {
                        $msg = $strFolder . '/' . $strUserDir . ' kann gelöscht werden, da ' . $objUser->username . ' keine ' . ucfirst($strFunktion) . '-Funktion (mehr) innehat!';
                        $this->addInfoFlashMessage($msg);
                    }
                }
            }
        }
    }

    /**
     * Ondelete callback for tl_user
     * @param DataContainer $dc
     */
    public function deleteAssignedMember(DataContainer $dc): void
    {
        $objUser = UserModel::findByPk($dc->id);
        if ($objUser === null)
        {
            return;
        }

        $objMember = MemberModel::findByPk($objUser->assignedMember);
        if ($objMember === null)
        {
            return;
        }

        // Log
        System::log(sprintf('DELETE FROM tl_member WHERE id=%s', $objMember->id), __CLASS__ . ' ' . __FUNCTION__ . '()', TL_GENERAL);

        // Show message in the backend
        $this->addInfoFlashMessage(sprintf('Das mit dem Benutzer verknüpfte Mitglied "%s %s" wurde automatisch mitgelöscht.', $objMember->firstname, $objMember->lastname));
        $objMember->delete();
    }

    /**
     * @param string $msg
     */
    private function addInfoFlashMessage(string $msg): void
    {
        // Get flash bag
        $session = System::getContainer()->get('session');
        $flashBag = $session->getFlashBag();
        $arrFlash = [];
        if ($flashBag->has(static::STR_INFO_FLASH_TYPE))
        {
            $arrFlash = $flashBag->get(static::STR_INFO_FLASH_TYPE);
        }

        $arrFlash[] = $msg;

        $flashBag->set(static::STR_INFO_FLASH_TYPE, $arrFlash);
    }
}
