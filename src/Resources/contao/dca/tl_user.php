<?php

/**
 * @copyright  Marko Cupic 2020 <m.cupic@gmx.ch>
 * @author     Marko Cupic
 * @package    RSZ Benutzerverwaltung
 * @license    MIT
 * @see        https://github.com/markocupic/rsz-benutzerverwaltung-bundle
 *
 */

//onload_callback callback für tl_user
$GLOBALS['TL_DCA']['tl_user']['config']['onload_callback'][] = [
    'RSZUser',
    'maintainUserProperties'
];

//ondelete callback für tl_user
$GLOBALS['TL_DCA']['tl_user']['config']['ondelete_callback'][] = [
    'RSZUser',
    'deleteUserFromTlMember'
];
//$GLOBALS['TL_DCA']['tl_user']['config']['ondelete_callback'][] = array('tl_user', 'deleteUserDirectory');

/*
* damit das Benutzer Modul nachwievor reibungslos funktioniert...
*/

/******************fields*******************************************************************/
$GLOBALS['TL_DCA']['tl_user']['fields']['isRSZ'] = [
    'search'    => true,
    'exclude'   => true,
    'sorting'   => true,
    'filter'    => true,
    'flag'      => 1,
    'inputType' => 'select',
    'options'   => [0, 1],
    'default'   => 1,
    'eval'      => ['tl_class' => ''],
    'sql'       => "char(1) NOT NULL default '0'"
];

$GLOBALS['TL_DCA']['tl_user']['fields']['name']['eval']['rgxp'] = 'name';

$GLOBALS['TL_DCA']['tl_user']['fields']['gender'] = [
    'search'    => true,
    'exclude'   => true,
    'sorting'   => true,
    'inputType' => 'select',
    'filter'    => true,
    'options'   => [
        'male',
        'female'
    ],
    'reference' => &$GLOBALS['TL_LANG']['MSC'],
    'eval'      => [
        'includeBlankOption' => true,
        'mandatory'          => true,
        'maxlength'          => 255,
        'tl_class'           => ''
    ],
    'sql'       => "varchar(30) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_user']['fields']['street'] = [
    'search'    => true,
    'exclude'   => true,
    'sorting'   => true,
    'flag'      => 1,
    'inputType' => 'text',
    'eval'      => [
        'maxlength' => 255,
        'tl_class'  => ''
    ],
    'sql'       => "varchar(255) NOT NULL default ''"
];

$GLOBALS['TL_DCA']['tl_user']['fields']['postal'] = [
    'search'    => true,
    'exclude'   => true,
    'sorting'   => true,
    'flag'      => 1,
    'inputType' => 'text',
    'eval'      => [
        'maxlength' => 4,
        'tl_class'  => ''
    ],
    'sql'       => "varchar(32) NOT NULL default ''"
];

$GLOBALS['TL_DCA']['tl_user']['fields']['city'] = [
    'search'    => true,
    'exclude'   => true,
    'sorting'   => true,
    'flag'      => 1,
    'inputType' => 'text',
    'eval'      => [
        'maxlength' => 255,
        'tl_class'  => ''
    ],
    'sql'       => "varchar(255) NOT NULL default ''"
];

$GLOBALS['TL_DCA']['tl_user']['fields']['telephone'] = [
    'search'    => true,
    'exclude'   => true,
    'sorting'   => true,
    'flag'      => 1,
    'inputType' => 'text',
    'eval'      => [
        'mandatory' => false,
        'rgxp'      => 'phone',
        'maxlength' => 13,
        'tl_class'  => ''
    ],
    'sql'       => "varchar(255) NOT NULL default ''"
];

$GLOBALS['TL_DCA']['tl_user']['fields']['mobile'] = [
    'search'    => true,
    'exclude'   => true,
    'sorting'   => true,
    'flag'      => 1,
    'inputType' => 'text',
    'eval'      => [
        'mandatory' => false,
        'rgxp'      => 'phone',
        'maxlength' => 13,
        'tl_class'  => ''
    ],
    'sql'       => "varchar(255) NOT NULL default ''"
];

$GLOBALS['TL_DCA']['tl_user']['fields']['alternate_email'] = [
    'search'    => true,
    'exclude'   => true,
    'sorting'   => true,
    'flag'      => 1,
    'inputType' => 'text',
    'eval'      => [
        'mandatory' => false,
        'rgxp'      => 'email',
        'tl_class'  => ''
    ],
    'sql'       => "varchar(255) NOT NULL default ''"
];

