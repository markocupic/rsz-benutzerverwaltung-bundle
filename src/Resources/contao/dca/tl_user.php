<?php

/**
 * @copyright  Marko Cupic 2020 <m.cupic@gmx.ch>
 * @author     Marko Cupic
 * @package    RSZ Benutzerverwaltung
 * @license    MIT
 * @see        https://github.com/markocupic/rsz-benutzerverwaltung-bundle
 *
 */

// Extend admin, extend, default and custom palette
Contao\CoreBundle\DataContainer\PaletteManipulator::create()
    ->addLegend('trainer_legend', 'name_legend', Contao\CoreBundle\DataContainer\PaletteManipulator::POSITION_AFTER)
    ->addLegend('athlete_legend', 'name_legend', Contao\CoreBundle\DataContainer\PaletteManipulator::POSITION_AFTER)
    ->addLegend('information_legend', 'name_legend', Contao\CoreBundle\DataContainer\PaletteManipulator::POSITION_AFTER)
    ->addLegend('extended_data', 'name_legend', Contao\CoreBundle\DataContainer\PaletteManipulator::POSITION_AFTER)
    ->addLegend('contact_legend', 'name_legend', Contao\CoreBundle\DataContainer\PaletteManipulator::POSITION_AFTER)
    // Add email to the contact legend
    ->removeField(['email'], 'name_legend')
    ->addField(
        ['isRSZ'],
        'name_legend',
        Contao\CoreBundle\DataContainer\PaletteManipulator::POSITION_PREPEND
    )
    ->addField(
        ['gender', 'street', 'postal', 'city', 'dateOfBirth', 'avatar'],
        'name_legend',
        Contao\CoreBundle\DataContainer\PaletteManipulator::POSITION_APPEND
    )
    ->addField(
        ['email', 'telephone', 'mobile', 'alternate_email', 'alternate_email_2', 'url'],
        'contact_legend',
        Contao\CoreBundle\DataContainer\PaletteManipulator::POSITION_APPEND
    )
    ->addField(
        ['iban', 'ahv_nr', 'fe_sorting'],
        'extended_data',
        Contao\CoreBundle\DataContainer\PaletteManipulator::POSITION_APPEND
    )
    ->addField(
        ['sac_sektion', 'funktion', 'funktionsbeschreibung'],
        'information_legend',
        Contao\CoreBundle\DataContainer\PaletteManipulator::POSITION_APPEND
    )
    ->addField(
        ['nationalmannschaft', 'niveau', 'trainingsgruppe', 'link_digitalrock', 'kategorie'],
        'athlete_legend',
        Contao\CoreBundle\DataContainer\PaletteManipulator::POSITION_APPEND
    )
    ->addField(
        ['trainerqualifikation', 'trainerFromGroup'],
        'trainer_legend',
        Contao\CoreBundle\DataContainer\PaletteManipulator::POSITION_APPEND
    )
    ->applyToPalette('admin', 'tl_user')
    ->applyToPalette('default', 'tl_user')
    ->applyToPalette('extend', 'tl_user')
    ->applyToPalette('custom', 'tl_user')
    ->applyToPalette('group', 'tl_user');

// Login palette
Contao\CoreBundle\DataContainer\PaletteManipulator::create()
    ->addLegend('extended_data', 'name_legend', Contao\CoreBundle\DataContainer\PaletteManipulator::POSITION_AFTER)
    ->addLegend('information_legend', 'name_legend', Contao\CoreBundle\DataContainer\PaletteManipulator::POSITION_AFTER)
    ->addLegend('contact_legend', 'name_legend', Contao\CoreBundle\DataContainer\PaletteManipulator::POSITION_AFTER)
    ->removeField(['email'], 'name_legend')
    ->addField(
        ['gender', 'street', 'postal', 'city', 'dateOfBirth', 'avatar'],
        'name_legend',
        Contao\CoreBundle\DataContainer\PaletteManipulator::POSITION_APPEND
    )
    ->addField(
        ['iban', 'ahv_nr'],
        'extended_data',
        Contao\CoreBundle\DataContainer\PaletteManipulator::POSITION_APPEND
    )
    ->addField(
        ['email', 'telephone', 'mobile', 'url'],
        'contact_legend',
        Contao\CoreBundle\DataContainer\PaletteManipulator::POSITION_APPEND
    )
    ->addField(
        ['sac_sektion'],
        'information_legend',
        Contao\CoreBundle\DataContainer\PaletteManipulator::POSITION_APPEND
    )
    ->applyToPalette('login', 'tl_user');

