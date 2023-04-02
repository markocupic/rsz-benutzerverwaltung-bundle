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
use Contao\System;
use Contao\DataContainer;

// Extend admin, extend, default and custom palette
PaletteManipulator::create()
    ->addLegend('trainer_legend', 'name_legend', PaletteManipulator::POSITION_AFTER)
    ->addLegend('athlete_legend', 'name_legend', PaletteManipulator::POSITION_AFTER)
    ->addLegend('information_legend', 'name_legend', PaletteManipulator::POSITION_AFTER)
    ->addLegend('extended_data', 'name_legend', PaletteManipulator::POSITION_AFTER)
    ->addLegend('contact_legend', 'name_legend', PaletteManipulator::POSITION_AFTER)
    ->addLegend('parent_legend', 'contact_legend', PaletteManipulator::POSITION_AFTER)

    // Add email to the contact legend
    ->removeField(['email'], 'name_legend')
    ->addField(
        ['isRSZ'],
        'name_legend',
        PaletteManipulator::POSITION_PREPEND
    )
    ->addField(
        ['gender', 'street', 'postal', 'city', 'dateOfBirth', 'avatar'],
        'name_legend',
        PaletteManipulator::POSITION_APPEND
    )
    ->addField(
        ['email', 'telephone', 'mobile', 'url'],
        'contact_legend',
        PaletteManipulator::POSITION_APPEND
    )
    ->addField(
        ['mother_firstname', 'mother_lastname', 'mother_email', 'mother_mobile', 'father_firstname', 'father_lastname', 'father_email', 'father_mobile',],
        'parent_legend',
        PaletteManipulator::POSITION_APPEND
    )
    ->addField(
        ['js_nr', 'iban', 'ahv_nr', 'fe_sorting'],
        'extended_data',
        PaletteManipulator::POSITION_APPEND
    )
    ->addField(
        ['sac_sektion', 'funktion', 'funktionsbeschreibung'],
        'information_legend',
        PaletteManipulator::POSITION_APPEND
    )
    ->addField(
        ['nationalmannschaft', 'niveau', 'trainingsgruppe', 'link_digitalrock', 'kategorie'],
        'athlete_legend',
        PaletteManipulator::POSITION_APPEND
    )
    ->addField(
        ['trainerqualifikation', 'trainerFromGroup'],
        'trainer_legend',
        PaletteManipulator::POSITION_APPEND
    )
    ->applyToPalette('admin', 'tl_user')
    ->applyToPalette('default', 'tl_user')
    ->applyToPalette('extend', 'tl_user')
    ->applyToPalette('custom', 'tl_user')
    ->applyToPalette('group', 'tl_user');

// Login palette
PaletteManipulator::create()
    ->addLegend('extended_data', 'name_legend', PaletteManipulator::POSITION_AFTER)
    ->addLegend('information_legend', 'name_legend', PaletteManipulator::POSITION_AFTER)
    ->addLegend('contact_legend', 'name_legend', PaletteManipulator::POSITION_AFTER)
    ->addLegend('parent_legend', 'contact_legend', PaletteManipulator::POSITION_AFTER)
    ->removeField(['email'], 'name_legend')
    ->addField(
        ['gender', 'street', 'postal', 'city', 'dateOfBirth', 'avatar'],
        'name_legend',
        PaletteManipulator::POSITION_APPEND
    )
    ->addField(
        ['js_nr', 'iban', 'ahv_nr'],
        'extended_data',
        PaletteManipulator::POSITION_APPEND
    )
    ->addField(
        ['email', 'telephone', 'mobile', 'url'],
        'contact_legend',
        PaletteManipulator::POSITION_APPEND
    )
    ->addField(
        ['mother_firstname', 'mother_lastname', 'mother_email', 'mother_mobile', 'father_firstname', 'father_lastname', 'father_email', 'father_mobile',],
        'parent_legend',
        PaletteManipulator::POSITION_APPEND
    )
    ->addField(
        ['sac_sektion'],
        'information_legend',
        PaletteManipulator::POSITION_APPEND
    )
    ->applyToPalette('login', 'tl_user');

