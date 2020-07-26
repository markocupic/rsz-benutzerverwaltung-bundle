<?php

/**
 * @copyright  Marko Cupic 2020 <m.cupic@gmx.ch>
 * @author     Marko Cupic
 * @package    RSZ Benutzerverwaltung
 * @license    MIT
 * @see        https://github.com/markocupic/rsz-benutzerverwaltung-bundle
 *
 */

/**
 * Add to palette
 */
\Contao\CoreBundle\DataContainer\PaletteManipulator::create()
    ->addLegend('rsz_benutzerverwaltung', \Contao\CoreBundle\DataContainer\PaletteManipulator::POSITION_APPEND)
    ->addField(['mcupic_be_benutzerverwaltung_niveau', 'mcupic_be_benutzerverwaltung_trainingsgruppe'], 'rsz_benutzerverwaltung')
    ->applyToPalette('default', 'tl_settings');

/**
 * Add fields
 */
$GLOBALS['TL_DCA']['tl_settings']['fields']['mcupic_be_benutzerverwaltung_niveau'] = [
    'label'     => &$GLOBALS['TL_LANG']['tl_settings']['mcupic_be_benutzerverwaltung_niveau'],
    'inputType' => 'text',
    'eval'      => ['tl_class' => 'long clr']
];

$GLOBALS['TL_DCA']['tl_settings']['fields']['mcupic_be_benutzerverwaltung_trainingsgruppe'] = [
    'label'     => &$GLOBALS['TL_LANG']['tl_settings']['mcupic_be_benutzerverwaltung_trainingsgruppe'],
    'inputType' => 'text',
    'eval'      => ['tl_class' => 'long clr']
];



