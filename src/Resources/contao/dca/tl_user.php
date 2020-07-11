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
 * @copyright  Marko Cupic 2005-2010
 * @author     Marko Cupic
 * @package    Backend
 * @license    LGPL
 * @filesource
 */


//onload_callback callback für tl_user
$GLOBALS['TL_DCA']['tl_user']['config']['onload_callback'][] = array(
    'RSZUser',
    'maintainUserProperties'
);

//ondelete callback für tl_user
$GLOBALS['TL_DCA']['tl_user']['config']['ondelete_callback'][] = array(
    'RSZUser',
    'deleteUserFromTlMember'
);
//$GLOBALS['TL_DCA']['tl_user']['config']['ondelete_callback'][] = array('tl_user', 'deleteUserDirectory');


/*
* damit das Benutzer Modul nachwievor reibungslos funktioniert...
*/

/******************fields*******************************************************************/
$GLOBALS['TL_DCA']['tl_user']['fields']['isRSZ'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_user']['isRSZ'],
    'search' => true,
    'exclude' => true,
    'sorting' => true,
    'filter' => true,
    'flag' => 1,
    'inputType' => 'select',
    'options' => array(0, 1),
    'default' => 1,
    'eval' => array('tl_class' => ''),
    'sql' => "char(1) NOT NULL default '0'"
);

$GLOBALS['TL_DCA']['tl_user']['fields']['name']['eval']['rgxp'] = 'name';

$GLOBALS['TL_DCA']['tl_user']['fields']['gender'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_user']['gender'],
    'search' => true,
    'exclude' => true,
    'sorting' => true,
    'inputType' => 'select',
    'filter' => true,
    'options' => array(
        'male',
        'female'
    ),
    'reference' => &$GLOBALS['TL_LANG']['MSC'],
    'eval' => array(
        'includeBlankOption' => true,
        'mandatory' => true,
        'maxlength' => 255,
        'tl_class' => ''
    ),
    'sql' => "varchar(30) NOT NULL default ''",
);

$GLOBALS['TL_DCA']['tl_user']['fields']['street'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_user']['street'],
    'search' => true,
    'exclude' => true,
    'sorting' => true,
    'flag' => 1,
    'inputType' => 'text',
    'eval' => array(
        'maxlength' => 255,
        'tl_class' => ''
    ),
    'sql' => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_user']['fields']['postal'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_user']['postal'],
    'search' => true,
    'exclude' => true,
    'sorting' => true,
    'flag' => 1,
    'inputType' => 'text',
    'eval' => array(
        'maxlength' => 4,
        'tl_class' => ''
    ),
    'sql' => "varchar(32) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_user']['fields']['city'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_user']['city'],
    'search' => true,
    'exclude' => true,
    'sorting' => true,
    'flag' => 1,
    'inputType' => 'text',
    'eval' => array(
        'maxlength' => 255,
        'tl_class' => ''
    ),
    'sql' => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_user']['fields']['telephone'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_user']['telephone'],
    'search' => true,
    'exclude' => true,
    'sorting' => true,
    'flag' => 1,
    'inputType' => 'text',
    'eval' => array(
        'mandatory' => false,
        'rgxp' => 'phone',
        'maxlength' => 13,
        'tl_class' => ''
    ),
    'sql' => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_user']['fields']['mobile'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_user']['mobile'],
    'search' => true,
    'exclude' => true,
    'sorting' => true,
    'flag' => 1,
    'inputType' => 'text',
    'eval' => array(
        'mandatory' => false,
        'rgxp' => 'phone',
        'maxlength' => 13,
        'tl_class' => ''
    ),
    'sql' => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_user']['fields']['email'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_user']['email'],
    'search' => true,
    'exclude' => true,
    'sorting' => true,
    'flag' => 1,
    'inputType' => 'text',
    'eval' => array(
        'mandatory' => false,
        'rgxp' => 'email',
        'tl_class' => ''
    ),
    'sql' => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_user']['fields']['alternate_email'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_user']['alternate_email'],
    'search' => true,
    'exclude' => true,
    'sorting' => true,
    'flag' => 1,
    'inputType' => 'text',
    'eval' => array(
        'mandatory' => false,
        'rgxp' => 'email',
        'tl_class' => ''
    ),
    'sql' => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_user']['fields']['alternate_email_2'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_user']['alternate_email_2'],
    'search' => true,
    'exclude' => true,
    'sorting' => true,
    'flag' => 1,
    'inputType' => 'text',
    'eval' => array(
        'mandatory' => false,
        'rgxp' => 'email',
        'tl_class' => ''
    ),
    'sql' => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_user']['fields']['url'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_user']['url'],
    'search' => true,
    'exclude' => true,
    'sorting' => true,
    'flag' => 1,
    'inputType' => 'text',
    'eval' => array(
        'mandatory' => false,
        'rgxp' => 'url',
        'maxlength' => 255,
        'tl_class' => ''
    ),
    'sql' => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_user']['fields']['kategorie'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_user']['kategorie'],
    'search' => true,
    'exclude' => true,
    'sorting' => true,
    'filter' => true,
    'flag' => 1,
    'inputType' => 'select',
    'options' => explode(',', $GLOBALS['TL_CONFIG']['mcupic_be_benutzerverwaltung_kategorie']),
    'eval' => array(
        'mandatory' => false,
        'maxlength' => 255,
        'tl_class' => '',
        'includeBlankOption' => true
    ),
    'sql' => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_user']['fields']['link_digitalrock'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_user']['link_digitalrock'],
    'search' => true,
    'exclude' => true,
    'sorting' => true,
    'flag' => 1,
    'inputType' => 'text',
    'eval' => array(
        'mandatory' => false,
        'maxlength' => 255,
        'tl_class' => ''
    ),
    'sql' => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_user']['fields']['niveau'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_user']['niveau'],
    'search' => true,
    'exclude' => true,
    'sorting' => true,
    'filter' => true,
    'flag' => 1,
    'inputType' => 'select',
    'options' => explode(',', $GLOBALS['TL_CONFIG']['mcupic_be_benutzerverwaltung_niveau']),
    'eval' => array(
        'includeBlankOption' => true,
        'tl_class' => ''
    ),
    'sql' => "blob NULL"
);

