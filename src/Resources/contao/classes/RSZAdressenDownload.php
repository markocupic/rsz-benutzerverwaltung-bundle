<?php
/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2010 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  Marko Cupic 2010
 * @author     Marko Cupic
 * @package    my_ve_rsz_adressen_download
 * @license    GNU/LGPL
 * @filesource
 */




class RSZAdressenDownload extends BackendModule
{

       public function __construct()
       {

              parent::__construct();
       }

       public function compile()
       {

              $this->import('BackendUser', 'User');
              $this->downloadAddressesXls();
       }

       /**
        * @throws PHPExcel_Exception
        * @throws PHPExcel_Reader_Exception
        */
       public function downloadAddressesXls()
       {

              $phpExcel = new PHPExcel();
              $phpExcel->getActiveSheet()->setTitle("My Sheet");
              $phpExcel->setActiveSheetIndex(0);

              $arr_fields = array(
                  "gender", "vorname", "name", "dateOfBirth", "street", "postal", "city", "telephone", "mobile",
                  "fax", "email", "alternate_email", "url", "sac_sektion", "funktion", "niveau", "trainingsgruppe",
                  "trainerqualifikation"
              );

              if ($this->User->isAdmin)
              {
                     $arr_fields[] = 'username';
              }

              //Die Spaltenbezeichnungen generieren
              $col = 0;
              $row = 1;
              foreach ($arr_fields as $field)
              {
                     $phpExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $field);
                     $col++;
              }

              $objUser = $this->Database->execute("SELECT * FROM tl_user ORDER BY funktion, dateOfBirth, name");
              //Die Zeilen mit den Benutzern
              while ($objUser->next())
              {
                     $col = 0;
                     $row++;
                     foreach ($arr_fields as $field)
                     {
                            $value = $objUser->{$field};
                            if ($field == "dateOfBirth")
                            {
                                   $value = date("Y-m-d", $value);
                                   $phpExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $value);
                            }
                            elseif ($field == "funktion")
                            {
                                   $value = strlen($value) ? implode(', ', unserialize($value)) : '';
                                   $phpExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $value);
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
                                   $phpExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $first_name);
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
                                   $phpExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $last_name);
                            }
                            elseif ($field == "trainerqualifikation")
                            {
                                   if (!is_array(unserialize($value)))
                                   {
                                          $value = "";
                                   }
                                   else
                                   {
                                          $value = unserialize($value);
                                          $string = "";
                                          foreach ($value as $key => $content)
                                          {
                                                 $string .= $content . ", ";
                                          }
                                          $value = $string;
                                   }
                                   $phpExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $value);
                            }
                            else
                            {
                                   $value = $objUser->{$field};
                                   $phpExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $value);
                            }
                            $col++;
                     }
              }

              $objWriter = PHPExcel_IOFactory::createWriter($phpExcel, "Excel2007");
              header("Content-Type: application/vnd.ms-excel");
              header("Content-Disposition: attachment; filename=\"adressen_rsz_" . \Date::parse("Y-m-d") . ".xlsx\"");
              header("Cache-Control: max-age=0");
              $objWriter->save("php://output");
              exit;

       }

       /**
        * CSV Output
        */
       public function downloadAddressesCSV()
       {
              $worksheet = "";
              $arr_fields = array(
                     "gender", "vorname", "name", "dateOfBirth", "street", "postal", "city", "telephone", "mobile",
                     "fax", "email", "alternate_email", "url", "sac_sektion", "funktion", "niveau", "trainingsgruppe",
                     "trainerqualifikation"
              );
              if ($this->User->isAdmin)
              {
                     $arr_fields[] = 'username';
              }
              if ($this->User->isAdmin)
              {
                     $arr_fields[] = 'username';
              }
              //Die Spaltenbezeichnungen generieren
              foreach ($arr_fields as $field)
              {
                     $worksheet .= $field . ";";
              }
              $worksheet .= "\r\n";


              $objUser = $this->Database->execute("SELECT * FROM tl_user ORDER BY funktion, dateOfBirth, name");
              //Die Zeilen mit den Benutzern
              while ($objUser->next())
              {
                     foreach ($arr_fields as $field)
                     {
                            $value = $objUser->{$field};
                            if ($field == "dateOfBirth")
                            {
                                   $value = date("Y-m-d", $value);
                                   $worksheet .= str_replace(";", ",", $value) . ";";
                            }
                            elseif ($field == "funktion")
                            {
                                   $value = strlen($value) ? implode(', ', unserialize($value)) : '';
                                   $worksheet .= str_replace(";", ",", $value) . ";";
                            }
                            elseif ($field == "name")
                            {
                                   $arr_name = explode(" ", $objUser->name);
                                   if ($arr_name[2])
                                   {
                                          $first_name = str_replace(";", ",", $arr_name[2]);
                                   }
                                   else
                                   {
                                          $first_name = str_replace(";", ",", $arr_name[1]);
                                   }
                                   $worksheet .= $first_name . ";";
                                   if ($arr_name[2])
                                   {
                                          $last_name = str_replace(";", ",", $arr_name[0] . " " . $arr_name[1]);
                                   }
                                   else
                                   {
                                          $last_name = str_replace(";", ",", $arr_name[0]);
                                   }
                                   $worksheet .= $last_name . ";";

                            }
                            elseif ($field == "vorname")
                            {
                                   //
                            }
                            elseif ($field == "trainerqualifikation")
                            {
                                   if (!is_array(unserialize($value)))
                                   {
                                          $value = "";
                                   }
                                   else
                                   {
                                          $value = unserialize($value);
                                          $string = "";
                                          foreach ($value as $key => $content)
                                          {
                                                 $string .= $content . ", ";
                                          }
                                          $value = $string;
                                   }
                                   $worksheet .= str_replace(";", ",", $value) . ";";
                            }
                            else
                            {
                                   $value = $objUser->{$field};
                                   $worksheet .= str_replace(";", ",", $value) . ";";
                            }
                     }
                     $worksheet .= "\r\n";
              }

              header('Content-Type: text/csv;');
              header('Content-type: application/octet-stream');
              header('Content-Disposition: attachment; filename=Adressen_RSZ.csv');
              header('Content-Description: csv File');
              header('Pragma: no-cache');
              header('Expires: 0');

              print utf8_decode($worksheet);
              die();
       }
}
