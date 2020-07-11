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
 * @copyright  2010 by e@sy Solutions IT <http://www.easySolutionsIT.de/>
 * @author     Patrick Froch <patrick.froch@easySolutionsIT.de/>
 * @package    esBackendMail
 * @license    LGPL
 * @filesource
 */


/**
 * -------------------------------------------------------------------------
 * BACK END MODULES
 * -------------------------------------------------------------------------
 */
$GLOBALS['BE_MOD']['accounts']['mcupic_be_benutzerverwaltung'] = array(
    'icon' => 'bundles/markocupicrszbenutzerverwaltung/user_thief.png',
    'tables' => array('tl_user')
);


if (TL_MODE === 'BE')
{
    $GLOBALS['TL_CSS'][]  = 'system/modules/mcupic_be_benutzerverwaltung/html/mcupic_be_benutzerverwaltung.css';

       // rgxp Hook
       $GLOBALS['TL_HOOKS']['addCustomRegexp'][] = array('RSZUser', 'myAddCustomRegexp');

}


//Adressen-Download Modul
$GLOBALS['BE_MOD']['accounts']['my_be_rsz_adressen_download'] = array(
    'icon' => 'bundles/markocupicrszbenutzerverwaltung/doc_excel_table.png',
    'callback' => 'RSZAdressenDownload'
);


// config.php
$GLOBALS['TL_HOOKS']['setNewPassword'][] = array('RSZUser', 'setNewPassword');