$GLOBALS['TL_DCA']['tl_user']['fields']['trainingsgruppe'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_user']['trainingsgruppe'],
    'search' => true,
    'exclude' => true,
    'sorting' => true,
    'filter' => true,
    'flag' => 1,
    'inputType' => 'select',
    'options' => explode(',', $GLOBALS['TL_CONFIG']['mcupic_be_benutzerverwaltung_trainingsgruppe']),
    'eval' => array(
        'includeBlankOption' => true,
        'tl_class' => ''
    ),
    'sql' => "blob NULL"
);

$GLOBALS['TL_DCA']['tl_user']['fields']['funktion'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_user']['funktion'],
    'search' => true,
    'filter' => true,
    'exclude' => true,
    'sorting' => true,
    'flag' => 1,
    'inputType' => 'checkbox',
    'options' => explode(',', $GLOBALS['TL_CONFIG']['mcupic_be_benutzerverwaltung_funktion']),
    'eval' => array(
        'mandatory' => false,
        'multiple' => true,
        'tl_class' => ''
    ),
    'sql' => "blob NULL"
);

$GLOBALS['TL_DCA']['tl_user']['fields']['funktionsbeschreibung'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_user']['funktionsbeschreibung'],
    'search' => true,
    'exclude' => true,
    'sorting' => true,
    'flag' => 1,
    'inputType' => 'text',
    'eval' => array('tl_class' => ''),
    'sql' => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_user']['fields']['ahv_nr'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_user']['ahv_nr'],
    'search' => true,
    'exclude' => true,
    'sorting' => true,
    'flag' => 1,
    'inputType' => 'text',
    'default' => '756.',
    'eval' => array('tl_class' => '', 'maxlength' => 16),
    'sql' => "varchar(16) NOT NULL default '756.'"
);

$GLOBALS['TL_DCA']['tl_user']['fields']['iban'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_user']['iban'],
    'search' => true,
    'exclude' => true,
    'sorting' => true,
    'flag' => 1,
    'inputType' => 'text',
    'eval' => array('tl_class' => '', 'maxlength' => 26),
    'sql' => "varchar(26) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_user']['fields']['trainerqualifikation'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_user']['trainerqualifikation'],
    'search' => true,
    'exclude' => true,
    'sorting' => true,
    'filter' => true,
    'flag' => 1,
    'inputType' => 'checkbox',
    'options' => explode(',', $GLOBALS['TL_CONFIG']['mcupic_be_benutzerverwaltung_trainerqualifikation']),
    'eval' => array(
        'multiple' => true,
        'tl_class' => ''
    ),
    'sql' => "blob NULL"
);

