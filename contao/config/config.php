<?php

/*
 * This file is part of RSZ Benutzerverwaltung Bundle.
 *
 * (c) Marko Cupic 2023 <m.cupic@gmx.ch>
 * @license MIT
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 * @link https://github.com/markocupic/rsz-benutzerverwaltung-bundle
 */

use Markocupic\RszBenutzerverwaltungBundle\Excel\RszAdressenDownload;

/**
 * Backend modules
 */
$GLOBALS['BE_MOD']['rsz_tools']['rsz_adressen_download'] = array(
	'callback' => RszAdressenDownload::class
);
