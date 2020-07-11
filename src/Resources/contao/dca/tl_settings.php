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

$GLOBALS['TL_DCA']['tl_settings']['palettes']['default'] .= ';{rsz_benutzerverwaltung:hide},mcupic_be_benutzerverwaltung_sac_sektion,mcupic_be_benutzerverwaltung_kategorie,mcupic_be_benutzerverwaltung_funktion,mcupic_be_benutzerverwaltung_niveau,mcupic_be_benutzerverwaltung_trainingsgruppe,mcupic_be_benutzerverwaltung_trainerqualifikation,mcupic_be_benutzerverwaltung_id_group_address_admin';

/**
 * Add fields
 */

$GLOBALS['TL_DCA']['tl_settings']['fields']['mcupic_be_benutzerverwaltung_sac_sektion'] = [
    'label'     => &$GLOBALS['TL_LANG']['tl_settings']['mcupic_be_benutzerverwaltung_sac_sektion'],
    'inputType' => 'text',
    'eval'      => ['tl_class' => 'long clr']
];
$GLOBALS['TL_DCA']['tl_settings']['fields']['mcupic_be_benutzerverwaltung_kategorie'] = [
    'label'     => &$GLOBALS['TL_LANG']['tl_settings']['mcupic_be_benutzerverwaltung_kategorie'],
    'inputType' => 'text',
    'eval'      => ['tl_class' => 'long clr']
];
$GLOBALS['TL_DCA']['tl_settings']['fields']['mcupic_be_benutzerverwaltung_funktion'] = [
    'label'     => &$GLOBALS['TL_LANG']['tl_settings']['mcupic_be_benutzerverwaltung_funktion'],
    'inputType' => 'text',
    'eval'      => ['tl_class' => 'long clr']
];
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
$GLOBALS['TL_DCA']['tl_settings']['fields']['mcupic_be_benutzerverwaltung_trainerqualifikation'] = [
    'label'     => &$GLOBALS['TL_LANG']['tl_settings']['mcupic_be_benutzerverwaltung_trainerqualifikation'],
    'inputType' => 'text',
    'eval'      => ['tl_class' => 'long clr']
];
$GLOBALS['TL_DCA']['tl_settings']['fields']['mcupic_be_benutzerverwaltung_id_group_address_admin'] = [
    'label'     => &$GLOBALS['TL_LANG']['tl_settings']['mcupic_be_benutzerverwaltung_id_group_address_admin'],
    'inputType' => 'text',
    'eval'      => ['tl_class' => 'long clr']
];
