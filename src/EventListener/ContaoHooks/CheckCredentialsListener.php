<?php

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

namespace Markocupic\RszBenutzerverwaltungBundle\EventListener\ContaoHooks;

use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;
use Contao\CoreBundle\Routing\ScopeMatcher;
use Contao\User;
use Contao\UserModel;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class CheckCredentialsListener
{
    private LoggerInterface $contaoGeneralLogger;
    private RequestStack $requestStack;
    private ScopeMatcher $scopeMatcher;

    public function __construct(RequestStack $requestStack, ScopeMatcher $scopeMatcher, LoggerInterface $contaoGeneralLogger)
    {
        $this->contaoGeneralLogger = $contaoGeneralLogger;
        $this->requestStack = $requestStack;
        $this->scopeMatcher = $scopeMatcher;
    }

    /**
     * Allow the backend password when logging in into the frontend.
     */
    #[AsHook('checkCredentials', priority: 100)]
    public function allowBackendPassword(string $username, string $credentials, User $user): bool
    {
        $request = $this->requestStack->getCurrentRequest();

        if ($request && $this->scopeMatcher->isFrontendRequest($request)) {
            if (null !== ($objUser = UserModel::findByAssignedMember($user->id))) {
                if (password_verify($credentials, $objUser->password)) {
                    $this->contaoGeneralLogger->info(sprintf(
                        'Contao member with username "%s" has logged in into the frontend using his backend password.',
                        $username
                    ));

                    // Return true means: "access granted"
                    return true;
                }
            }
        }

        return false;
    }
}