// Onload_callback callbacks
$GLOBALS['TL_DCA']['tl_user']['config']['onload_callback'][] = [
    Markocupic\RszBenutzerverwaltungBundle\RszUser\RszUser::class,
    'maintainUserProperties'
];

$GLOBALS['TL_DCA']['tl_user']['config']['ondelete_callback'][] = [
    Markocupic\RszBenutzerverwaltungBundle\RszUser\RszUser::class,
    'deleteAssignedMember'
];

// Fields
$GLOBALS['TL_DCA']['tl_user']['fields']['isRSZ'] = [
    'search'    => true,
    'exclude'   => true,
    'sorting'   => true,
    'filter'    => true,
    'flag'      => 1,
    'inputType' => 'select',
    'options'   => [0, 1],
    'default'   => 1,
    'eval'      => ['tl_class' => ''],
    'sql'       => "char(1) NOT NULL default '0'"
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
    'flag'      => 1,
    'inputType' => 'text',
    'eval'      => ['maxlength' => 255, 'tl_class' => ''],
    'sql'       => "varchar(255) NOT NULL default ''"
];

$GLOBALS['TL_DCA']['tl_user']['fields']['postal'] = [
    'search'    => true,
    'exclude'   => true,
    'sorting'   => true,
    'flag'      => 1,
    'inputType' => 'text',
    'eval'      => ['maxlength' => 4, 'tl_class' => ''],
    'sql'       => "varchar(32) NOT NULL default ''"
];

$GLOBALS['TL_DCA']['tl_user']['fields']['city'] = [
    'search'    => true,
    'exclude'   => true,
    'sorting'   => true,
    'flag'      => 1,
    'inputType' => 'text',
    'eval'      => ['maxlength' => 255, 'tl_class' => ''],
    'sql'       => "varchar(255) NOT NULL default ''"
];

$GLOBALS['TL_DCA']['tl_user']['fields']['telephone'] = [
    'search'    => true,
    'exclude'   => true,
    'sorting'   => true,
    'flag'      => 1,
    'inputType' => 'text',
    'eval'      => ['mandatory' => false, 'rgxp' => 'phone', 'maxlength' => 13, 'tl_class' => ''],
    'sql'       => "varchar(255) NOT NULL default ''"
];

$GLOBALS['TL_DCA']['tl_user']['fields']['mobile'] = [
    'search'    => true,
    'exclude'   => true,
    'sorting'   => true,
    'flag'      => 1,
    'inputType' => 'text',
    'eval'      => ['mandatory' => false, 'rgxp' => 'phone', 'maxlength' => 13, 'tl_class' => ''],
    'sql'       => "varchar(255) NOT NULL default ''"
];

$GLOBALS['TL_DCA']['tl_user']['fields']['alternate_email'] = [
    'search'    => true,
    'exclude'   => true,
    'sorting'   => true,
    'flag'      => 1,
    'inputType' => 'text',
    'eval'      => ['mandatory' => false, 'rgxp' => 'email', 'tl_class' => ''],
    'sql'       => "varchar(255) NOT NULL default ''"
];

$GLOBALS['TL_DCA']['tl_user']['fields']['alternate_email_2'] = [
    'search'    => true,
    'exclude'   => true,
    'sorting'   => true,
    'flag'      => 1,
    'inputType' => 'text',
    'eval'      => ['mandatory' => false, 'rgxp' => 'email', 'tl_class' => ''],
    'sql'       => "varchar(255) NOT NULL default ''"
];

