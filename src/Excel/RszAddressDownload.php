<?php

declare(strict_types=1);

/*
 * This file is part of RSZ Benutzerverwaltung Bundle.
 *
 * (c) Marko Cupic 2023 <m.cupic@gmx.ch>
 * @license MIT
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 * @link https://github.com/markocupic/rsz-benutzerverwaltung-bundle
 */

namespace Markocupic\RszBenutzerverwaltungBundle\Excel;

use Contao\BackendUser;
use Contao\Config;
use Contao\Database;
use Contao\Date;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Security\Core\Security;

class RszAddressDownload
{
    private BackendUser|null $user = null;
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;

        $user = $this->security->getUser();

        if ($user instanceof BackendUser) {
            $this->user = $user;
        }
    }

    /**
     * @throws Exception
     * @throws \Exception
     */
    public function download(array $arrIds = [], string $strOrderBy = ''): Response
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
            $sheet->setCellValue([$col, $row], $field);
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
                } elseif (!empty($objUser->{$field}) && \is_array(unserialize($objUser->{$field}))) {
                    $arrValues = unserialize($objUser->{$field});
                    $arrValues = array_filter($arrValues, 'strlen');
                    $value = implode(', ', $arrValues);
                }

                $sheet->setCellValue([$col, $row], $value);
                ++$col;
            }
        }

        // Send file to browser
        $writer = new Xlsx($spreadsheet);

        $response = new StreamedResponse(
            static function () use ($writer): void {
                $writer->save('php://output');
            }
        );

        $response->headers->set('Content-Type', 'application/vnd.ms-excel');
        $response->headers->set('Content-Disposition', 'attachment;filename="adressen_rsz_'.Date::parse('Y-m-d').'.xlsx"');
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response->send();
    }
}