$GLOBALS['TL_DCA']['tl_user']['fields']['alternate_email_2'] = [
    'search'    => true,
    'exclude'   => true,
    'sorting'   => true,
    'flag'      => 1,
    'inputType' => 'text',
    'eval'      => [
        'mandatory' => false,
        'rgxp'      => 'email',
        'tl_class'  => ''
    ],
    'sql'       => "varchar(255) NOT NULL default ''"
];

$GLOBALS['TL_DCA']['tl_user']['fields']['url'] = [
    'search'    => true,
    'exclude'   => true,
    'sorting'   => true,
    'flag'      => 1,
    'inputType' => 'text',
    'eval'      => [
        'mandatory' => false,
        'rgxp'      => 'url',
        'maxlength' => 255,
        'tl_class'  => ''
    ],
    'sql'       => "varchar(255) NOT NULL default ''"
];

$GLOBALS['TL_DCA']['tl_user']['fields']['kategorie'] = [
    'search'    => true,
    'exclude'   => true,
    'sorting'   => true,
    'filter'    => true,
    'flag'      => 1,
    'inputType' => 'select',
    'options'   => explode(',', $GLOBALS['TL_CONFIG']['mcupic_be_benutzerverwaltung_kategorie']),
    'eval'      => [
        'mandatory'          => false,
        'maxlength'          => 255,
        'tl_class'           => '',
        'includeBlankOption' => true
    ],
    'sql'       => "varchar(255) NOT NULL default ''"
];

$GLOBALS['TL_DCA']['tl_user']['fields']['link_digitalrock'] = [
    'search'    => true,
    'exclude'   => true,
    'sorting'   => true,
    'flag'      => 1,
    'inputType' => 'text',
    'eval'      => [
        'mandatory' => false,
        'maxlength' => 255,
        'tl_class'  => ''
    ],
    'sql'       => "varchar(255) NOT NULL default ''"
];

$GLOBALS['TL_DCA']['tl_user']['fields']['niveau'] = [
    'search'    => true,
    'exclude'   => true,
    'sorting'   => true,
    'filter'    => true,
    'flag'      => 1,
    'inputType' => 'select',
    'options'   => explode(',', $GLOBALS['TL_CONFIG']['mcupic_be_benutzerverwaltung_niveau']),
    'eval'      => [
        'includeBlankOption' => true,
        'tl_class'           => ''
    ],
    'sql'       => "blob NULL"
];

$GLOBALS['TL_DCA']['tl_user']['fields']['trainingsgruppe'] = [
    'search'    => true,
    'exclude'   => true,
    'sorting'   => true,
    'filter'    => true,
    'flag'      => 1,
    'inputType' => 'select',
    'options'   => explode(',', $GLOBALS['TL_CONFIG']['mcupic_be_benutzerverwaltung_trainingsgruppe']),
    'eval'      => [
        'includeBlankOption' => true,
        'tl_class'           => ''
    ],
    'sql'       => "blob NULL"
];

$GLOBALS['TL_DCA']['tl_user']['fields']['funktion'] = [
    'search'    => true,
    'filter'    => true,
    'exclude'   => true,
    'sorting'   => true,
    'flag'      => 1,
    'inputType' => 'checkbox',
    'options'   => explode(',', $GLOBALS['TL_CONFIG']['mcupic_be_benutzerverwaltung_funktion']),
    'eval'      => [
        'mandatory' => false,
        'multiple'  => true,
        'tl_class'  => ''
    ],
    'sql'       => "blob NULL"
];

$GLOBALS['TL_DCA']['tl_user']['fields']['funktionsbeschreibung'] = [
    'search'    => true,
    'exclude'   => true,
    'sorting'   => true,
    'flag'      => 1,
    'inputType' => 'text',
    'eval'      => ['tl_class' => ''],
    'sql'       => "varchar(255) NOT NULL default ''"
];

$GLOBALS['TL_DCA']['tl_user']['fields']['ahv_nr'] = [
    'search'    => true,
    'exclude'   => true,
    'sorting'   => true,
    'flag'      => 1,
    'inputType' => 'text',
    'default'   => '756.',
    'eval'      => ['tl_class' => '', 'maxlength' => 16],
    'sql'       => "varchar(16) NOT NULL default '756.'"
];