$GLOBALS['TL_DCA']['tl_user']['fields']['nationalmannschaft'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_user']['nationalmannschaft'],
    'search' => true,
    'exclude' => true,
    'sorting' => true,
    'filter' => true,
    'flag' => 1,
    'inputType' => 'select',
    'options' => array(
        '0' => 'false',
        '1' => 'true'
    ),
    'eval' => array('tl_class' => ''),
    'sql' => "int(1) NOT NULL default '0'"
);

$GLOBALS['TL_DCA']['tl_user']['fields']['dateOfBirth'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_user']['dateOfBirth'],
    'exclude' => true,
    'search' => true,
    'sorting' => true,
    'inputType' => 'text',
    'eval' => array(
        'maxlength' => 10,
        'datepicker' => $this->getDatePickerString(),
        'submitOnChange' => false,
        'rgxp' => 'date',
        'tl_class' => ' wizard'
    ),
    'sql' => "int(14) NOT NULL default '0'"
);

$GLOBALS['TL_DCA']['tl_user']['fields']['sac_sektion'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_user']['sac_sektion'],
    'exclude' => true,
    'search' => true,
    'sorting' => true,
    'filter' => true,
    'flag' => 1,
    'inputType' => 'select',
    'options' => explode(',', $GLOBALS['TL_CONFIG']['mcupic_be_benutzerverwaltung_sac_sektion']),
    'eval' => array(
        'maxlength' => 255,
        'tl_class' => ''
    ),
    'sql' => "blob NULL"
);

$GLOBALS['TL_DCA']['tl_user']['fields']['fe_sorting'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_user']['fe_sorting'],
    'search' => true,
    'exclude' => true,
    'sorting' => true,
    'filter' => true,
    'inputType' => 'text',
    'eval' => array(
        'rgxp' => 'digit',
    ),
    'sql' => "int(14) NOT NULL default '999'"
);

$GLOBALS['TL_DCA']['tl_user']['fields']['avatar'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_user']['avatar'],
    'exclude' => true,
    'inputType' => 'fileTree',
    'eval' => array('filesOnly' => true, 'fieldType' => 'radio', 'mandatory' => false, 'tl_class' => 'clr'),
    'load_callback' => array
    (//array('tl_content', 'setSingleSrcFlags')
    ),
    'save_callback' => array
    (//array('tl_content', 'storeFileMetaInformation')
    ),
    'sql' => "binary(16) NULL"
);

$GLOBALS['TL_DCA']['tl_user']['fields']['assignedMember'] = array(
    'sql' => "int(10) NOT NULL default '0'"
);

$GLOBALS['TL_DCA']['tl_user']['fields']['getPasswordField'] = array(
    'sql' => "char(1) NOT NULL default ''"
);

/*********************************end fields************************************************************/


if ($_GET["do"] == "mcupic_be_benutzerverwaltung")
{
    //continue
}
elseif ($_GET["do"] == "group")
{
    //continue
}
elseif ($_GET["do"] == "login")
{
    //continue
}
else
{
    return;
}


/**
 * Table tl_user
 */

//config
$GLOBALS['TL_DCA']['tl_user']['config']['onload_callback'][] = array(
    'mcupic_be_benutzerverwaltung',
    'checkPermission'
);
$GLOBALS['TL_DCA']['tl_user']['config']['onload_callback'][] = array(
    'mcupic_be_benutzerverwaltung',
    'setFieldPermissions'
);


//list
$GLOBALS['TL_DCA']['tl_user']['list']['sorting'] = array(
    'mode' => 2,
    'fields' => array('dateAdded DESC'),
    'flag' => 1,
    'panelLayout' => 'filter;sort,search'
);

$GLOBALS['TL_DCA']['tl_user']['list']['label'] = array(
    'fields' => array(
        'name',
        'funktion',
        'kategorie'
    ),
    'showColumns' => true,
    'format' => '<span style="font-weight:bold">%s</span> #age# <span style="color:#b3b3b3; padding-left:3px;">[%s]</span> %s ',
    'label_callback' => array(
        'mcupic_be_benutzerverwaltung',
        'labelCallback'
    )
);

