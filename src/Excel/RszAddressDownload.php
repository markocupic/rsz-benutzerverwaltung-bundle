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

use Contao\Config;
use Contao\Controller;
use Contao\CoreBundle\Framework\Adapter;
use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\CoreBundle\Security\ContaoCorePermissions;
use Contao\Database;
use Contao\Date;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\Translation\TranslatorInterface;

class RszAddressDownload
{
    private Adapter $config;
    private Adapter $controller;
    private Adapter $database;
    private Adapter $date;

    public function __construct(
        private readonly Security $security,
        private readonly ContaoFramework $framework,
        private readonly TranslatorInterface $translator,
    ) {
        $this->config = $this->framework->getAdapter(Config::class);
        $this->controller = $this->framework->getAdapter(Controller::class);
        $this->database = $this->framework->getAdapter(Database::class);
        $this->date = $this->framework->getAdapter(Date::class);
    }

    /**
     * @throws Exception
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
            'url',
            'mother_firstname',
            'mother_lastname',
            'mother_email',
            'mother_mobile',
            'father_firstname',
            'father_lastname',
            'father_email',
            'father_mobile',
            'sac_sektion',
            'funktion',
            'niveau',
            'js_nr',
            'trainingsgruppe',
            'nationalmannschaft',
            'trainerqualifikation',
            'trainerFromGroup',
            'ahv_nr',
        ];

        if (!$this->security->isGranted('ROLE_ADMIN') && !$this->security->isGranted(ContaoCorePermissions::USER_CAN_EDIT_FIELD_OF_TABLE, 'tl_user::ahv_nr')) {
            unset($arr_fields['ahv_nr']);
        }

        // Get header
        $col = 1;
        $row = 1;

        foreach ($arr_fields as $field) {
            if (!$this->database->getInstance()->fieldExists($field, 'tl_user')) {
                throw new \Exception(sprintf('Column "%s" not found in %s.', $field, 'tl_user'));
            }
            $this->controller->loadLanguageFile('tl_user');

            $columnName = $this->translator->trans('tl_user.'.$field.'.0', [], 'contao_default');
            $sheet->setCellValue([$col, $row], $columnName);
            ++$col;
        }

        if (empty($arrIds)) {
            $objUser = $this->database->getInstance()
                ->prepare('SELECT * FROM tl_user WHERE isRSZ = ? ORDER BY '.$strOrderBy)
                ->execute('1')
            ;
        } else {
            $objUser = $this->database->getInstance()
                ->prepare('SELECT * FROM tl_user WHERE id IN ('.implode(',', array_map('intval', $arrIds)).') ORDER BY '.$strOrderBy)
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
                    $value = $this->date->parse($this->config->get('dateFormat'), $value);
                } elseif (!empty($objUser->{$field}) && \is_array(unserialize($objUser->{$field}))) {
                    $arrValues = unserialize($objUser->{$field});
                    $arrValues = array_filter($arrValues, 'strlen');
                    $value = implode(', ', $arrValues);
                } elseif ('ahv_nr' === $field && \strlen((string) $value) < 5) {
                    $value = ''; // Remove default value: 756.
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
        $response->headers->set('Content-Disposition', 'attachment;filename="adressen_rsz_'.$this->date->parse('Y-m-d').'.xlsx"');
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response->send();
    }
}
