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
    const USER_DIRECTORY = 'files/Dateiablage/user_dir';

    /**
     * @var string
     */
    const DEFAULT_AVATAR_FEMALE = 'files/theme-files/theme_pics/avatars/female-1.png';

    /**
     * @var string
     */
    const DEFAULT_AVATAR_MALE = 'files/theme-files/theme_pics/avatars/male-1.png';

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
                if ($objUser->gender == 'female')
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
     * onload callback for tl_user
     * sync tl_user with tl_member
     * create user directories
     * add filemounts for the user directories
     * remove  orphaned user directories from filesystem
     */
    public function maintainUserProperties(): void
    {
        // remove  orphaned user directories from filesystem
        $this->deleteOrphanedDirectories(static::USER_DIRECTORY . '/athlet', 'Athlet');
        $this->deleteOrphanedDirectories(static::USER_DIRECTORY . '/trainer', 'Trainer');
        $this->deleteOrphanedDirectories(static::USER_DIRECTORY . '/vorstand', 'Vorstand');
        $this->deleteOrphanedDirectories(static::USER_DIRECTORY . '/website', 'Website');
        $this->deleteOrphanedDirectories(static::USER_DIRECTORY . '/eltern', 'Eltern');

        //synchronize all tl_user.passwords with tl_member.passwords
        $objUser = UserModel::findAll();
        if ($objUser === null)
        {
            return;
        }
        while ($objUser->next())
        {
            if (!$objUser->isRSZ)
            {
                continue;
            }

            // create user directories
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
                        $strFolder = static::USER_DIRECTORY . '/' . strtolower($strFunction) . '/' . $objUser->username . '/my_profile/my_pics';
                        if (!file_exists($this->projectDir . '/' . $strFolder))
                        {
                            // create user directory
                            new Folder($strFolder);
                        }

                        // add filemount for the user directory
                        $strFolder = static::USER_DIRECTORY . '/' . strtolower($strFunction) . '/' . $objUser->username;
                        $objFile = FilesModel::findByPath($strFolder);
                        $arrFileMounts = StringUtil::deserialize($objUser->filemounts, true);
                        $arrFileMounts[] = $objFile->uuid;
                        $objUser->filemounts = serialize(array_unique($arrFileMounts));
                        $objUser->inherit = 'extend';
                        $objUser->save();
                    }
                }
            }

            // collect data
            unset($firstname, $lastname);
            $arrName = explode(" ", $objUser->name);

            //bei 2-teiligen Nachnamen (z.B. Von Arx)
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
                "password"    => $objUser->password,
                "dateOfBirth" => $objUser->dateOfBirth,
                "language"    => $objUser->language,
                "website"     => $objUser->url,
                "login"       => 1,
            ];

            // get assigned member
            $objDbMember = MemberModel::findByPk($objUser->assignedMember);

            if ($objDbMember !== null)
            {
                // sync tl_member with tl_user
                $objDbMember->setRow($set);
                $objDbMember->save();
            }
            else
            {
                try
                {
                    // create new member
                    $set['dateAdded'] = time();
                    $objNewMember = new MemberModel();

                    // sync tl_member with tl_user
                    foreach ($set as $k => $v)
                    {
                        $objNewMember->{$k} = $v;
                    }

                    $objNewMember->save();

                    $objUser->assignedMember = $objNewMember->id;
                    $objUser->save();

                    System::log(sprintf('A new entry "tl_member.id=%s" has been created', $objNewMember->id), __CLASS__ . ' ' . __FUNCTION__ . '()', TL_GENERAL);

                    // notify Admin
                    $subject = sprintf('Neuer Backend User auf %s', Environment::get('httpHost'));
                    $link = sprintf('http://%s/contao?do=user&act=edit&id=%s', Environment::get('httpHost'), $objUser->id);
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
     * Delete orphaned directories
     * @param $strFolder
     * @param $strGroup
     */
    public function deleteOrphanedDirectories($strFolder, $strGroup)
    {
        if (!file_exists($this->projectDir . '/' . $strFolder))
        {
            return;
        }

        foreach (scan($this->projectDir . '/' . $strFolder) as $strUserDir)
        {
            $objUser = UserModel::findByUsername($strUserDir);
            if (!$objUser && is_dir($this->projectDir . '/' . $strFolder . '/' . $strUserDir))
            {
                //$objFolder = new Folder($strFolder . '/' . $strUserDir);
                //$objFolder->delete();
                $msg = $strFolder . '/' . $strUserDir . ' kann gelöscht werden, da tl_user.' . $objUser->username . ' gelöscht wurde.';
                $this->addInfoFlashMessage($msg);
            }

            // display message if a directory must be deleted
            if ($this->User->isAdmin)
            {
                if ($objUser !== null && is_dir($this->projectDir . '/' . $strFolder . '/' . $strUserDir))
                {
                    $arrGroups = deserialize($objUser->funktion, true);
                    if (!in_array($strGroup, $arrGroups))
                    {
                        $msg = $strFolder . '/' . $strUserDir . ' kann gelöscht werden, da ' . $objUser->username . ' keine ' . $strGroup . '-Funktion innehat!';
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
