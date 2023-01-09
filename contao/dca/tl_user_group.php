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

use Contao\CoreBundle\DataContainer\PaletteManipulator;

// Extend the default palette with permissions
PaletteManipulator::create()
    ->addLegend('rsz_address_download_legend', 'amg_legend', PaletteManipulator::POSITION_BEFORE)
    ->addField(['rsz_address_downloadp'], 'rsz_address_download_legend', PaletteManipulator::POSITION_APPEND)
    ->applyToPalette('default', 'tl_user_group');

// Add fields to tl_user_group
$GLOBALS['TL_DCA']['tl_user_group']['fields']['rsz_address_downloadp'] = [
    'exclude'   => true,
    'inputType' => 'checkbox',
    'options'   => [
        'main_menu_download',
    ],
    'reference' => &$GLOBALS['TL_LANG']['MSC'],
    'eval'      => ['multiple' => true],
    'sql'       => 'blob NULL',
];