$GLOBALS['TL_DCA']['tl_user']['list']['global_operations']['addressDownload'] = [
    'label'      => &$GLOBALS['TL_LANG']['tl_user']['addressDownload'],
    'route'      => 'markocupic_rsz_benutzerverwaltung_rsz_address_download',
    'class'      => 'header_icon',
    'icon'       => 'bundles/markocupicrszbenutzerverwaltung/excel.svg',
    'attributes' => 'onclick="Backend.getScrollOffset();" accesskey="i"',
];

// Fields
$GLOBALS['TL_DCA']['tl_user']['fields']['isRSZ'] = [
    'search'    => true,
    'exclude'   => true,
    'sorting'   => true,
    'filter'    => true,
    'flag'      => DataContainer::SORT_INITIAL_LETTER_ASC,
    'inputType' => 'select',
    'options'   => ['' => 'false', '1' => 'true'],
    'default'   => 1,
    'eval'      => ['tl_class' => ''],
    'sql'       => "char(1) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_user']['fields']['gender'] = [
    'search'    => true,
    'exclude'   => true,
    'sorting'   => true,
    'inputType' => 'select',
    'filter'    => true,
    'options'   => ['male', 'female'],
    'reference' => &$GLOBALS['TL_LANG']['MSC'],
    'eval'      => ['includeBlankOption' => true, 'mandatory' => true, 'maxlength' => 255, 'tl_class' => ''],
    'sql'       => "varchar(30) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_user']['fields']['street'] = [
    'search'    => true,
    'exclude'   => true,
    'sorting'   => true,
    'flag'      => DataContainer::SORT_INITIAL_LETTER_ASC,
    'inputType' => 'text',
    'eval'      => ['maxlength' => 255, 'tl_class' => ''],
    'sql'       => "varchar(255) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_user']['fields']['postal'] = [
    'search'    => true,
    'exclude'   => true,
    'sorting'   => true,
    'flag'      => DataContainer::SORT_INITIAL_LETTER_ASC,
    'inputType' => 'text',
    'eval'      => ['maxlength' => 4, 'tl_class' => ''],
    'sql'       => "varchar(32) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_user']['fields']['city'] = [
    'search'    => true,
    'exclude'   => true,
    'sorting'   => true,
    'flag'      => DataContainer::SORT_INITIAL_LETTER_ASC,
    'inputType' => 'text',
    'eval'      => ['maxlength' => 255, 'tl_class' => ''],
    'sql'       => "varchar(255) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_user']['fields']['telephone'] = [
    'search'    => true,
    'exclude'   => true,
    'sorting'   => true,
    'flag'      => DataContainer::SORT_INITIAL_LETTER_ASC,
    'inputType' => 'text',
    'eval'      => ['mandatory' => false, 'rgxp' => 'phone', 'maxlength' => 13, 'tl_class' => ''],
    'sql'       => "varchar(255) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_user']['fields']['mobile'] = [
    'search'    => true,
    'exclude'   => true,
    'sorting'   => true,
    'flag'      => DataContainer::SORT_INITIAL_LETTER_ASC,
    'inputType' => 'text',
    'eval'      => ['mandatory' => false, 'rgxp' => 'phone', 'maxlength' => 13, 'tl_class' => ''],
    'sql'       => "varchar(255) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_user']['fields']['url'] = [
    'search'    => true,
    'exclude'   => true,
    'sorting'   => true,
    'flag'      => DataContainer::SORT_INITIAL_LETTER_ASC,
    'inputType' => 'text',
    'eval'      => ['mandatory' => false, 'rgxp' => 'url', 'maxlength' => 255, 'tl_class' => ''],
    'sql'       => "varchar(255) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_user']['fields']['mother_firstname'] = [
    'search'    => true,
    'exclude'   => true,
    'sorting'   => true,
    'flag'      => DataContainer::SORT_INITIAL_LETTER_ASC,
    'inputType' => 'text',
    'eval'      => ['maxlength' => 255, 'tl_class' => 'w50'],
    'sql'       => "varchar(255) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_user']['fields']['mother_lastname'] = [
    'search'    => true,
    'exclude'   => true,
    'sorting'   => true,
    'flag'      => DataContainer::SORT_INITIAL_LETTER_ASC,
    'inputType' => 'text',
    'eval'      => ['maxlength' => 255, 'tl_class' => 'w50'],
    'sql'       => "varchar(255) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_user']['fields']['mother_email'] = [
    'exclude'   => true,
    'flag'      => DataContainer::SORT_INITIAL_LETTER_ASC,
    'inputType' => 'text',
    'eval'      => ['maxlength' => 255, 'rgxp' => 'email', 'decodeEntities' => true, 'tl_class' => 'w50'],
    'sql'       => "varchar(255) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_user']['fields']['mother_mobile'] = [
    'exclude'   => true,
    'inputType' => 'text',
    'eval'      => ['maxlength' => 64, 'rgxp' => 'phone', 'decodeEntities' => true, 'tl_class' => 'w50'],
    'sql'       => "varchar(255) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_user']['fields']['father_firstname'] = [
    'search'    => true,
    'exclude'   => true,
    'sorting'   => true,
    'flag'      => DataContainer::SORT_INITIAL_LETTER_ASC,
    'inputType' => 'text',
    'eval'      => ['maxlength' => 255, 'tl_class' => 'w50'],
    'sql'       => "varchar(255) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_user']['fields']['father_lastname'] = [
    'search'    => true,
    'exclude'   => true,
    'sorting'   => true,
    'flag'      => DataContainer::SORT_INITIAL_LETTER_ASC,
    'inputType' => 'text',
    'eval'      => ['maxlength' => 255, 'tl_class' => 'w50'],
    'sql'       => "varchar(255) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_user']['fields']['father_email'] = [
    'exclude'   => true,
    'flag'      => DataContainer::SORT_INITIAL_LETTER_ASC,
    'inputType' => 'text',
    'eval'      => ['maxlength' => 255, 'rgxp' => 'email', 'decodeEntities' => true, 'tl_class' => 'w50'],
    'sql'       => "varchar(255) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_user']['fields']['father_mobile'] = [
    'exclude'   => true,
    'inputType' => 'text',
    'eval'      => ['maxlength' => 64, 'rgxp' => 'phone', 'decodeEntities' => true, 'tl_class' => 'w50'],
    'sql'       => "varchar(255) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_user']['fields']['kategorie'] = [
    'search'    => true,
    'exclude'   => true,
    'sorting'   => true,
    'filter'    => true,
    'flag'      => DataContainer::SORT_INITIAL_LETTER_ASC,
    'inputType' => 'select',
    'options'   => System::getContainer()->getParameter('rsz-wettkampfkategorien'),
    'eval'      => ['mandatory' => false, 'maxlength' => 255, 'tl_class' => '', 'includeBlankOption' => true],
    'sql'       => "varchar(255) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_user']['fields']['link_digitalrock'] = [
    'search'    => true,
    'exclude'   => true,
    'sorting'   => true,
    'flag'      => DataContainer::SORT_INITIAL_LETTER_ASC,
    'inputType' => 'text',
    'eval'      => ['mandatory' => false, 'maxlength' => 255, 'tl_class' => ''],
    'sql'       => "varchar(255) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_user']['fields']['niveau'] = [
    'search'    => true,
    'exclude'   => true,
    'sorting'   => true,
    'filter'    => true,
    'flag'      => DataContainer::SORT_INITIAL_LETTER_ASC,
    'inputType' => 'select',
    'options'   => explode(',', $GLOBALS['TL_CONFIG']['mcupic_be_benutzerverwaltung_niveau']),
    'eval'      => ['includeBlankOption' => true, 'tl_class' => ''],
    'sql'       => 'blob NULL',
];

