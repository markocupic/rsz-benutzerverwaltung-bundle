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
 * @copyright  Leo Feyer 2005-2010
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Language
 * @license    LGPL
 * @filesource
 */
$GLOBALS['TL_LANG']['tl_user']['username']              = array('Benutzername für Backend Login', 'Vorname und Nachname in Kleinbuchstaben ohne Leerzeichen: z.B. hansmuster');
$GLOBALS['TL_LANG']['tl_user']['isRSZ']                 = array('Teil des RSZ', 'Gehört der Benutzer zum RSZ?');
$GLOBALS['TL_LANG']['tl_user']['gender']                = array('Geschlecht', 'Bitte wählen Sie das Geschlecht.');
$GLOBALS['TL_LANG']['tl_user']['name']     		= array('Name und Vorname', 'Bitte geben Sie den Namen und Vornamen ein.');
$GLOBALS['TL_LANG']['tl_user']['postal']                = array('PLZ', 'Bitte geben Sie die Postleitzahl ein.');
$GLOBALS['TL_LANG']['tl_user']['city']     		= array('Ort', 'Bitte geben Sie die Stadt oder den Wohnort ein.');
$GLOBALS['TL_LANG']['tl_user']['street']     	       = array('Strasse', 'Bitte geben Sie die Strasse ein.');
$GLOBALS['TL_LANG']['tl_user']['funktion']     	       = array('Funktion', 'Bitte geben Sie die Funktion ein.');
$GLOBALS['TL_LANG']['tl_user']['niveau']     	       = array('Leistungsniveau', 'Gilt nur für Athleten.');
$GLOBALS['TL_LANG']['tl_user']['trainingsgruppe']     	= array('Trainingsgruppe', 'Gilt nur für Athleten.');
$GLOBALS['TL_LANG']['tl_user']['kategorie']             = array('Wettkampf-Kategorie', 'Bitte geben Sie die Kategorie ein.');
$GLOBALS['TL_LANG']['tl_user']['telephone']             = array('Festnetz-Telefonnummer', 'Festnetznummer im Format 041 000 00 00');
$GLOBALS['TL_LANG']['tl_user']['mobile']     	       = array('Natelnummer', 'Natelnummer im Format 079 000 00 00');
$GLOBALS['TL_LANG']['tl_user']['email']                 = array('E-Mail-Adresse', 'Bitte geben Sie eine gültige E-Mail-Adresse ein.');
$GLOBALS['TL_LANG']['tl_user']['alternate_email']       = array('E-Mail-Adresse der Eltern / 2. Email-Adresse', 'Bitte geben Sie eine gültige E-Mail-Adresse ein.');
$GLOBALS['TL_LANG']['tl_user']['alternate_email_2']     = array('weitere E-Mail-Adresse der Eltern (z.B. bei getrennt lebenden Erziehungsberechtigten Paaren)', 'Bitte geben Sie eine gültige E-Mail-Adresse ein.');
$GLOBALS['TL_LANG']['tl_user']['nationalmannschaft']    = array('Mitglied Nationalmannschaft', '');
$GLOBALS['TL_LANG']['tl_user']['funktionsbeschreibung'] = array('Genauere Angaben zur Funktion', 'Weitere Aufgaben im Verein, usw.');
$GLOBALS['TL_LANG']['tl_user']['trainerqualifikation']  = array('J&S-Leiterstatus', 'Falls du über eine gültige J&S-Qualifikation, verfügst.');
$GLOBALS['TL_LANG']['tl_user']['dateOfBirth']           = array('Geburtsdatum', 'Geburtsdatum: YYYY-MM-DD');
$GLOBALS['TL_LANG']['tl_user']['avatar']                = array('Portraitbild', 'Erstelle auf http://www.avataro.de/ kostenlos deinen Avatar und lade ihn über die Dateiverwaltung in dein Benutzerverzeichnis.');
$GLOBALS['TL_LANG']['tl_user']['sac_sektion']           = array('Mitglied in SAC-Sektion', 'Geben Sie eine SAC-Sektion an.');
$GLOBALS['TL_LANG']['tl_user']['url']                   = array('Webseite', 'Geben Sie eine gültige web-Adresse an: http://4ae-racing-team.ch');
$GLOBALS['TL_LANG']['tl_user']['admin']                 = array('Administrator', 'Aus dem Benutzer einen Administrator machen');
$GLOBALS['TL_LANG']['tl_user']['disable']               = array('Backend-Benutzer deaktivieren', '');
$GLOBALS['TL_LANG']['tl_user']['getPasswordField']      = array('Passwort ändern', '');
$GLOBALS['TL_LANG']['tl_user']['link_digitalrock']      = array('Link zu Digitalrock', 'Link zu den Wettkampfresultaten eines Athleten. z.B. http://www.digitalrock.de/pstambl.php?person=266&cat=1');
$GLOBALS['TL_LANG']['tl_user']['fe_sorting']            = array('Sortierrang in der Mitgliederliste im Frontend', 'Geben  Sie eine Zahl ein. Je kleiner die Zahl, umso weiter oben die Anzeige im Frontend.');
$GLOBALS['TL_LANG']['tl_user']['ahv_nr']                = array('AHV-Nr. (13-stellig)','z.B. 756.1234.5678.95');
$GLOBALS['TL_LANG']['tl_user']['iban']                  = array('Bankverbindung, IBAN-Nr.', 'im Format CH31 8123 9000 0012 4568 9');


if ($_GET["do"] == "mcupic_be_benutzerverwaltung");
elseif	($_GET["do"] == "group");
elseif	($_GET["do"] == "login");
else return;

/**
 * Fields
 */
/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_user']['name_legend']       = 'Name, Adresse und Geburtsdatum';
$GLOBALS['TL_LANG']['tl_user']['contact_legend']    = 'Kontaktangaben';
$GLOBALS['TL_LANG']['tl_user']['backend_legend']    = 'Backend-Einstellungen';
$GLOBALS['TL_LANG']['tl_user']['password_legend']   = 'Passwort-Einstellungen';
$GLOBALS['TL_LANG']['tl_user']['admin_legend']      = 'Administrator';
$GLOBALS['TL_LANG']['tl_user']['information_legend']= 'Weitere Angaben zur Person';
$GLOBALS['TL_LANG']['tl_user']['trainer']       = 'Trainerangaben';
$GLOBALS['TL_LANG']['tl_user']['athlete']       = 'Athletenangaben';
$GLOBALS['TL_LANG']['tl_user']['extended_data']       = 'Erweiterte Angaben';

/**
 * Buttons und links
 */
$GLOBALS['TL_LANG']['tl_user']['download_addresses']   =  array('RSZ Adressen downloaden', 'Adressen-Download im XLS-Format');
