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
            "gender", "vorname", "name", "dateOfBirth", "street", "postal", "city", "telephone", "mobile",
            "fax", "email", "alternate_email", "url", "sac_sektion", "funktion", "niveau", "trainingsgruppe",
            "trainerqualifikation"
        ];

        if ($this->User->isAdmin)
        {
            $arr_fields[] = 'username';
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
                elseif ($field == "funktion")
                {
                    $value = !empty($value) ? implode(', ', StringUtil::deserialize($value)) : '';
                    $sheet->setCellValueByColumnAndRow($col, $row, $value);
                }
                elseif ($field == "vorname")
                {
                    $arr_name = explode(" ", $objUser->name);
                    if ($arr_name[2])
                    {
                        $first_name = $arr_name[2];
                    }
                    else
                    {
                        $first_name = $arr_name[1];
                    }
                    $sheet->setCellValueByColumnAndRow($col, $row, $first_name);
                }
                elseif ($field == "name")
                {
                    $arr_name = explode(" ", $objUser->name);
                    if ($arr_name[2])
                    {
                        $last_name = $arr_name[0] . " " . $arr_name[1];
                    }
                    else
                    {
                        $last_name = $arr_name[0];
                    }
                    $sheet->setCellValueByColumnAndRow($col, $row, $last_name);
                }
                elseif ($field == "trainerqualifikation")
                {
                    $value = StringUtil::deserialize($value, true);
                    if (empty($value))
                    {
                        $value = "";
                    }
                    else
                    {
                        $string = "";
                        foreach ($value as $key => $content)
                        {
                            $string .= $content . ", ";
                        }
                        $value = $string;
                    }
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
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=\"adressen_rsz_" . Date::parse("Y-m-d") . ".xlsx\"");
        header("Cache-Control: max-age=0");
        $objWriter->save("php://output");
        exit;
    }

}
