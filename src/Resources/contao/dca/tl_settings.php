<?php
/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2010 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  Marko Cupic 2010
 * @author     Marko Cupic, Oberkirch, Switzerland ->  mailto: m.cupic@gmx.ch
 * @package    mcupic_be_benutzerverwaltung
 * @license    GNU/LGPL
 * @filesource
 */



/**
 * Add to palette
 */

$GLOBALS['TL_DCA']['tl_settings']['palettes']['default'] .= ';{rsz_benutzerverwaltung:hide},mcupic_be_benutzerverwaltung_sac_sektion,mcupic_be_benutzerverwaltung_kategorie,mcupic_be_benutzerverwaltung_funktion,mcupic_be_benutzerverwaltung_niveau,mcupic_be_benutzerverwaltung_trainingsgruppe,mcupic_be_benutzerverwaltung_trainerqualifikation,mcupic_be_benutzerverwaltung_id_group_address_admin';

/**
 * Add fields
 */

$GLOBALS['TL_DCA']['tl_settings']['fields']['mcupic_be_benutzerverwaltung_sac_sektion'] = array
(
		'label'		=>	&$GLOBALS['TL_LANG']['tl_settings']['mcupic_be_benutzerverwaltung_sac_sektion'],
		'inputType'	=>	'text',
		'eval'		=>	array('tl_class'=>'long clr')
);
$GLOBALS['TL_DCA']['tl_settings']['fields']['mcupic_be_benutzerverwaltung_kategorie'] = array
(
		'label'		=>	&$GLOBALS['TL_LANG']['tl_settings']['mcupic_be_benutzerverwaltung_kategorie'],
		'inputType'	=>	'text',
		'eval'		=>	array('tl_class'=>'long clr')
);
$GLOBALS['TL_DCA']['tl_settings']['fields']['mcupic_be_benutzerverwaltung_funktion'] = array
(
		'label'		=>	&$GLOBALS['TL_LANG']['tl_settings']['mcupic_be_benutzerverwaltung_funktion'],
		'inputType'	=>	'text',
		'eval'		=>	array('tl_class'=>'long clr')
);
$GLOBALS['TL_DCA']['tl_settings']['fields']['mcupic_be_benutzerverwaltung_niveau'] = array
(
		'label'		=>	&$GLOBALS['TL_LANG']['tl_settings']['mcupic_be_benutzerverwaltung_niveau'],
		'inputType'	=>	'text',
		'eval'		=>	array('tl_class'=>'long clr')
);
$GLOBALS['TL_DCA']['tl_settings']['fields']['mcupic_be_benutzerverwaltung_trainingsgruppe'] = array
(
		'label'		=>	&$GLOBALS['TL_LANG']['tl_settings']['mcupic_be_benutzerverwaltung_trainingsgruppe'],
		'inputType'	=>	'text',
		'eval'		=>	array('tl_class'=>'long clr')
);
$GLOBALS['TL_DCA']['tl_settings']['fields']['mcupic_be_benutzerverwaltung_trainerqualifikation'] = array
(
		'label'		=>	&$GLOBALS['TL_LANG']['tl_settings']['mcupic_be_benutzerverwaltung_trainerqualifikation'],
		'inputType'	=>	'text',
		'eval'		=>	array('tl_class'=>'long clr')
);
$GLOBALS['TL_DCA']['tl_settings']['fields']['mcupic_be_benutzerverwaltung_id_group_address_admin'] = array
(
		'label'		=>	&$GLOBALS['TL_LANG']['tl_settings']['mcupic_be_benutzerverwaltung_id_group_address_admin'],
		'inputType'	=>	'text',
		'eval'		=>	array('tl_class'=>'long clr')
);
