<?php

/**
 * @copyright  Marko Cupic 2020 <m.cupic@gmx.ch>
 * @author     Marko Cupic
 * @package    RSZ Benutzerverwaltung
 * @license    MIT
 * @see        https://github.com/markocupic/rsz-benutzerverwaltung-bundle
 *
 */

declare(strict_types=1);

namespace Markocupic\RszBenutzerverwaltungBundle\Excel;

use Contao\BackendModule;
use Contao\Database;
use Contao\Date;
use Contao\StringUtil;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * Class RszAdressenDownload
 * @package Markocupic\RszBenutzerverwaltungBundle\Excel
 */
class RszAdressenDownload extends BackendModule
{

    /**
     * RszAdressenDownload constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function compile()
    {
        $this->import('BackendUser', 'User');
        $this->downloadAddressesAsXlsx();
    }

    /**
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function downloadAddressesAsXlsx()
    {
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getActiveSheet()->setTitle("RSZ Adressen");
        $spreadsheet->setActiveSheetIndex(0);

        // Get active sheet
        $sheet = $spreadsheet->getActiveSheet();

        $arr_fields = [
            "gender", "name", "street", "postal", "city", "dateOfBirth", "telephone", "mobile", "fax",
            "username", "email", "alternate_email", "url", "sac_sektion", "funktion", "niveau", "trainingsgruppe", "nationalmannschaft",
            "trainerqualifikation", "trainerFromGroup"
        ];

        if ($this->User->isAdmin)
        {
            $arr_fields[] = 'ahv';
        }

        // Get header
        $col = 1;
        $row = 1;
        foreach ($arr_fields as $field)
        {
            $sheet->setCellValueByColumnAndRow($col, $row, $field);
            $col++;
        }

        $objUser = Database::getInstance()->prepare("SELECT * FROM tl_user WHERE isRSZ=? ORDER BY funktion, dateOfBirth, name")->execute('1');

        // Get rows
        while ($objUser->next())
        {
            $col = 1;
            $row++;
            foreach ($arr_fields as $field)
            {
                $value = $objUser->{$field};
                if ($field == "dateOfBirth")
                {
                    $value = Date::parse("Y-m-d", $value);
                    $sheet->setCellValueByColumnAndRow($col, $row, $value);
                }
                elseif (!empty($objUser->{$field}) && is_array(unserialize($objUser->{$field})))
                {
                    $arrValues = unserialize($objUser->{$field});
                    $arrValues = array_filter($arrValues, 'strlen');
                    $value = implode(', ', $arrValues);
                    $sheet->setCellValueByColumnAndRow($col, $row, $value);
                }
                else
                {
                    $value = $objUser->{$field};
                    $sheet->setCellValueByColumnAndRow($col, $row, $value);
                }
                $col++;
            }
        }

        // Send file to browser
        $objWriter = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"adressen_rsz_" . Date::parse("Y-m-d") . ".xlsx\"");
        header("Cache-Control: max-age=0");
        $objWriter->save("php://output");
        exit;
    }

}