$GLOBALS['TL_DCA']['tl_user']['fields']['trainerFromGroup'] = [
    'search'    => true,
    'exclude'   => true,
    'sorting'   => true,
    'filter'    => true,
    'flag'      => DataContainer::SORT_INITIAL_LETTER_ASC,
    'inputType' => 'select',
    'options'   => explode(',', $GLOBALS['TL_CONFIG']['mcupic_be_benutzerverwaltung_trainingsgruppe']),
    'eval'      => ['includeBlankOption' => true, 'tl_class' => '', 'multiple' => true, 'chosen' => true],
    'sql'       => 'blob NULL',
];

$GLOBALS['TL_DCA']['tl_user']['fields']['trainingsgruppe'] = [
    'search'    => true,
    'exclude'   => true,
    'sorting'   => true,
    'filter'    => true,
    'flag'      => DataContainer::SORT_INITIAL_LETTER_ASC,
    'inputType' => 'select',
    'options'   => explode(',', $GLOBALS['TL_CONFIG']['mcupic_be_benutzerverwaltung_trainingsgruppe']),
    'eval'      => ['includeBlankOption' => true, 'tl_class' => ''],
    'sql'       => 'blob NULL',
];

$GLOBALS['TL_DCA']['tl_user']['fields']['funktion'] = [
    'search'    => true,
    'filter'    => true,
    'exclude'   => true,
    'sorting'   => true,
    'flag'      => DataContainer::SORT_INITIAL_LETTER_ASC,
    'inputType' => 'checkbox',
    'options'   => System::getContainer()->getParameter('rsz-funktion'),
    'eval'      => ['mandatory' => false, 'multiple' => true, 'tl_class' => ''],
    'sql'       => 'blob NULL',
];

