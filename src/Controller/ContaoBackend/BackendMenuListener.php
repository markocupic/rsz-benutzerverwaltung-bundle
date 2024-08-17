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

namespace Markocupic\RszBenutzerverwaltungBundle\Controller\ContaoBackend;

use Contao\CoreBundle\Event\MenuEvent;
use Contao\StringUtil;
use Markocupic\RszBenutzerverwaltungBundle\Security\RszBackendPermissions;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Bundle\SecurityBundle\Security;

#[AsEventListener(event: 'contao.backend_menu_build', priority: -255)]
class BackendMenuListener
{
    public function __construct(
        private readonly RouterInterface $router,
        private readonly Security $security,
        private readonly RequestStack $requestStack,
    ) {
    }

    public function __invoke(MenuEvent $event): void
    {
        if (!$this->security->isGranted('ROLE_ADMIN') && !$this->security->isGranted(RszBackendPermissions::USER_CAN_DOWNLOAD_RSZ_ADDRESSES, 'main_menu_download')) {
            return;
        }

        $factory = $event->getFactory();
        $tree = $event->getTree();

        if ('mainMenu' !== $tree->getName()) {
            return;
        }

        $contentNode = $tree->getChild('rsz_tools');

        $node = $factory
            ->createItem('rsz_address_download_main_menu')
            ->setUri($this->router->generate(RszAddressDownloadAllController::class))
            ->setLabel($GLOBALS['TL_LANG']['MOD']['rsz_address_download_main_menu'][0])
            ->setLinkAttribute('title', StringUtil::specialcharsAttribute('Adressen herunterladen'))
            ->setLinkAttribute('class', 'my-rsz_address_download_main_menu')
            ->setCurrent(RszAddressDownloadAllController::class === $this->requestStack->getCurrentRequest()->get('_controller'))
        ;

        $contentNode->addChild($node);
    }
}
