<?php

declare(strict_types=1);

/*
 * This file is part of RSZ Benutzerverwaltung Bundle.
 *
 * (c) Marko Cupic 2021 <m.cupic@gmx.ch>
 * @license MIT
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 * @link https://github.com/markocupic/rsz-benutzerverwaltung-bundle
 */

namespace Markocupic\RszBenutzerverwaltungBundle\EventListener\ContaoHooks;

use Contao\CoreBundle\Monolog\ContaoContext;
use Contao\CoreBundle\ServiceAnnotation\Hook;
use Contao\System;
use Contao\User;
use Contao\UserModel;
use Psr\Log\LogLevel;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

/**
 * Class CheckCredentialsListener.
 */
class CheckCredentialsListener
{
    /**
     * @var EncoderFactoryInterface
     */
    private $encoderFactory;

    /**
     * CheckCredentialsListener constructor.
     */
    public function __construct(EncoderFactoryInterface $encoderFactory)
    {
        $this->encoderFactory = $encoderFactory;
    }

    /**
     * Allow the backend password when loging in into the frontend.
     *
     * @Hook("checkCredentials")
     */
    public function allowBackendPassword(string $username, string $credentials, User $user): bool
    {
        if (TL_MODE === 'FE') {
            if (null !== ($objUser = UserModel::findByAssignedMember($user->id))) {
                if (password_verify($credentials, $objUser->password)) {
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
