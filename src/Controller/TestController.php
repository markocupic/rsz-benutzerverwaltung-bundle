<?php

declare(strict_types=1);

/*
 * This file is part of RSZ Benutzerverwaltung Bundle.
 *
 * (c) Marko Cupic <m.cupic@gmx.ch>
 * @license MIT
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 * @link https://github.com/markocupic/rsz-benutzerverwaltung-bundle
 */

namespace Markocupic\RszBenutzerverwaltungBundle\Controller;

use Doctrine\DBAL\Connection;
use Markocupic\RszBenutzerverwaltungBundle\Maintenance\BackendUser\MaintainContaoCorePermissions;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TestController
{
    public function __construct(
        private readonly Connection $connection,
        private readonly MaintainContaoCorePermissions $maintainContaoCorePermissions,
    ) {
    }

    #[Route('/test', name: self::class, defaults: ['_scope' => 'backend'])]
    public function test(): Response
    {
        $id = $this->connection->fetchOne('SELECT id FROM tl_user WHERE username = ?', ['lauraspescha']);

        $this->maintainContaoCorePermissions->resetContaoCorePermissions($id, [], true);

        return new Response(
            '<html><body>Test</body></html>'
        );
    }
}
