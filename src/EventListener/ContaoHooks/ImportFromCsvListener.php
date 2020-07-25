<?php

/**
 * @copyright  Marko Cupic 2020 <m.cupic@gmx.ch>
 * @author     Marko Cupic
 * @package    RSZ Benutzerverwaltung
 * @license    MIT
 * @see        https://github.com/markocupic/rsz-benutzerverwaltung-bundle
 *
 */

namespace Markocupic\RszJahresprogrammBundle\EventListener\ContaoHooks;

use Contao\Config;
use Contao\Date;
use Contao\CoreBundle\ServiceAnnotation\Hook;
use Terminal42\ServiceAnnotationBundle\ServiceAnnotationInterface;

/**
 * Class ImportFromCsvListener
 * @package Markocupic\RszBenutzerverwaltungBundle\EventListener\ContaoHooks
 */
class ImportFromCsvListener implements ServiceAnnotationInterface
{

    /**
     * Import Hook für import_from_csv extension
     *
     * @Hook("importFromCsv")
     *
     * @param $arrCustomValidation
     * @param null $objBackendModule
     * @return mixed
     */
    public function importFromCsvHook($arrCustomValidation, $objBackendModule = null)
    {
        /**
         * $arrCustomValidation = array(
         *
         * 'strTable'               => 'tablename',
         * 'arrDCA'                 => 'Datacontainer array (DCA) of the current field.',
         * 'fieldname'              => 'fieldname',
         * 'value'                  => 'value',
         * 'arrayLine'              => 'Contains the current line/dataset as associative array.',
         * 'line'                   => 'current line in the csv-spreadsheet',
         * 'objCsvFile'             => 'the Contao file object'
         * 'skipWidgetValidation'   => 'Skip widget-input-validation? (default is set to false)',
         * 'hasErrors'              => 'Should be set to true if custom validation fails. (default is set to false)',
         * 'errorMsg'               => 'Define a custom text message if custom validation fails.',
         * 'doNotSave'              => 'Set this item to true if you don't want to save the datarecord into the database. (default is set to false)',
         * );
         */

        // tl_jahresprogramm
        if ($arrCustomValidation['strTable'] == 'tl_rsz_jahresprogramm')
        {
            // Get geolocation from a given address
            if ($arrCustomValidation['fieldname'] == 'kw')
            {
                // Kalenderwoche wird automatisch gesetzt
                $arrCustomValidation['value'] = '';
            }
            // Clean teilnehmer
            elseif ($arrCustomValidation['fieldname'] == 'teilnehmer')
            {
                if ($arrCustomValidation['value'] != '')
                {
                    $value = trim($arrCustomValidation['value']);
                    $arrValue = explode(',', $value);
                    $arrValue = array_map(function ($v) {
                        return trim($v);
                    }, $arrValue);
                    $arrValue = array_filter(array_unique($arrValue));

                    $arrCustomValidation['value'] = implode(',', $arrValue);
                }
            }
            elseif ($arrCustomValidation['fieldname'] == 'start_date' || $arrCustomValidation['fieldname'] == 'end_date' || $arrCustomValidation['fieldname'] == 'registrationStop')
            {
                if (trim($arrCustomValidation['value']) == '' && $arrCustomValidation['fieldname'] == 'registrationStop')
                {
                    $arrCustomValidation['value'] = '';
                    $arrCustomValidation['skipWidgetValidation'] = true;
                }
                else
                {
                    // Change date to timestamp
                    $arrDate = explode(".", $arrCustomValidation['value']);
                    //int mktime ([ int $hour = date("H") [, int $minute = date("i") [, int $second = date("s") [, int $month = date("n") [, int $day = date("j") [, int $year = date("Y") [, int $is_dst = -1 ]]]]]]] )
                    $tstamp = mktime(0, 0, 0, (int) $arrDate[1], (int) $arrDate[0], (int) $arrDate[2]);
                    $arrCustomValidation['value'] = Date::parse(Config::get('dateFormat'), $tstamp);
                }
            }
            elseif ($arrCustomValidation['fieldname'] == 'zeit')
            {
                //$arrCustomValidation['value'] = str_replace('"','', $arrCustomValidation['value']);
            }
            elseif ($arrCustomValidation['fieldname'] == 'autoSignInKategories')
            {
                $arrCustomValidation['skipWidgetValidation'] = true;
                if (trim($arrCustomValidation['value']) != '')
                {
                    $arrCustomValidation['value'] = serialize(explode(',', trim($arrCustomValidation['value'])));
                }
            }

            else
            {
                $arrCustomValidation['value'] = $arrCustomValidation['value'] == "" ? "" : trim(str_replace("**<BR>##", chr(10), $arrCustomValidation['value']));
                $arrCustomValidation['value'] = str_replace("<br>", chr(10), $arrCustomValidation['value']);
            }
        }

        return $arrCustomValidation;
    }

}
