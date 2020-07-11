<?php

/**
 * @copyright  Marko Cupic 2020 <m.cupic@gmx.ch>
 * @author     Marko Cupic
 * @package    RSZ Benutzerverwaltung
 * @license    MIT
 * @see        https://github.com/markocupic/rsz-benutzerverwaltung-bundle
 *
 */

namespace Markocupic\RszBenutzerverwaltungBundle\EventListener\ContaoHooks;

use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\MemberModel;
use Contao\UserModel;
use Contao\CoreBundle\ServiceAnnotation\Hook;
use Terminal42\ServiceAnnotationBundle\ServiceAnnotationInterface;

/**
 * Class SetNewPasswordListener
 * @package Markocupic\RszBenutzerverwaltungBundle\EventListener\ContaoHooks
 */
class SetNewPasswordListener implements ServiceAnnotationInterface
{
    /**
     * @var ContaoFramework
     */
    private $framework;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * AddCustomRegexpListener constructor.
     * @param ContaoFramework $framework
     * @param RequestStack $requestStack
     */
    public function __construct(ContaoFramework $framework, RequestStack $requestStack)
    {
        $this->framework = $framework;
        $this->requestStack = $requestStack;
    }

    /**
     * SetNewPassword Hook for tl_member.password
     * Synchronize the password with with tl_user
     * Methode wird durch den setNewPassword Hook aufgerufen,
     * wenn ein Mitglied sein Passwort ändert
     *
     * @Hook("setNewPassword")       *
     * @param MemberModel $objMember
     * @param string $strPassword
     */
    public function setNewPassword(MemberModel $objMember, string $strPassword)
    {
        $objUser = UserModel::findByUsername($objMember->username);
        if ($objUser !== null)
        {
            $objUser->password = $strPassword;
            $objUser->save();
        }
    }
}
