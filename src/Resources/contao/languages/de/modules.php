<?php

/**
 * @copyright  Marko Cupic 2020 <m.cupic@gmx.ch>
 * @author     Marko Cupic
 * @package    RSZ Benutzerverwaltung
 * @license    MIT
 * @see        https://github.com/markocupic/rsz-benutzerverwaltung-bundle
 *
 */

$GLOBALS['TL_LANG']['MOD']['rsz_benutzerverwaltung'] = ['RSZ Benutzerverwaltung', 'Benutzerangaben verwalten'];

$objUser = \BackendUser::getInstance();

if (!$objUser->isAdmin && !$objUser->isMemberOf($GLOBALS['TL_CONFIG']['mcupic_be_benutzerverwaltung_id_group_address_admin']))
{
    $GLOBALS['TL_LANG']['MOD']['rsz_benutzerverwaltung'] = ['Mein Konto', 'Meine Benutzerangaben verwalten'];
}

$GLOBALS['TL_LANG']['MOD']['rsz_adressen_download'] = ['RSZ Adressen', 'RSZ Adressen als Excel Textfile'];
