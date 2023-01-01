<?php

declare(strict_types=1);

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

    public function __invoke()
    {
        $this->user->maintainUserProperties();
    }
}
