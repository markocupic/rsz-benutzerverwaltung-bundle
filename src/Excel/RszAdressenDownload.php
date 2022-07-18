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

namespace Markocupic\RszBenutzerverwaltungBundle\Excel;

use Contao\BackendModule;
use Contao\BackendUser;
use Contao\Config;
use Contao\Database;
use Contao\Date;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class RszAdressenDownload extends BackendModule
{
    private BackendUser|null $user = null;

    public function __construct(BackendUser|null $user)
    {
        if ($user instanceof BackendUser) {
            $this->user = $user;
        }
    }

    /**
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function compile(): void
    {
        // Legacy
        $this->user = BackendUser::getInstance();
        $this->downloadAddressesAsXlsx();
    }

    /**
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function downloadAddressesAsXlsx(array $arrIds = [], $strOrderBy = ''): void
    {
        if ('' === $strOrderBy) {
            $strOrderBy = 'funktion, dateOfBirth, name';
        }

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getActiveSheet()->setTitle('RSZ Adressen');
        $spreadsheet->setActiveSheetIndex(0);

        // Get active sheet
        $sheet = $spreadsheet->getActiveSheet();

        $arr_fields = [
            'isRSZ',
            'gender',
            'name',
            'street',
            'postal',
            'city',
            'dateOfBirth',
            'telephone',
            'mobile',
            'username',
            'email',
            'alternate_email',
            'url',
            'sac_sektion',
            'funktion',
            'niveau',
            'js_nr',
            'trainingsgruppe',
            'nationalmannschaft',
            'trainerqualifikation',
            'trainerFromGroup',
        ];

        if ($this->user && $this->user->admin) {
            $arr_fields[] = 'ahv_nr';
        }

        // Get header
        $col = 1;
        $row = 1;

        foreach ($arr_fields as $field) {
            if (!Database::getInstance()->fieldExists($field, 'tl_user')) {
                throw new \Exception(sprintf('Column "%s" not found in %s.', $field, 'tl_user'));
            }
            $sheet->setCellValueByColumnAndRow($col, $row, $field);
            ++$col;
        }

        if (empty($arrIds)) {
            $objUser = Database::getInstance()
                ->prepare('SELECT * FROM tl_user WHERE isRSZ = ? ORDER BY funktion, dateOfBirth, name')
                ->execute('1')
            ;
        } else {
            $objUser = Database::getInstance()
                ->prepare('SELECT * FROM tl_user WHERE id IN ('.implode(',', $arrIds).') ORDER BY '.$strOrderBy)
                ->execute('1')
            ;
        }

        // Get rows
        while ($objUser->next()) {
            $col = 1;
            ++$row;

            foreach ($arr_fields as $field) {
                $value = $objUser->{$field};

                if ('dateOfBirth' === $field) {
                    $value = Date::parse(Config::get('dateFormat'), $value);
                    $sheet->setCellValueByColumnAndRow($col, $row, $value);
                } elseif (!empty($objUser->{$field}) && \is_array(unserialize($objUser->{$field}))) {
                    $arrValues = unserialize($objUser->{$field});
                    $arrValues = array_filter($arrValues, 'strlen');
                    $value = implode(', ', $arrValues);
                    $sheet->setCellValueByColumnAndRow($col, $row, $value);
                } else {
                    $value = $objUser->{$field};
                    $sheet->setCellValueByColumnAndRow($col, $row, $value);
                }
                ++$col;
            }
        }

        // Send file to browser
        $objWriter = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="adressen_rsz_'.Date::parse('Y-m-d').'.xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter->save('php://output');
        exit;
    }
}
