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

namespace Markocupic\RszBenutzerverwaltungBundle\Cron;

use Contao\CoreBundle\DependencyInjection\Attribute\AsCronJob;
use Doctrine\DBAL\Connection;
use Markocupic\RszBenutzerverwaltungBundle\Maintenance\BackendUser\MaintainContaoCorePermissions;

#[AsCronJob('daily')]
class MaintainContaoCorePermissionsCron
{


    public function __construct(
        private readonly Connection $connection,
        private readonly MaintainContaoCorePermissions $maintainContaoCorePermissions,
    )
    {

    }

    public function __invoke(): void
    {
        $result = $this->connection->executeQuery('SELECT id FROM tl_user WHERE isRsz = ?', ['1']);

        while (false !== ($id = $result->fetchOne())) {
            $this->maintainContaoCorePermissions->resetContaoCorePermissions($id, [], true);
        }
    }
}
