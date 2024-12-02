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

use Contao\CoreBundle\DataContainer\PaletteManipulator;

/*
 * Add to palette
 */
PaletteManipulator::create()
    ->addLegend('rsz_benutzerverwaltung', PaletteManipulator::POSITION_APPEND)
    ->addField(['mcupic_be_benutzerverwaltung_niveau', 'mcupic_be_benutzerverwaltung_trainingsgruppe'], 'rsz_benutzerverwaltung')
    ->applyToPalette('default', 'tl_settings');

/*
 * Add fields
 */
$GLOBALS['TL_DCA']['tl_settings']['fields']['mcupic_be_benutzerverwaltung_niveau'] = [
    'inputType' => 'text',
    'eval'      => ['tl_class' => 'long clr'],
];

$GLOBALS['TL_DCA']['tl_settings']['fields']['mcupic_be_benutzerverwaltung_trainingsgruppe'] = [
    'inputType' => 'text',
    'eval'      => ['tl_class' => 'long clr'],
];
