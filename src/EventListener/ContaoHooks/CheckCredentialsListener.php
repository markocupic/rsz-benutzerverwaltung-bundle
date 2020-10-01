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
use Contao\CoreBundle\Monolog\ContaoContext;
use Contao\CoreBundle\ServiceAnnotation\Hook;
use Contao\System;
use Contao\User;
use Contao\UserModel;
use Psr\Log\LogLevel;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

/**
 * Class CheckCredentialsListener
 * @package Markocupic\RszBenutzerverwaltungBundle\EventListener\ContaoHooks
 */
class CheckCredentialsListener
{
    /** @var EncoderFactoryInterface */
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
                    $logger = System::getContainer()->get('monolog.logger.contao');
                    $logger->log(
                        LogLevel::INFO,
                        sprintf(
                            'Contao member with username "%s" has logged in into the frontend using his backend password.',
                            $username
                        ),
                        ['contao' => new ContaoContext(__METHOD__, ContaoContext::GENERAL)]
                    );

                    // Return true means: "access granted"
                    return true;
                }
            }
        }

        return false;
    }
}