$GLOBALS['TL_DCA']['tl_user']['fields']['funktionsbeschreibung'] = [
    'search'    => true,
    'exclude'   => true,
    'sorting'   => true,
    'flag'      => DataContainer::SORT_INITIAL_LETTER_ASC,
    'inputType' => 'text',
    'eval'      => ['tl_class' => ''],
    'sql'       => "varchar(255) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_user']['fields']['ahv_nr'] = [
    'search'    => true,
    'exclude'   => true,
    'sorting'   => true,
    'flag'      => DataContainer::SORT_INITIAL_LETTER_ASC,
    'inputType' => 'text',
    'default'   => '756.',
    'eval'      => ['tl_class' => '', 'maxlength' => 16],
    'sql'       => "varchar(16) NOT NULL default '756.'",
];

$GLOBALS['TL_DCA']['tl_user']['fields']['iban'] = [
    'search'    => true,
    'exclude'   => true,
    'sorting'   => true,
    'flag'      => DataContainer::SORT_INITIAL_LETTER_ASC,
    'inputType' => 'text',
    'eval'      => ['tl_class' => '', 'maxlength' => 26],
    'sql'       => "varchar(26) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_user']['fields']['js_nr'] = [
    'search'    => true,
    'exclude'   => true,
    'sorting'   => true,
    'flag'      => DataContainer::SORT_INITIAL_LETTER_ASC,
    'inputType' => 'text',
    'eval'      => ['tl_class' => '', 'maxlength' => 8, 'rgxp' => 'natural'],
    'sql'       => "varchar(8) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_user']['fields']['trainerqualifikation'] = [
    'search'    => true,
    'exclude'   => true,
    'sorting'   => true,
    'filter'    => true,
    'flag'      => DataContainer::SORT_INITIAL_LETTER_ASC,
    'inputType' => 'checkbox',
    'options'   => System::getContainer()->getParameter('rsz-leiterqualifikation'),
    'eval'      => ['multiple' => true, 'tl_class' => ''],
    'sql'       => 'blob NULL',
];

