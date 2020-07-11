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
 * @author     Marko Cupic
 * @package    My_Benutzerverwaltung
 * @license    GNU/LGPL
 * @filesource
 */

/************************************************************************************
 * 		BACKEND
 ************************************************************************************/

$GLOBALS['TL_LANG']['MOD']['rsz_benutzerverwaltung'] = array('RSZ Benutzerverwaltung', 'Benutzerangaben verwalten');
$objUser = \BackendUser::getInstance();
if (!$objUser->isAdmin && !$objUser->isMemberOf($GLOBALS['TL_CONFIG']['mcupic_be_benutzerverwaltung_id_group_address_admin']) )
{
	$GLOBALS['TL_LANG']['MOD']['rsz_benutzerverwaltung'] = array('Mein Konto', 'Meine Benutzerangaben verwalten');
}
$GLOBALS['TL_LANG']['MOD']['my_be_rsz_adressen_download'] = array('RSZ Adressen', 'RSZ Adressen als Excel Textfile');