$GLOBALS['TL_DCA']['tl_user']['list']['global_operations'] = array(
    'all' => array(
        'label' => &$GLOBALS['TL_LANG']['MSC']['all'],
        'href' => 'act=select',
        'class' => 'header_edit_all',
        'attributes' => 'onclick="Backend.getScrollOffset();" accesskey="e"'
    ),
    'download_addresses' => array(
        'label' => &$GLOBALS['TL_LANG']['tl_user']['download_addresses'],
        'href' => 'action=exportTable&id=11&key=89ab8f345b0200e4a7b92d9b435d7e5b',
        'class' => 'header_download_addresses',
        'attributes' => 'onclick="Backend.getScrollOffset();" accesskey="d"'
    )
);


//palettes
$GLOBALS['TL_DCA']['tl_user']['palettes']['__selector__'] = array();
$GLOBALS['TL_DCA']['tl_user']['palettes']['admin'] = '{name_legend},isRSZ,disable,admin,name,gender,street,postal,city,dateOfBirth,avatar;{extended_data},iban,ahv_nr,fe_sorting;{contact_legend},telephone,mobile,email,alternate_email,alternate_email_2,url;{information_legend:hide},sac_sektion,funktion,funktionsbeschreibung;{athlete:hide},nationalmannschaft,niveau,trainingsgruppe,link_digitalrock,kategorie;{trainer:hide},trainerqualifikation;{groups_legend},groups;{password_legend:hide},username,pwChange,password';
$GLOBALS['TL_DCA']['tl_user']['palettes']['restricted_user'] = '{name_legend},isRSZ,username,gender,name,street,postal,city,dateOfBirth,avatar; {contact_legend},telephone,mobile,email,alternate_email,alternate_email_2,url;{extended_data},iban,ahv_nr;{information_legend:hide},sac_sektion,funktion,funktionsbeschreibung;{athlete:hide},nationalmannschaft,niveau,trainingsgruppe,link_digitalrock,kategorie;{trainer:hide},trainerqualifikation;{password_legend:hide},password';
$GLOBALS['TL_DCA']['tl_user']['palettes']['address_admin'] = '{name_legend},isRSZ,gender,name,street,postal,city,dateOfBirth,avatar;{contact_legend},telephone,mobile,email,alternate_email,alternate_email_2,url;{extended_data},iban,ahv_nr;{information_legend:hide},sac_sektion,funktion,funktionsbeschreibung;{athlete:hide},nationalmannschaft,niveau,trainingsgruppe,link_digitalrock,kategorie;{trainer:hide},trainerqualifikation;{password_legend:hide},username,pwChange,password';


//fields
$GLOBALS['TL_DCA']['tl_user']['fields']['name']['eval']['tl_class'] = '';

/**
 * Class mcupic_be_benutzerverwaltung
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Marko Cupic 2005-2010
 * @author     Marko Cupi  <http://www.contao.org>
 * @package    Controller
 */
class mcupic_be_benutzerverwaltung extends tl_user
{

    public $modulname;


    public function __construct()
    {

        parent::__construct();
        $this->modulname = "mcupic_be_benutzerverwaltung";
        //HOOKS registrieren
        //parse Backend Template Hook
        $GLOBALS['TL_HOOKS']['parseBackendTemplate'][] = array(
            'mcupic_be_benutzerverwaltung',
            'myParseBackendTemplate'
        );
        if ($this->Input->get('act2') == 'download_addresses')
        {
            $a = new RSZAdressenDownload;
            $a->downloadAddressesXls();
        }

    }


    //Setzt die Palette
    public function setPalette($paletname)
    {
        $GLOBALS['TL_DCA']['tl_user']['palettes']['default'] = $GLOBALS['TL_DCA']['tl_user']['palettes'][$paletname];
    }


    public function checkPermission()
    {

        if ($this->User->isAdmin)
        {
            return;
        }
        else
        {
            $GLOBALS['TL_DCA']['tl_user']['list']['sorting']['filter'] = array(
                array(
                    'isRSZ=?',
                    '1'
                )
            );

        }

        //for all other users
        unset($GLOBALS['TL_DCA']['tl_user']['list']['operations']['reset']);
        unset($GLOBALS['TL_DCA']['tl_user']['list']['operations']['toggle']);
        unset($GLOBALS['TL_DCA']['tl_user']['list']['operations']['su']);
        unset($GLOBALS['TL_DCA']['tl_user']['list']['operations']['copy']);

        if ($this->User->isMemberOf($GLOBALS['TL_CONFIG']['mcupic_be_benutzerverwaltung_id_group_address_admin']))
        {
            return;
        }

        //Benutzer wird gleich weitergeleitet zur Detailansicht
        $GLOBALS['TL_LANG']['MOD']['mcupic_be_benutzerverwaltung'] = array(
            'Mein Konto',
            'Meine Benutzerangaben verwalten'
        );
        if (!isset($_GET["act"]))
        {
            $this->redirect('contao/main.php?do=' . $this->modulname . '&act=edit&id=' . $this->User->id . '&rt=' . REQUEST_TOKEN . '&ref=' . TL_REFERER_ID);
        }

        if ($this->User->id == $this->Input->get('id'))
        {
            return;
        }
        $this->redirect('contao/main.php?do=' . $this->modulname . '&act=edit&id=' . $this->User->id . '&rt=' . REQUEST_TOKEN . '&ref=' . TL_REFERER_ID);
    }


