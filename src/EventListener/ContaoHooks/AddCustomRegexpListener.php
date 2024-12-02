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

namespace Markocupic\RszBenutzerverwaltungBundle\EventListener\ContaoHooks;

use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;
use Contao\Widget;

class AddCustomRegexpListener
{
    /**
     * Überprüfe, ob Name und Vorname übergeben wurden (mind. 2 Wörter).
     */
    #[AsHook('addCustomRegexp', priority: 100)]
    public function isFirstnameAndLastname(string $strRegexp, string $varValue, Widget $objWidget): bool
    {
        // Überprüfe, ob Name und Vorname übergeben wurden (mind. 2 Wörter)
        if ('name' === $strRegexp) {
            if (false === strpos(trim($varValue), ' ')) {
                $objWidget->addError('Der Name sollte aus mindestens zwei durch einen Leerschlag voneinander getrennten Wörtern bestehen.');
            }

            return true;
        }

        return false;
    }
}