$GLOBALS['TL_DCA']['tl_user']['fields']['nationalmannschaft'] = [
    'search'    => true,
    'exclude'   => true,
    'sorting'   => true,
    'filter'    => true,
    'flag'      => DataContainer::SORT_INITIAL_LETTER_ASC,
    'inputType' => 'select',
    'options'   => ['' => 'false', '1' => 'true'],
    'eval'      => ['tl_class' => ''],
    'sql'       => "char(1) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_user']['fields']['dateOfBirth'] = [
    'exclude'   => true,
    'search'    => true,
    'sorting'   => true,
    'inputType' => 'text',
    'eval'      => ['maxlength' => 10, 'datepicker' => $this->getDatePickerString(), 'submitOnChange' => false, 'rgxp' => 'date', 'tl_class' => ' wizard'],
    'sql'       => "int(14) NOT NULL default '0'",
];

$GLOBALS['TL_DCA']['tl_user']['fields']['sac_sektion'] = [
    'exclude'   => true,
    'search'    => true,
    'sorting'   => true,
    'filter'    => true,
    'flag'      => DataContainer::SORT_INITIAL_LETTER_ASC,
    'inputType' => 'select',
    'options'   => System::getContainer()->getParameter('rsz-sac-sektionen'),
    'eval'      => ['includeBlankOption' => true, 'tl_class' => ''],
    'sql'       => 'blob NULL',
];

$GLOBALS['TL_DCA']['tl_user']['fields']['fe_sorting'] = [
    'search'    => true,
    'exclude'   => true,
    'sorting'   => true,
    'filter'    => true,
    'inputType' => 'text',
    'eval'      => ['rgxp' => 'digit'],
    'sql'       => "int(14) NOT NULL default '999'",
];

$GLOBALS['TL_DCA']['tl_user']['fields']['avatar'] = [
    'exclude'   => true,
    'inputType' => 'fileTree',
    'eval'      => ['filesOnly' => true, 'fieldType' => 'radio', 'extensions' => 'jpg,jpeg,png', 'mandatory' => false, 'tl_class' => 'clr'],
    'sql'       => 'binary(16) NULL',
];

$GLOBALS['TL_DCA']['tl_user']['fields']['assignedMember'] = [
    'sql' => "int(10) NOT NULL default '0'",
];

$GLOBALS['TL_DCA']['tl_user']['fields']['getPasswordField'] = [
    'sql' => "char(1) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_user']['fields']['username']['eval']['tl_class'] = 'clr';
$GLOBALS['TL_DCA']['tl_user']['fields']['name']['eval']['rgxp'] = 'name';
$GLOBALS['TL_DCA']['tl_user']['fields']['name']['eval']['tl_class'] = 'clr';
$GLOBALS['TL_DCA']['tl_user']['fields']['email']['eval']['tl_class'] = 'clr';

// Extend the default palette with permissions
PaletteManipulator::create()
    ->addLegend('rsz_permission_legend', 'amg_legend', PaletteManipulator::POSITION_BEFORE)
    ->addField(['rsz_address_downloadp', 'rsz_usersp'], 'rsz_permission_legend', PaletteManipulator::POSITION_APPEND)
    ->applyToPalette('default', 'tl_user');

// Add fields to tl_user
$GLOBALS['TL_DCA']['tl_user']['fields']['rsz_address_downloadp'] = [
    'exclude'   => true,
    'inputType' => 'checkbox',
    'options'   => [
        'main_menu_download',
    ],
    'reference' => &$GLOBALS['TL_LANG']['MSC'],
    'eval'      => ['multiple' => true],
    'sql'       => 'blob NULL',
];

// Add fields to tl_user
$GLOBALS['TL_DCA']['tl_user']['fields']['rsz_usersp'] = [
    'exclude'   => true,
    'inputType' => 'checkbox',
    'options'   => [
        'can_edit_rsz_users',
        'can_delete_rsz_users',
    ],
    'reference' => &$GLOBALS['TL_LANG']['MSC'],
    'eval'      => ['multiple' => true],
    'sql'       => 'blob NULL',
];
