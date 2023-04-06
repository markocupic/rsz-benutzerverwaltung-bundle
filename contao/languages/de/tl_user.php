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

/*
 * Legends
 */
$GLOBALS['TL_LANG']['tl_user']['contact_legend'] = 'Kontaktangaben';
$GLOBALS['TL_LANG']['tl_user']['parent_legend'] = 'Kontaktangaben Eltern/Erziehungsberechtigte';
$GLOBALS['TL_LANG']['tl_user']['admin_legend'] = 'Administrator';
$GLOBALS['TL_LANG']['tl_user']['information_legend'] = 'Weitere Angaben zur Person';
$GLOBALS['TL_LANG']['tl_user']['trainer_legend'] = 'Trainerangaben';
$GLOBALS['TL_LANG']['tl_user']['athlete_legend'] = 'Athletenangaben';
$GLOBALS['TL_LANG']['tl_user']['extended_data'] = 'Erweiterte Angaben';
$GLOBALS['TL_LANG']['tl_user']['rsz_permission_legend'] = 'RSZ Benutzer-Rechte';

/*
 * Global operations
 */
$GLOBALS['TL_LANG']['tl_user']['addressDownload'] = 'Excel Export';

/*
 * Fields
 */
$GLOBALS['TL_LANG']['tl_user']['rsz_address_downloadp'] = ['RSZ Adressen-Rechte', 'Hier können Sie einstellen wer Zugriff auf die Adressen hat.'];
$GLOBALS['TL_LANG']['tl_user']['rsz_usersp'] = ['Benutzerrechte für Benutzermodul', 'Hier können Sie einstellen, wer Zugriff auf das Benutzermodul hat.'];
$GLOBALS['TL_LANG']['tl_user']['username'] = ['Benutzername für Backend Login', 'Vorname und Nachname in Kleinbuchstaben ohne Leerzeichen: z.B. hansmuster'];
$GLOBALS['TL_LANG']['tl_user']['isRSZ'] = ['Teil des RSZ', 'Gehört der Benutzer zum RSZ?'];
$GLOBALS['TL_LANG']['tl_user']['gender'] = ['Geschlecht', 'Bitte wählen Sie das Geschlecht.'];
$GLOBALS['TL_LANG']['tl_user']['name'] = ['Name und Vorname', 'Bitte geben Sie den Namen und Vornamen ein.'];
$GLOBALS['TL_LANG']['tl_user']['postal'] = ['PLZ', 'Bitte geben Sie die Postleitzahl ein.'];
$GLOBALS['TL_LANG']['tl_user']['city'] = ['Ort', 'Bitte geben Sie die Stadt oder den Wohnort ein.'];
$GLOBALS['TL_LANG']['tl_user']['street'] = ['Strasse', 'Bitte geben Sie die Strasse ein.'];
$GLOBALS['TL_LANG']['tl_user']['funktion'] = ['Funktion', 'Bitte geben Sie die Funktion ein.'];
$GLOBALS['TL_LANG']['tl_user']['niveau'] = ['Leistungsniveau', 'Gilt nur für Athleten.'];
$GLOBALS['TL_LANG']['tl_user']['trainingsgruppe'] = ['Trainingsgruppe', 'Gilt nur für Athleten.'];
$GLOBALS['TL_LANG']['tl_user']['kategorie'] = ['Wettkampf-Kategorie', 'Die Kategorie wird stündlich automatisch aktualisiert.'];
$GLOBALS['TL_LANG']['tl_user']['telephone'] = ['Festnetz-Telefonnummer', 'Festnetznummer im Format 041 000 00 00'];
$GLOBALS['TL_LANG']['tl_user']['mobile'] = ['Natelnummer', 'Natelnummer im Format 079 000 00 00'];
$GLOBALS['TL_LANG']['tl_user']['email'] = ['E-Mail-Adresse', 'Bitte geben Sie eine gültige E-Mail-Adresse ein.'];
$GLOBALS['TL_LANG']['tl_user']['mother_firstname'] = ['Vorname Mutter', 'Bitte geben Sie den Vornamen der Mutter ein.'];
$GLOBALS['TL_LANG']['tl_user']['father_firstname'] = ['Vorname Vater', 'Bitte geben Sie den Vornamen des Vaters ein.'];
$GLOBALS['TL_LANG']['tl_user']['mother_lastname'] = ['Nachname Mutter', 'Bitte geben Sie den Nachnamen der Mutter ein.'];
$GLOBALS['TL_LANG']['tl_user']['father_lastname'] = ['Nachname Vater', 'Bitte geben Sie den Nachnamen des Vaters ein.'];
$GLOBALS['TL_LANG']['tl_user']['mother_email'] = ['E-Mail-Adresse Mutter', 'Bitte geben Sie die E-Mail-Adresse der Mutter ein.'];
$GLOBALS['TL_LANG']['tl_user']['father_email'] = ['E-Mail-Adresse Vater', 'Bitte geben Sie die E-Mail-Adresse des Vaters ein.'];
$GLOBALS['TL_LANG']['tl_user']['mother_mobile'] = ['Mobiltelefon Mutter', 'Bitte geben Sie die Mobile-Nummer der Mutter ein.'];
$GLOBALS['TL_LANG']['tl_user']['father_mobile'] = ['Mobiltelefon Vater', 'Bitte geben Sie die Mobile-Nummer des Vaters ein.'];
$GLOBALS['TL_LANG']['tl_user']['nationalmannschaft'] = ['Mitglied Nationalmannschaft', ''];
$GLOBALS['TL_LANG']['tl_user']['funktionsbeschreibung'] = ['Genauere Angaben zur Funktion', 'Weitere Aufgaben im Verein, usw.'];
$GLOBALS['TL_LANG']['tl_user']['trainerqualifikation'] = ['J&S-Leiterstatus', 'Falls du über eine gültige J&S-Qualifikation, verfügst.'];
$GLOBALS['TL_LANG']['tl_user']['trainerFromGroup'] = ['Trainer der Gruppe', 'Geben Sie an, ob der Leiter in einer oder mehreren Gruppen eine Trainingsfunktion hat.'];
$GLOBALS['TL_LANG']['tl_user']['dateOfBirth'] = ['Geburtsdatum', 'Geburtsdatum: YYYY-MM-DD'];
$GLOBALS['TL_LANG']['tl_user']['avatar'] = ['Portraitbild', 'Erstelle auf http://www.avataro.de/ kostenlos deinen Avatar und lade ihn über die Dateiverwaltung in dein Benutzerverzeichnis.'];
$GLOBALS['TL_LANG']['tl_user']['sac_sektion'] = ['Mitglied in SAC-Sektion', 'Geben Sie eine SAC-Sektion an.'];
$GLOBALS['TL_LANG']['tl_user']['url'] = ['Webseite', 'Geben Sie eine gültige web-Adresse an: http://4ae-racing-team.ch'];
$GLOBALS['TL_LANG']['tl_user']['admin'] = ['Administrator', 'Aus dem Benutzer einen Administrator machen'];
$GLOBALS['TL_LANG']['tl_user']['disable'] = ['Backend-Benutzer deaktivieren', ''];
$GLOBALS['TL_LANG']['tl_user']['getPasswordField'] = ['Passwort ändern', ''];
$GLOBALS['TL_LANG']['tl_user']['link_digitalrock'] = ['Link zu Digitalrock', 'Link zu den Wettkampfresultaten eines Athleten. z.B. http://www.digitalrock.de/pstambl.php?person=266&cat=1'];
$GLOBALS['TL_LANG']['tl_user']['fe_sorting'] = ['Sortierrang in der Mitgliederliste im Frontend', 'Geben  Sie eine Zahl ein. Je kleiner die Zahl, umso weiter oben die Anzeige im Frontend.'];
$GLOBALS['TL_LANG']['tl_user']['ahv_nr'] = ['AHV-Nr. (13-stellig)', 'z.B. 756.1234.5678.95'];
$GLOBALS['TL_LANG']['tl_user']['iban'] = ['Bankverbindung, IBAN-Nr.', 'im Format CH31 8123 9000 0012 4568 9'];
$GLOBALS['TL_LANG']['tl_user']['js_nr'] = ['J&S-Nr.', 'Gib hier deine J&S Nummer ein.'];
