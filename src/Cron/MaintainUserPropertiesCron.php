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
use Markocupic\RszBenutzerverwaltungBundle\DataContainer\User;

#[AsCronJob('daily')]
class MaintainUserPropertiesCron
{
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function __invoke(): void
    {
        $this->user->maintainUserProperties();
    }
}