$GLOBALS['TL_DCA']['tl_user']['fields']['url'] = [
    'search'    => true,
    'exclude'   => true,
    'sorting'   => true,
    'flag'      => 1,
    'inputType' => 'text',
    'eval'      => ['mandatory' => false, 'rgxp' => 'url', 'maxlength' => 255, 'tl_class' => ''],
    'sql'       => "varchar(255) NOT NULL default ''"
];

$GLOBALS['TL_DCA']['tl_user']['fields']['kategorie'] = [
    'search'    => true,
    'exclude'   => true,
    'sorting'   => true,
    'filter'    => true,
    'flag'      => 1,
    'inputType' => 'select',
    'options'   => \Contao\System::getContainer()->getParameter('rsz-wettkampfkategorien'),
    'eval'      => ['mandatory' => false, 'maxlength' => 255, 'tl_class' => '', 'includeBlankOption' => true],
    'sql'       => "varchar(255) NOT NULL default ''"
];

$GLOBALS['TL_DCA']['tl_user']['fields']['link_digitalrock'] = [
    'search'    => true,
    'exclude'   => true,
    'sorting'   => true,
    'flag'      => 1,
    'inputType' => 'text',
    'eval'      => ['mandatory' => false, 'maxlength' => 255, 'tl_class' => ''],
    'sql'       => "varchar(255) NOT NULL default ''"
];

$GLOBALS['TL_DCA']['tl_user']['fields']['niveau'] = [
    'search'    => true,
    'exclude'   => true,
    'sorting'   => true,
    'filter'    => true,
    'flag'      => 1,
    'inputType' => 'select',
    'options'   => explode(',', $GLOBALS['TL_CONFIG']['mcupic_be_benutzerverwaltung_niveau']),
    'eval'      => ['includeBlankOption' => true, 'tl_class' => ''],
    'sql'       => "blob NULL"
];

$GLOBALS['TL_DCA']['tl_user']['fields']['trainerFromGroup'] = [
    'search'    => true,
    'exclude'   => true,
    'sorting'   => true,
    'filter'    => true,
    'flag'      => 1,
    'inputType' => 'select',
    'options'   => explode(',', $GLOBALS['TL_CONFIG']['mcupic_be_benutzerverwaltung_trainingsgruppe']),
    'eval'      => ['includeBlankOption' => true, 'tl_class' => '', 'multiple' => true, 'chosen' => true],
    'sql'       => "blob NULL"
];

$GLOBALS['TL_DCA']['tl_user']['fields']['trainingsgruppe'] = [
    'search'    => true,
    'exclude'   => true,
    'sorting'   => true,
    'filter'    => true,
    'flag'      => 1,
    'inputType' => 'select',
    'options'   => explode(',', $GLOBALS['TL_CONFIG']['mcupic_be_benutzerverwaltung_trainingsgruppe']),
    'eval'      => ['includeBlankOption' => true, 'tl_class' => ''],
    'sql'       => "blob NULL"
];

$GLOBALS['TL_DCA']['tl_user']['fields']['funktion'] = [
    'search'    => true,
    'filter'    => true,
    'exclude'   => true,
    'sorting'   => true,
    'flag'      => 1,
    'inputType' => 'checkbox',
    'options'   => explode(',', $GLOBALS['TL_CONFIG']['mcupic_be_benutzerverwaltung_funktion']),
    'eval'      => ['mandatory' => false, 'multiple' => true, 'tl_class' => ''],
    'sql'       => "blob NULL"
];

$GLOBALS['TL_DCA']['tl_user']['fields']['funktionsbeschreibung'] = [
    'search'    => true,
    'exclude'   => true,
    'sorting'   => true,
    'flag'      => 1,
    'inputType' => 'text',
    'eval'      => ['tl_class' => ''],
    'sql'       => "varchar(255) NOT NULL default ''"
];