$GLOBALS['TL_DCA']['tl_user']['fields']['iban'] = [
    'search'    => true,
    'exclude'   => true,
    'sorting'   => true,
    'flag'      => 1,
    'inputType' => 'text',
    'eval'      => ['tl_class' => '', 'maxlength' => 26],
    'sql'       => "varchar(26) NOT NULL default ''"
];

$GLOBALS['TL_DCA']['tl_user']['fields']['trainerqualifikation'] = [
    'search'    => true,
    'exclude'   => true,
    'sorting'   => true,
    'filter'    => true,
    'flag'      => 1,
    'inputType' => 'checkbox',
    'options'   => explode(',', $GLOBALS['TL_CONFIG']['mcupic_be_benutzerverwaltung_trainerqualifikation']),
    'eval'      => [
        'multiple' => true,
        'tl_class' => ''
    ],
    'sql'       => "blob NULL"
];

$GLOBALS['TL_DCA']['tl_user']['fields']['nationalmannschaft'] = [
    'search'    => true,
    'exclude'   => true,
    'sorting'   => true,
    'filter'    => true,
    'flag'      => 1,
    'inputType' => 'select',
    'options'   => [
        '0' => 'false',
        '1' => 'true'
    ],
    'eval'      => ['tl_class' => ''],
    'sql'       => "int(1) NOT NULL default '0'"
];

$GLOBALS['TL_DCA']['tl_user']['fields']['dateOfBirth'] = [
    'exclude'   => true,
    'search'    => true,
    'sorting'   => true,
    'inputType' => 'text',
    'eval'      => [
        'maxlength'      => 10,
        'datepicker'     => $this->getDatePickerString(),
        'submitOnChange' => false,
        'rgxp'           => 'date',
        'tl_class'       => ' wizard'
    ],
    'sql'       => "int(14) NOT NULL default '0'"
];

$GLOBALS['TL_DCA']['tl_user']['fields']['sac_sektion'] = [
    'exclude'   => true,
    'search'    => true,
    'sorting'   => true,
    'filter'    => true,
    'flag'      => 1,
    'inputType' => 'select',
    'options'   => explode(',', $GLOBALS['TL_CONFIG']['mcupic_be_benutzerverwaltung_sac_sektion']),
    'eval'      => [
        'maxlength' => 255,
        'tl_class'  => ''
    ],
    'sql'       => "blob NULL"
];

$GLOBALS['TL_DCA']['tl_user']['fields']['fe_sorting'] = [
    'search'    => true,
    'exclude'   => true,
    'sorting'   => true,
    'filter'    => true,
    'inputType' => 'text',
    'eval'      => [
        'rgxp' => 'digit',
    ],
    'sql'       => "int(14) NOT NULL default '999'"
];

$GLOBALS['TL_DCA']['tl_user']['fields']['avatar'] = [
    'exclude'   => true,
    'inputType' => 'fileTree',
    'eval'      => ['filesOnly' => true, 'fieldType' => 'radio', 'extensions' => 'jpg,jpeg,png', 'mandatory' => false, 'tl_class' => 'clr'],
    'sql'       => "binary(16) NULL"
];

$GLOBALS['TL_DCA']['tl_user']['fields']['assignedMember'] = [
    'sql' => "int(10) NOT NULL default '0'"
];

$GLOBALS['TL_DCA']['tl_user']['fields']['getPasswordField'] = [
    'sql' => "char(1) NOT NULL default ''"
];

/*********************************end fields************************************************************/

if ($_GET["do"] == "rsz_benutzerverwaltung")
{
    //continue
}
elseif ($_GET["do"] == "group")
{
    //continue
}
elseif ($_GET["do"] == "login")
{
    return;
}
else
{
    return;
}

/**
 * Table tl_user
 */

//config
$GLOBALS['TL_DCA']['tl_user']['config']['onload_callback'][] = [
    'rsz_benutzerverwaltung',
    'checkPermission'
];
$GLOBALS['TL_DCA']['tl_user']['config']['onload_callback'][] = [
    'rsz_benutzerverwaltung',
    'setFieldPermissions'
];

//list
$GLOBALS['TL_DCA']['tl_user']['list']['sorting'] = [
    'mode'        => 2,
    'fields'      => ['dateAdded DESC'],
    'flag'        => 1,
    'panelLayout' => 'filter;sort,search'
];