    public function setFieldPermissions()
    {

        if ($this->User->isAdmin)
        {
            $this->setPalette('admin');
            return;
        }
        elseif ($this->User->isMemberOf($GLOBALS['TL_CONFIG']['mcupic_be_benutzerverwaltung_id_group_address_admin']))
        {
            $this->setPalette('address_admin');
            $readonlyFelder = array(
                'isRSZ'
            );
        }
        else
        {
            //Die readonly Felder
            $readonlyFelder = array(
                'isRSZ',
                'admin',
                'username',
                'name',
                'alternate_email',
                'alternate_email_2',
                //'sac_sektion',
                'funktion',
                'niveau',
                'trainingsgruppe',
                'funktionsbeschreibung',
                'trainerqualifikation',
                'kategorie',
                'link_digitalrock',
                'nationalmannschaft'
            );

            $this->setPalette('restricted_user');
        }

        foreach ($readonlyFelder as $Feldname)
        {
            $GLOBALS['TL_DCA']['tl_user']['fields'][$Feldname]['eval']['readonly'] = true;
            $GLOBALS['TL_DCA']['tl_user']['fields'][$Feldname]['eval']['style'] = " background-color:#ddd;";

            if ($GLOBALS['TL_DCA']['tl_user']['fields'][$Feldname]['inputType'] == 'select')
            {
                $GLOBALS['TL_DCA']['tl_user']['fields'][$Feldname]['inputType'] = 'text';
            }
            if ($GLOBALS['TL_DCA']['tl_user']['fields'][$Feldname]['inputType'] == 'checkbox')
            {
                $GLOBALS['TL_DCA']['tl_user']['fields'][$Feldname]['eval']['disabled'] = true;
            }
        }
    }


    //LABEL CALLBACK
    public function labelCallback($row, $label)
    {

        if ($row["dateOfBirth"] !== 0)
        {
            $age = Date::parse('Y') - Date::parse('Y', $row['dateOfBirth']);
            $age = $age . " yr.";
            $label = str_replace("#age#", $age, $label);
        }
        if ($row["kategorie"] !== "")
        {
            $kategorie = " [" . $row['kategorie'] . "]";
            $label = str_replace("#kategorie#", $kategorie, $label);
        }
        else
        {
            $label = str_replace("#kategorie#", "", $label);
        }

        return $label;
    }

    //HOOKS
    //ParseBackend HOOK
    public function myParseBackendTemplate($strContent, $strTemplate)
    {

        $strContent = str_replace('<a href="contao/main.php?do=mcupic_be_benutzerverwaltung&amp;act=download_addresses"', '<a href="contao/main.php?do=mcupic_be_benutzerverwaltung&amp;act2=download_addresses" target="_blank"', $strContent);

        if ($this->Input->get('act') == 'edit')
        {
            $replace = '
					<div id="tl_buttons">
						<a href="contao/main.php?do=mcupic_be_benutzerverwaltung" class="header_back" title="Zur&uuml;ck" accesskey="b" onclick="Backend.getScrollOffset();">Zur&uuml;ck</a>&nbsp;&nbsp;::&nbsp;&nbsp;
						<a href="contao/main.php?do=mcupic_be_benutzerverwaltung&amp;act2=download_addresses" target="_blank" class="header_download_addresses" title="' . $GLOBALS['TL_LANG']['tl_user']['download_addresses'][1] . '" onclick="Backend.getScrollOffset();" accesskey="d">' . $GLOBALS['TL_LANG']['tl_user']['download_addresses'][0] . '</a>
					</div>';

            $strContent = preg_replace('/<div id=\"tl_buttons((\r|\n|.)+?)div>/', $replace, $strContent);
        }

        return $strContent;
    }

}
