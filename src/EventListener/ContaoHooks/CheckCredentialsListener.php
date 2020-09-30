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

use Contao\BackendUser;
use Contao\CoreBundle\ServiceAnnotation\Hook;
use Contao\User;
use Contao\UserModel;
use Symfony\Component\Security\Core\Authentication\Provider\DaoAuthenticationProvider;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

/**
 * Class CheckCredentialsListener
 * @package Markocupic\RszBenutzerverwaltungBundle\EventListener\ContaoHooks
 */
class CheckCredentialsListener
{
    /** @var EncoderFactoryInterface  */
    private $encoderFactory;

    /**
     * CheckCredentialsListener constructor.
     * @param EncoderFactoryInterface $encoderFactory
     */
    public function __construct(EncoderFactoryInterface $encoderFactory)
    {
        $this->encoderFactory = $encoderFactory;
    }


    /**
     * Allow the backend password when loging in into the frontend
     * @Hook("checkCredentials")
     *
     * @param string $username
     * @param string $credentials
     * @param User $user
     * @return bool
     */
    public function allowBackendPassword(string $username, string $credentials, User $user): bool
    {

        if (TL_MODE === 'FE')
        {
            if (null !== ($objUser = UserModel::findByAssignedMember($user->id)))
            {
                $encoder = $this->encoderFactory->getEncoder(BackendUser::class);
                $hash = $encoder->encodePassword($credentials, null);
                if (password_verify($credentials, $objUser->password))
                {
                    return true;
                }
            }
        }

        return false;
    }
}