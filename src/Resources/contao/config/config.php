<?php

/**
 * @copyright  Marko Cupic 2020 <m.cupic@gmx.ch>
 * @author     Marko Cupic
 * @package    RSZ Benutzerverwaltung
 * @license    MIT
 * @see        https://github.com/markocupic/rsz-benutzerverwaltung-bundle
 *
 */

use Markocupic\RszBenutzerverwaltungBundle\RszUser\RszUser;
use Markocupic\RszBenutzerverwaltungBundle\Excel\RszAdressenDownload;

/**
 * Backend modules
 */
$GLOBALS['BE_MOD']['rsz_tools']['rsz_adressen_download'] = array(
    'callback' => RszAdressenDownload::class
);


/**
 * Cronjobs
 */
$GLOBALS['TL_CRON']['hourly'][] = [RszUser::class, 'maintainUserProperties'];