$GLOBALS['TL_DCA']['tl_user']['fields']['ahv_nr'] = [
    'search'    => true,
    'exclude'   => true,
    'sorting'   => true,
    'flag'      => 1,
    'inputType' => 'text',
    'default'   => '756.',
    'eval'      => ['tl_class' => '', 'maxlength' => 16],
    'sql'       => "varchar(16) NOT NULL default '756.'"
];

$GLOBALS['TL_DCA']['tl_user']['fields']['iban'] = [
    'search'    => true,
    'exclude'   => true,
    'sorting'   => true,
    'flag'      => 1,
    'inputType' => 'text',
    'eval'      => ['tl_class' => '', 'maxlength' => 26],
    'sql'       => "varchar(26) NOT NULL default ''"
];

$GLOBALS['TL_DCA']['tl_user']['fields']['trainerqualifikation'] = [
    'search'    => true,
    'exclude'   => true,
    'sorting'   => true,
    'filter'    => true,
    'flag'      => 1,
    'inputType' => 'checkbox',
    'options'   => explode(',', $GLOBALS['TL_CONFIG']['mcupic_be_benutzerverwaltung_trainerqualifikation']),
    'eval'      => ['multiple' => true, 'tl_class' => ''],
    'sql'       => "blob NULL"
];

$GLOBALS['TL_DCA']['tl_user']['fields']['nationalmannschaft'] = [
    'search'    => true,
    'exclude'   => true,
    'sorting'   => true,
    'filter'    => true,
    'flag'      => 1,
    'inputType' => 'select',
    'options'   => ['0' => 'false', '1' => 'true'],
    'eval'      => ['tl_class' => ''],
    'sql'       => "int(1) NOT NULL default '0'"
];

$GLOBALS['TL_DCA']['tl_user']['fields']['dateOfBirth'] = [
    'exclude'   => true,
    'search'    => true,
    'sorting'   => true,
    'inputType' => 'text',
    'eval'      => ['maxlength' => 10, 'datepicker' => $this->getDatePickerString(), 'submitOnChange' => false, 'rgxp' => 'date', 'tl_class' => ' wizard'],
    'sql'       => "int(14) NOT NULL default '0'"
];

$GLOBALS['TL_DCA']['tl_user']['fields']['sac_sektion'] = [
    'exclude'   => true,
    'search'    => true,
    'sorting'   => true,
    'filter'    => true,
    'flag'      => 1,
    'inputType' => 'select',
    'options'   => \Contao\System::getContainer()->getParameter('rsz-sac-sektionen'),
    'eval'      => ['includeBlankOption' => true, 'tl_class' => ''],
    'sql'       => "blob NULL"
];

$GLOBALS['TL_DCA']['tl_user']['fields']['fe_sorting'] = [
    'search'    => true,
    'exclude'   => true,
    'sorting'   => true,
    'filter'    => true,
    'inputType' => 'text',
    'eval'      => ['rgxp' => 'digit'],
    'sql'       => "int(14) NOT NULL default '999'"
];

$GLOBALS['TL_DCA']['tl_user']['fields']['avatar'] = [
    'exclude'   => true,
    'inputType' => 'fileTree',
    'eval'      => ['filesOnly' => true, 'fieldType' => 'radio', 'extensions' => 'jpg,jpeg,png', 'mandatory' => false, 'tl_class' => 'clr'],
    'sql'       => "binary(16) NULL"
];

$GLOBALS['TL_DCA']['tl_user']['fields']['assignedMember'] = [
    'sql' => "int(10) NOT NULL default '0'"
];

$GLOBALS['TL_DCA']['tl_user']['fields']['getPasswordField'] = [
    'sql' => "char(1) NOT NULL default ''"
];

$GLOBALS['TL_DCA']['tl_user']['fields']['username']['eval']['tl_class'] = 'clr';

$GLOBALS['TL_DCA']['tl_user']['fields']['name']['eval']['rgxp'] = 'name';
$GLOBALS['TL_DCA']['tl_user']['fields']['name']['eval']['tl_class'] = 'clr';

$GLOBALS['TL_DCA']['tl_user']['fields']['email']['eval']['tl_class'] = 'clr';

