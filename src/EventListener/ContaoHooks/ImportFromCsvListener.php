<?php

declare(strict_types=1);

/*
 * This file is part of RSZ Benutzerverwaltung Bundle.
 *
 * (c) Marko Cupic 2021 <m.cupic@gmx.ch>
 * @license MIT
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 * @link https://github.com/markocupic/rsz-benutzerverwaltung-bundle
 */

namespace Markocupic\RszJahresprogrammBundle\EventListener\ContaoHooks;

use Contao\Config;
use Contao\CoreBundle\ServiceAnnotation\Hook;
use Contao\Date;
use Terminal42\ServiceAnnotationBundle\ServiceAnnotationInterface;

/**
 * Class ImportFromCsvListener.
 */
class ImportFromCsvListener implements ServiceAnnotationInterface
{
    /**
     * Import Hook für import_from_csv extension.
     *
     * @param $arrCustomValidation
     * @param null $objBackendModule
     *
     * @return mixed
     */
    public function importFromCsvHook($arrCustomValidation, $objBackendModule = null)
    {
        /**
         * $arrCustomValidation = array(.
         *
         * 'strTable' => 'tablename',
         * 'arrDCA' => 'Datacontainer array (DCA) of the current field.',
         * 'fieldname' => 'fieldname',
         * 'value' => 'value',
         * 'arrayLine' => 'Contains the current line/dataset as associative array.',
         * 'line' => 'current line in the csv-spreadsheet',
         * 'objCsvFile' => 'the Contao file object'
         * 'skipWidgetValidation' => 'Skip widget-input-validation? (default is set to false)',
         * 'hasErrors' => 'Should be set to true if custom validation fails. (default is set to false)',
         * 'errorMsg' => 'Define a custom text message if custom validation fails.',
         * 'doNotSave' => 'Set this item to true if you don't want to save the datarecord into the database. (default is set to false)',
         * );
         */

        // tl_jahresprogramm
        if ('tl_rsz_jahresprogramm' === $arrCustomValidation['strTable']) {
            // Get geolocation from a given address
            if ('kw' === $arrCustomValidation['fieldname']) {
                // Kalenderwoche wird automatisch gesetzt
                $arrCustomValidation['value'] = '';
            }
            // Clean teilnehmer
            elseif ('teilnehmer' === $arrCustomValidation['fieldname']) {
                if ('' !== $arrCustomValidation['value']) {
                    $value = trim($arrCustomValidation['value']);
                    $arrValue = explode(',', $value);
                    $arrValue = array_map(
                        static function ($v) {
                            return trim($v);
                        },
                        $arrValue
                    );
                    $arrValue = array_filter(array_unique($arrValue));

                    $arrCustomValidation['value'] = implode(',', $arrValue);
                }
            } elseif ('start_date' === $arrCustomValidation['fieldname'] || 'end_date' === $arrCustomValidation['fieldname'] || 'registrationStop' === $arrCustomValidation['fieldname']) {
                if ('' === trim($arrCustomValidation['value']) && 'registrationStop' === $arrCustomValidation['fieldname']) {
                    $arrCustomValidation['value'] = '';
                    $arrCustomValidation['skipWidgetValidation'] = true;
                } else {
                    // Change date to timestamp
                    $arrDate = explode('.', $arrCustomValidation['value']);
                    //int mktime ([ int $hour = date("H") [, int $minute = date("i") [, int $second = date("s") [, int $month = date("n") [, int $day = date("j") [, int $year = date("Y") [, int $is_dst = -1 ]]]]]]] )
                    $tstamp = mktime(0, 0, 0, (int) $arrDate[1], (int) $arrDate[0], (int) $arrDate[2]);
                    $arrCustomValidation['value'] = Date::parse(Config::get('dateFormat'), $tstamp);
                }
            } elseif ('zeit' === $arrCustomValidation['fieldname']) {
                //$arrCustomValidation['value'] = str_replace('"','', $arrCustomValidation['value']);
            } elseif ('autoSignInKategories' === $arrCustomValidation['fieldname']) {
                $arrCustomValidation['skipWidgetValidation'] = true;

                if ('' !== trim($arrCustomValidation['value'])) {
                    $arrCustomValidation['value'] = serialize(explode(',', trim($arrCustomValidation['value'])));
                }
            } else {
                $arrCustomValidation['value'] = '' === $arrCustomValidation['value'] ? '' : trim(str_replace('**<BR>##', \chr(10), $arrCustomValidation['value']));
                $arrCustomValidation['value'] = str_replace('<br>', \chr(10), $arrCustomValidation['value']);
            }
        }

        return $arrCustomValidation;
    }
}
