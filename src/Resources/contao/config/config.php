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
 * Backend modules
 */
$GLOBALS['BE_MOD']['accounts']['rsz_benutzerverwaltung'] = array(
    'tables' => array('tl_user')
);


$GLOBALS['BE_MOD']['accounts']['rsz_adressen_download'] = array(
    'callback' => Markocupic\RszBenutzerverwaltungBundle\Excel\RszAdressenDownload::class
);


// CSS
if (TL_MODE === 'BE')
{
    $GLOBALS['TL_CSS'][]  = 'bundles/markocupicrszbenutzerverwaltung/be_benutzerverwaltung.css';
}