$GLOBALS['TL_DCA']['tl_user']['list']['label'] = [
    'fields'         => [
        'name',
        'funktion',
        'kategorie'
    ],
    'showColumns'    => true,
    'format'         => '<span style="font-weight:bold">%s</span> #age# <span style="color:#b3b3b3; padding-left:3px;">[%s]</span> %s ',
    'label_callback' => [
        'rsz_benutzerverwaltung',
        'labelCallback'
    ]
];

//palettes
$GLOBALS['TL_DCA']['tl_user']['palettes']['__selector__'] = [];
$GLOBALS['TL_DCA']['tl_user']['palettes']['admin'] = '{name_legend},isRSZ,disable,admin,name,gender,street,postal,city,dateOfBirth,avatar;{extended_data},iban,ahv_nr,fe_sorting;{contact_legend},telephone,mobile,email,alternate_email,alternate_email_2,url;{information_legend:hide},sac_sektion,funktion,funktionsbeschreibung;{athlete:hide},nationalmannschaft,niveau,trainingsgruppe,link_digitalrock,kategorie;{trainer:hide},trainerqualifikation;{groups_legend},groups;{password_legend:hide},username,pwChange,password';
$GLOBALS['TL_DCA']['tl_user']['palettes']['restricted_user'] = '{name_legend},isRSZ,username,gender,name,street,postal,city,dateOfBirth,avatar; {contact_legend},telephone,mobile,email,alternate_email,alternate_email_2,url;{extended_data},iban,ahv_nr;{information_legend:hide},sac_sektion,funktion,funktionsbeschreibung;{athlete:hide},nationalmannschaft,niveau,trainingsgruppe,link_digitalrock,kategorie;{trainer:hide},trainerqualifikation;{password_legend:hide},password';
$GLOBALS['TL_DCA']['tl_user']['palettes']['address_admin'] = '{name_legend},isRSZ,gender,name,street,postal,city,dateOfBirth,avatar;{contact_legend},telephone,mobile,email,alternate_email,alternate_email_2,url;{extended_data},iban,ahv_nr;{information_legend:hide},sac_sektion,funktion,funktionsbeschreibung;{athlete:hide},nationalmannschaft,niveau,trainingsgruppe,link_digitalrock,kategorie;{trainer:hide},trainerqualifikation;{password_legend:hide},username,pwChange,password';

//fields
$GLOBALS['TL_DCA']['tl_user']['fields']['name']['eval']['tl_class'] = 'clr';

/**
 * Class rsz_benutzerverwaltung
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Marko Cupic 2005-2010
 * @author     Marko Cupi  <http://www.contao.org>
 * @package    Controller
 */
class rsz_benutzerverwaltung extends tl_user
{

    /**
     * @var string
     */
    public $modulname;

    public function __construct()
    {
        parent::__construct();
        $this->modulname = "rsz_benutzerverwaltung";

        $this->import('BackendUser', 'User');
    }

    /**
     * Set field permissions
     */
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
            $readonlyFelder = [
                'isRSZ'
            ];
        }
        else
        {
            //Die readonly Felder
            $readonlyFelder = [
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
            ];

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

    /**
     * Set palette
     * @param string $paletname
     */
    public function setPalette(string $paletname)
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
            $GLOBALS['TL_DCA']['tl_user']['list']['sorting']['filter'] = [
                [
                    'isRSZ=?',
                    '1'
                ]
            ];
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
        $GLOBALS['TL_LANG']['MOD']['rsz_benutzerverwaltung'] = [
            'Mein Konto',
            'Meine Benutzerangaben verwalten'
        ];
        if (!isset($_GET["act"]))
        {
            $this->redirect(sprintf('contao?do=rsz_benutzerverwaltung&act=edit&id=%s&rt=%s&ref=%s', $this->User->id, REQUEST_TOKEN, TL_REFERER_ID));
        }

        if ($this->User->id == $this->Input->get('id'))
        {
            return;
        }

        $this->redirect(sprintf('contao?do=rsz_benutzerverwaltung&act=edit&id=%s&rt=%s&ref=%s', $this->User->id, REQUEST_TOKEN, TL_REFERER_ID));
    }

    /**
     * Label callback
     * @param $row
     * @param $label
     * @return mixed
     */
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

}
