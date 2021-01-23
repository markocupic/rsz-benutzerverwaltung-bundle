<?php

/*
 * This file is part of RSZ Benutzerverwaltung Bundle.
 *
 * (c) Marko Cupic 2021 <m.cupic@gmx.ch>
 * @license MIT
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 * @link https://github.com/markocupic/rsz-benutzerverwaltung-bundle
 */

use Contao\CoreBundle\DataContainer\PaletteManipulator;
use Contao\System;
use Markocupic\RszBenutzerverwaltungBundle\RszUser\RszUser;

// Extend admin, extend, default and custom palette
PaletteManipulator::create()
	->addLegend('trainer_legend', 'name_legend', PaletteManipulator::POSITION_AFTER)
	->addLegend('athlete_legend', 'name_legend', PaletteManipulator::POSITION_AFTER)
	->addLegend('information_legend', 'name_legend', PaletteManipulator::POSITION_AFTER)
	->addLegend('extended_data', 'name_legend', PaletteManipulator::POSITION_AFTER)
	->addLegend('contact_legend', 'name_legend', PaletteManipulator::POSITION_AFTER)
	// Add email to the contact legend
	->removeField(array('email'), 'name_legend')
	->addField(
		array('isRSZ'),
		'name_legend',
		PaletteManipulator::POSITION_PREPEND
	)
	->addField(
		array('gender', 'street', 'postal', 'city', 'dateOfBirth', 'avatar'),
		'name_legend',
		PaletteManipulator::POSITION_APPEND
	)
	->addField(
		array('email', 'telephone', 'mobile', 'alternate_email', 'alternate_email_2', 'url'),
		'contact_legend',
		PaletteManipulator::POSITION_APPEND
	)
	->addField(
		array('iban', 'ahv_nr', 'fe_sorting'),
		'extended_data',
		PaletteManipulator::POSITION_APPEND
	)
	->addField(
		array('sac_sektion', 'funktion', 'funktionsbeschreibung'),
		'information_legend',
		PaletteManipulator::POSITION_APPEND
	)
	->addField(
		array('nationalmannschaft', 'niveau', 'trainingsgruppe', 'link_digitalrock', 'kategorie'),
		'athlete_legend',
		PaletteManipulator::POSITION_APPEND
	)
	->addField(
		array('trainerqualifikation', 'trainerFromGroup'),
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
	->removeField(array('email'), 'name_legend')
	->addField(
		array('gender', 'street', 'postal', 'city', 'dateOfBirth', 'avatar'),
		'name_legend',
		PaletteManipulator::POSITION_APPEND
	)
	->addField(
		array('iban', 'ahv_nr'),
		'extended_data',
		PaletteManipulator::POSITION_APPEND
	)
	->addField(
		array('email', 'telephone', 'mobile', 'url'),
		'contact_legend',
		PaletteManipulator::POSITION_APPEND
	)
	->addField(
		array('sac_sektion'),
		'information_legend',
		PaletteManipulator::POSITION_APPEND
	)
	->applyToPalette('login', 'tl_user');

// Onload_callback callbacks
$GLOBALS['TL_DCA']['tl_user']['config']['onload_callback'][] = array(
	RszUser::class,
	'maintainUserProperties'
);

$GLOBALS['TL_DCA']['tl_user']['config']['onload_callback'][] = array(
    RszUser::class,
    'prepareExcelExport'
);

$GLOBALS['TL_DCA']['tl_user']['config']['ondelete_callback'][] = array(
	RszUser::class,
	'deleteAssignedMember'
);

$GLOBALS['TL_DCA']['tl_user']['list']['global_operations']['excelExport'] = array(
	'label'      => &$GLOBALS['TL_LANG']['tl_user']['excelExport'],
	'href'       => 'act=excelExport',
	'class'      => 'header_icon',
	'icon'       => 'bundles/markocupicrszbenutzerverwaltung/excel.svg',
	'attributes' => 'onclick="Backend.getScrollOffset();" accesskey="i"',
);

// Fields
$GLOBALS['TL_DCA']['tl_user']['fields']['isRSZ'] = array(
	'search'    => true,
	'exclude'   => true,
	'sorting'   => true,
	'filter'    => true,
	'flag'      => 1,
	'inputType' => 'select',
	'options'   => array(0, 1),
	'default'   => 1,
	'eval'      => array('tl_class' => ''),
	'sql'       => "char(1) NOT NULL default '0'"
);

$GLOBALS['TL_DCA']['tl_user']['fields']['gender'] = array(
	'search'    => true,
	'exclude'   => true,
	'sorting'   => true,
	'inputType' => 'select',
	'filter'    => true,
	'options'   => array('male', 'female'),
	'reference' => &$GLOBALS['TL_LANG']['MSC'],
	'eval'      => array('includeBlankOption' => true, 'mandatory' => true, 'maxlength' => 255, 'tl_class' => ''),
	'sql'       => "varchar(30) NOT NULL default ''",
);

$GLOBALS['TL_DCA']['tl_user']['fields']['street'] = array(
	'search'    => true,
	'exclude'   => true,
	'sorting'   => true,
	'flag'      => 1,
	'inputType' => 'text',
	'eval'      => array('maxlength' => 255, 'tl_class' => ''),
	'sql'       => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_user']['fields']['postal'] = array(
	'search'    => true,
	'exclude'   => true,
	'sorting'   => true,
	'flag'      => 1,
	'inputType' => 'text',
	'eval'      => array('maxlength' => 4, 'tl_class' => ''),
	'sql'       => "varchar(32) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_user']['fields']['city'] = array(
	'search'    => true,
	'exclude'   => true,
	'sorting'   => true,
	'flag'      => 1,
	'inputType' => 'text',
	'eval'      => array('maxlength' => 255, 'tl_class' => ''),
	'sql'       => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_user']['fields']['telephone'] = array(
	'search'    => true,
	'exclude'   => true,
	'sorting'   => true,
	'flag'      => 1,
	'inputType' => 'text',
	'eval'      => array('mandatory' => false, 'rgxp' => 'phone', 'maxlength' => 13, 'tl_class' => ''),
	'sql'       => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_user']['fields']['mobile'] = array(
	'search'    => true,
	'exclude'   => true,
	'sorting'   => true,
	'flag'      => 1,
	'inputType' => 'text',
	'eval'      => array('mandatory' => false, 'rgxp' => 'phone', 'maxlength' => 13, 'tl_class' => ''),
	'sql'       => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_user']['fields']['alternate_email'] = array(
	'search'    => true,
	'exclude'   => true,
	'sorting'   => true,
	'flag'      => 1,
	'inputType' => 'text',
	'eval'      => array('mandatory' => false, 'rgxp' => 'email', 'tl_class' => ''),
	'sql'       => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_user']['fields']['alternate_email_2'] = array(
	'search'    => true,
	'exclude'   => true,
	'sorting'   => true,
	'flag'      => 1,
	'inputType' => 'text',
	'eval'      => array('mandatory' => false, 'rgxp' => 'email', 'tl_class' => ''),
	'sql'       => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_user']['fields']['url'] = array(
	'search'    => true,
	'exclude'   => true,
	'sorting'   => true,
	'flag'      => 1,
	'inputType' => 'text',
	'eval'      => array('mandatory' => false, 'rgxp' => 'url', 'maxlength' => 255, 'tl_class' => ''),
	'sql'       => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_user']['fields']['kategorie'] = array(
	'search'    => true,
	'exclude'   => true,
	'sorting'   => true,
	'filter'    => true,
	'flag'      => 1,
	'inputType' => 'select',
	'options'   => System::getContainer()->getParameter('rsz-wettkampfkategorien'),
	'eval'      => array('mandatory' => false, 'maxlength' => 255, 'tl_class' => '', 'includeBlankOption' => true),
	'sql'       => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_user']['fields']['link_digitalrock'] = array(
	'search'    => true,
	'exclude'   => true,
	'sorting'   => true,
	'flag'      => 1,
	'inputType' => 'text',
	'eval'      => array('mandatory' => false, 'maxlength' => 255, 'tl_class' => ''),
	'sql'       => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_user']['fields']['niveau'] = array(
	'search'    => true,
	'exclude'   => true,
	'sorting'   => true,
	'filter'    => true,
	'flag'      => 1,
	'inputType' => 'select',
	'options'   => explode(',', $GLOBALS['TL_CONFIG']['mcupic_be_benutzerverwaltung_niveau']),
	'eval'      => array('includeBlankOption' => true, 'tl_class' => ''),
	'sql'       => "blob NULL"
);

$GLOBALS['TL_DCA']['tl_user']['fields']['trainerFromGroup'] = array(
	'search'    => true,
	'exclude'   => true,
	'sorting'   => true,
	'filter'    => true,
	'flag'      => 1,
	'inputType' => 'select',
	'options'   => explode(',', $GLOBALS['TL_CONFIG']['mcupic_be_benutzerverwaltung_trainingsgruppe']),
	'eval'      => array('includeBlankOption' => true, 'tl_class' => '', 'multiple' => true, 'chosen' => true),
	'sql'       => "blob NULL"
);

$GLOBALS['TL_DCA']['tl_user']['fields']['trainingsgruppe'] = array(
	'search'    => true,
	'exclude'   => true,
	'sorting'   => true,
	'filter'    => true,
	'flag'      => 1,
	'inputType' => 'select',
	'options'   => explode(',', $GLOBALS['TL_CONFIG']['mcupic_be_benutzerverwaltung_trainingsgruppe']),
	'eval'      => array('includeBlankOption' => true, 'tl_class' => ''),
	'sql'       => "blob NULL"
);

$GLOBALS['TL_DCA']['tl_user']['fields']['funktion'] = array(
	'search'    => true,
	'filter'    => true,
	'exclude'   => true,
	'sorting'   => true,
	'flag'      => 1,
	'inputType' => 'checkbox',
	'options'   => System::getContainer()->getParameter('rsz-funktion'),
	'eval'      => array('mandatory' => false, 'multiple' => true, 'tl_class' => ''),
	'sql'       => "blob NULL"
);

$GLOBALS['TL_DCA']['tl_user']['fields']['funktionsbeschreibung'] = array(
	'search'    => true,
	'exclude'   => true,
	'sorting'   => true,
	'flag'      => 1,
	'inputType' => 'text',
	'eval'      => array('tl_class' => ''),
	'sql'       => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_user']['fields']['ahv_nr'] = array(
	'search'    => true,
	'exclude'   => true,
	'sorting'   => true,
	'flag'      => 1,
	'inputType' => 'text',
	'default'   => '756.',
	'eval'      => array('tl_class' => '', 'maxlength' => 16),
	'sql'       => "varchar(16) NOT NULL default '756.'"
);

$GLOBALS['TL_DCA']['tl_user']['fields']['iban'] = array(
	'search'    => true,
	'exclude'   => true,
	'sorting'   => true,
	'flag'      => 1,
	'inputType' => 'text',
	'eval'      => array('tl_class' => '', 'maxlength' => 26),
	'sql'       => "varchar(26) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_user']['fields']['trainerqualifikation'] = array(
	'search'    => true,
	'exclude'   => true,
	'sorting'   => true,
	'filter'    => true,
	'flag'      => 1,
	'inputType' => 'checkbox',
	'options'   => System::getContainer()->getParameter('rsz-leiterqualifikation'),
	'eval'      => array('multiple' => true, 'tl_class' => ''),
	'sql'       => "blob NULL"
);

$GLOBALS['TL_DCA']['tl_user']['fields']['nationalmannschaft'] = array(
	'search'    => true,
	'exclude'   => true,
	'sorting'   => true,
	'filter'    => true,
	'flag'      => 1,
	'inputType' => 'select',
	'options'   => array('0' => 'false', '1' => 'true'),
	'eval'      => array('tl_class' => ''),
	'sql'       => "int(1) NOT NULL default '0'"
);

$GLOBALS['TL_DCA']['tl_user']['fields']['dateOfBirth'] = array(
	'exclude'   => true,
	'search'    => true,
	'sorting'   => true,
	'inputType' => 'text',
	'eval'      => array('maxlength' => 10, 'datepicker' => $this->getDatePickerString(), 'submitOnChange' => false, 'rgxp' => 'date', 'tl_class' => ' wizard'),
	'sql'       => "int(14) NOT NULL default '0'"
);

$GLOBALS['TL_DCA']['tl_user']['fields']['sac_sektion'] = array(
	'exclude'   => true,
	'search'    => true,
	'sorting'   => true,
	'filter'    => true,
	'flag'      => 1,
	'inputType' => 'select',
	'options'   => System::getContainer()->getParameter('rsz-sac-sektionen'),
	'eval'      => array('includeBlankOption' => true, 'tl_class' => ''),
	'sql'       => "blob NULL"
);

$GLOBALS['TL_DCA']['tl_user']['fields']['fe_sorting'] = array(
	'search'    => true,
	'exclude'   => true,
	'sorting'   => true,
	'filter'    => true,
	'inputType' => 'text',
	'eval'      => array('rgxp' => 'digit'),
	'sql'       => "int(14) NOT NULL default '999'"
);

$GLOBALS['TL_DCA']['tl_user']['fields']['avatar'] = array(
	'exclude'   => true,
	'inputType' => 'fileTree',
	'eval'      => array('filesOnly' => true, 'fieldType' => 'radio', 'extensions' => 'jpg,jpeg,png', 'mandatory' => false, 'tl_class' => 'clr'),
	'sql'       => "binary(16) NULL"
);

$GLOBALS['TL_DCA']['tl_user']['fields']['assignedMember'] = array(
	'sql' => "int(10) NOT NULL default '0'"
);

$GLOBALS['TL_DCA']['tl_user']['fields']['getPasswordField'] = array(
	'sql' => "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_user']['fields']['username']['eval']['tl_class'] = 'clr';

$GLOBALS['TL_DCA']['tl_user']['fields']['name']['eval']['rgxp'] = 'name';
$GLOBALS['TL_DCA']['tl_user']['fields']['name']['eval']['tl_class'] = 'clr';

$GLOBALS['TL_DCA']['tl_user']['fields']['email']['eval']['tl_class'] = 'clr';
