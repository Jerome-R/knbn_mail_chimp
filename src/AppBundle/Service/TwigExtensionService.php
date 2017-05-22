<?php

namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;

use Application\Sonata\UserBundle\Entity\User;

class TwigExtensionService extends \Twig_Extension
{	
    private $em;
    private $service;

	public function __construct(EntityManager $entityManager)
	{
		$this->em 		= $entityManager;
	}

    public function getName()
    {
        return 'TwigExtensionService';
    }
    
    public function getFunctions()
    {   
        return array(
          'getModules'      => new \Twig_Function_Method($this, 'GetModules'),
          'getMonthWording' => new \Twig_Function_Method($this, 'getMonthWording'),
          'roundLetter'     => new \Twig_Function_Method($this, 'roundLetter'),
          'deleteFirstCharacters' => new \Twig_Function_Method($this, 'deleteFirstCharacters'),
          'getTransacM0'    => new \Twig_Function_Method($this, 'getTransacM0'),
          'getCountry'      => new \Twig_Function_Method($this, 'getCountry'),
          'redirectUrl'     => new \Twig_Function_Method($this, 'redirectUrl'),
          'unsuscribeLink'  => new \Twig_Function_Method($this, 'unsuscribeLink'),
        );
    }

    public function roundLetter($value){
        if ($value > 999 && $value <= 999999) {
            $result = floor($value / 1000) . ' K';
        } elseif ($value > 999999) {
            $result = floor($value / 1000000) . ' M';
        } else {
            $result = $value;
        }

        return $result;
    }

    public function getTransacM0(User $user, $date){
        $kpiM0 = $this->em->getRepository('AppBundle:KpiCapture')->findOneBy(array( 'user' =>  $user, 'date' => $date ));

        $nbTransac = $kpiM0->getnbTransacM0();

        return $nbTransac;
    }

    public function deleteFirstCharacters($word, $nb)
    {
        return substr($word, $nb);
    }

    //Get month Wording for Kpis
    public function getMonthWording($month, $perso = null) {

        switch ( $month ) {
            case '12':
                $monthWording = $perso." Décembre";
            break;
            case '01':
                $monthWording = $perso." Janvier";
            break;
            case '02':
                $monthWording = $perso." Février";
            break;
            case '03':
                $monthWording = $perso." Mars";
            break;
            case '04':
                if($perso == "de") {
                    $perso = "d'";
                    $monthWording = $perso."Avril";
                }
                else
                    $monthWording = $perso." Avril";
            break;
            case '05':
                $monthWording = $perso." Mai";
            break;
            case '06':
                $monthWording = $perso." Juin";
            break;
            case '07':
                $monthWording = $perso." Juillet";
            break;
            case '08':
                if($perso == "de"){
                    $perso = "d'";
                    $monthWording = $perso."Août";
                }
                else
                    $monthWording = $perso." Août";
            break;
            case '09':
                $monthWording = $perso." Septembre";
            break;
            case '10':
                if($perso == "de"){
                    $perso = "d'";
                    $monthWording = $perso."Octobre";
                }
                else
                    $monthWording = $perso." Octobre";
            break;
            case '11':
                $monthWording = $perso." Novembre";
            break;          
            default:
                $monthWording = "";
            break;
        }

        return $monthWording;
    }

    public function getCountryIso2($codeIso){

        $country = $codeIso;
        
        switch($codeIso){
            case "AF" :
                $country = "AFGHANISTAN";
            break;
            case "ZA" :
                $country = "AFRIQUE DU SUD";
            break;
            case "AX" :
                $country = "ÅLAND, ÎLES";
            break;
            case "AL" :
                $country = "ALBANIE";
            break;
            case "DZ" :
                $country = "ALGERIE";
            break;
            case "DE" :
                $country = "ALLEMAGNE";
            break;
            case "AD" :
                $country = "ANDORRE";
            break;
            case "AO" :
                $country = "ANGOLA";
            break;
            case "AI" :
                $country = "ANGUILLA";
            break;
            case "AQ" :
                $country = "ANTARCTIQUE";
            break;
            case "AG" :
                $country = "ANTIGUA ET BARBUDA";
            break;
            case "SA" :
                $country = "ARABIE SAOUDITE";
            break;
            case "AR" :
                $country = "ARGENTINE";
            break;
            case "AM" :
                $country = "ARMENIE";
            break;
            case "AW" :
                $country = "ARUBA";
            break;
            case "AU" :
                $country = "AUSTRALIE";
            break;
            case "AT" :
                $country = "AUTRICHE";
            break;
            case "AZ" :
                $country = "AZERBAIDJAN";
            break;
            case "BS" :
                $country = "BAHAMAS";
            break;
            case "BH" :
                $country = "BAHREIN";
            break;
            case "BD" :
                $country = "BANGLADESH";
            break;
            case "BB" :
                $country = "BARBADE";
            break;
            case "BY" :
                $country = "BELARUS";
            break;
            case "BE" :
                $country = "BELGIQUE";
            break;
            case "BZ" :
                $country = "BELIZE";
            break;
            case "BJ" :
                $country = "BENIN";
            break;
            case "BM" :
                $country = "BERMUDES";
            break;
            case "BT" :
                $country = "BHOUTAN";
            break;
            case "BO" :
                $country = "ETAT PLURINATIONAL DE BOLIVIE";
            break;
            case "BQ" :
                $country = "BONAIRE, SAINT-EUSTACHE ET SABA";
            break;
            case "BA" :
                $country = "BOSNIE-HERZEGOVINE";
            break;
            case "BW" :
                $country = "BOTSWANA";
            break;
            case "BV" :
                $country = "BOUVET, ILE";
            break;
            case "BR" :
                $country = "BRESIL";
            break;
            case "BN" :
                $country = "BRUNEI DARUSSALAM";
            break;
            case "BG" :
                $country = "BULGARIE";
            break;
            case "BF" :
                $country = "BURKINA FASO";
            break;
            case "BI" :
                $country = "BURUNDI";
            break;
            case "KY" :
                $country = "ILES CAIMANES";
            break;
            case "KH" :
                $country = "CAMBODGE";
            break;
            case "CM" :
                $country = "CAMEROUN";
            break;
            case "CA" :
                $country = "CANADA";
            break;
            case "CV" :
                $country = "CAP-VERT";
            break;
            case "CF" :
                $country = "REPUBLIQUE CENTRAFRICAINE";
            break;
            case "CL" :
                $country = "CHILI";
            break;
            case "CN" :
                $country = "CHINE";
            break;
            case "CX" :
                $country = "CHRISTMAS, ILE";
            break;
            case "CY" :
                $country = "CHYPRE";
            break;
            case "CC" :
                $country = "ILES COCOS (KEELING)";
            break;
            case "CO" :
                $country = "COLOMBIE";
            break;
            case "KM" :
                $country = "COMORES";
            break;
            case "CG" :
                $country = "CONGO";
            break;
            case "CD" :
                $country = "LA REPUBLIQUE DEMOCRATIQUE DUCONGO";
            break;
            case "CK" :
                $country = "ILES COOK";
            break;
            case "KR" :
                $country = "REPUBLIQUE DE COREE";
            break;
            case "KP" :
                $country = "REPUBLIQUE POPULAIRE DEMOCRATIQUE DE COREE,";
            break;
            case "CR" :
                $country = "COSTA RICA";
            break;
            case "CI" :
                $country = "COTE D'IVOIRE";
            break;
            case "HR" :
                $country = "CROATIE";
            break;
            case "CU" :
                $country = "CUBA";
            break;
            case "CW" :
                $country = "CURACAO";
            break;
            case "DK" :
                $country = "DANEMARK";
            break;
            case "DJ" :
                $country = "DJIBOUTI";
            break;
            case "DO" :
                $country = "REPUBLIQUE DOMINICAINE";
            break;
            case "DM" :
                $country = "DOMINIQUE";
            break;
            case "EG" :
                $country = "EGYPTE";
            break;
            case "SV" :
                $country = "EL SALVADOR";
            break;
            case "AE" :
                $country = "EMIRATS ARABES UNIS";
            break;
            case "EC" :
                $country = "EQUATEUR";
            break;
            case "ER" :
                $country = "ERYTHREE";
            break;
            case "ES" :
                $country = "ESPAGNE";
            break;
            case "EE" :
                $country = "ESTONIE";
            break;
            case "US" :
                $country = "ETATS-UNIS";
            break;
            case "ET" :
                $country = "ETHIOPIE";
            break;
            case "FK" :
                $country = "ILES FALKLAND (MALVINAS)";
            break;
            case "FO" :
                $country = "ILES FEROE";
            break;
            case "FJ" :
                $country = "FIDJI";
            break;
            case "FI" :
                $country = "FINLANDE";
            break;
            case "FR" :
                $country = "FRANCE";
            break;
            case "GA" :
                $country = "GABON";
            break;
            case "GM" :
                $country = "GAMBIE";
            break;
            case "GE" :
                $country = "GEORGIE";
            break;
            case "GS" :
                $country = "GEORGIE DU SUD ET LES ILES SANDWICH DU SUD";
            break;
            case "GH" :
                $country = "GHANA";
            break;
            case "GI" :
                $country = "GIBRALTAR";
            break;
            case "GR" :
                $country = "GRECE";
            break;
            case "GD" :
                $country = "GRENADE";
            break;
            case "GL" :
                $country = "GROENLAND";
            break;
            case "GP" :
                $country = "GUADELOUPE";
            break;
            case "GU" :
                $country = "GUAM";
            break;
            case "GT" :
                $country = "GUATEMALA";
            break;
            case "GG" :
                $country = "GUERNESEY";
            break;
            case "GN" :
                $country = "GUINEE";
            break;
            case "GW" :
                $country = "GUINEE-BISSAU";
            break;
            case "GQ" :
                $country = "GUINEE EQUATORIALE";
            break;
            case "GY" :
                $country = "GUYANA";
            break;
            case "GF" :
                $country = "GUYANE FRANCAISE";
            break;
            case "HT" :
                $country = "HAITI";
            break;
            case "HM" :
                $country = "ILES HEARD ET MCDONALD";
            break;
            case "HN" :
                $country = "HONDURAS";
            break;
            case "HK" :
                $country = "HONG KONG";
            break;
            case "HU" :
                $country = "HONGRIE";
            break;
            case "IM" :
                $country = "ILE DE MAN";
            break;
            case "UM" :
                $country = "ILES MINEURES ELOIGNEES DES ETATS-UNIS";
            break;
            case "VG" :
                $country = "ILES VIERGES BRITANNIQUES";
            break;
            case "VI" :
                $country = "ILES VIERGES DES ETATS-UNIS";
            break;
            case "IN" :
                $country = "INDE";
            break;
            case "ID" :
                $country = "INDONESIE";
            break;
            case "IR" :
                $country = "REPUBLIQUE ISLAMIQUE D'IRAN";
            break;
            case "IQ" :
                $country = "IRAQ";
            break;
            case "IE" :
                $country = "IRLANDE";
            break;
            case "IS" :
                $country = "ISLANDE";
            break;
            case "IL" :
                $country = "ISRAEL";
            break;
            case "IT" :
                $country = "ITALIE";
            break;
            case "JM" :
                $country = "JAMAIQUE";
            break;
            case "JP" :
                $country = "JAPON";
            break;
            case "JE" :
                $country = "JERSEY";
            break;
            case "JO" :
                $country = "JORDANIE";
            break;
            case "KZ" :
                $country = "KAZAKHSTAN";
            break;
            case "KE" :
                $country = "KENYA";
            break;
            case "KG" :
                $country = "KIRGHIZISTAN";
            break;
            case "KI" :
                $country = "KIRIBATI";
            break;
            case "KW" :
                $country = "KOWEIT";
            break;
            case "LA" :
                $country = "REPUBLIQUE DEMOCRATIQUE POPULAIRE LAO";
            break;
            case "LS" :
                $country = "LESOTHO";
            break;
            case "LV" :
                $country = "LETTONIE";
            break;
            case "LB" :
                $country = "LIBAN";
            break;
            case "LR" :
                $country = "LIBERIA";
            break;
            case "LY" :
                $country = "JAMAHIRIYA ARABE LIBYENNE";
            break;
            case "LI" :
                $country = "LIECHTENSTEIN";
            break;
            case "LT" :
                $country = "LITUANIE";
            break;
            case "LU" :
                $country = "LUXEMBOURG";
            break;
            case "MO" :
                $country = "MACAO";
            break;
            case "MK" :
                $country = "EX-REPUBLIQUE YOUGOSLAVE DE MACEDOINE";
            break;
            case "MG" :
                $country = "MADAGASCAR";
            break;
            case "MY" :
                $country = "MALAISIE";
            break;
            case "MW" :
                $country = "MALAWI";
            break;
            case "MV" :
                $country = "MALDIVES";
            break;
            case "ML" :
                $country = "MALI";
            break;
            case "MT" :
                $country = "MALTE";
            break;
            case "MP" :
                $country = "ILES MARIANNES DU NORD";
            break;
            case "MA" :
                $country = "MAROC";
            break;
            case "MH" :
                $country = "ILES MARSHALL";
            break;
            case "MQ" :
                $country = "MARTINIQUE";
            break;
            case "MU" :
                $country = "MAURICE";
            break;
            case "MR" :
                $country = "MAURITANIE";
            break;
            case "YT" :
                $country = "MAYOTTE";
            break;
            case "MX" :
                $country = "MEXIQUE";
            break;
            case "FM" :
                $country = "ETATS FEDERES DE MICRONESIE";
            break;
            case "MD" :
                $country = "REPUBLIQUE DE MOLDOVA";
            break;
            case "MC" :
                $country = "MONACO";
            break;
            case "MN" :
                $country = "MONGOLIE";
            break;
            case "ME" :
                $country = "MONTENEGRO";
            break;
            case "MS" :
                $country = "MONTSERRAT";
            break;
            case "MZ" :
                $country = "MOZAMBIQUE";
            break;
            case "MM" :
                $country = "MYANMAR";
            break;
            case "NA" :
                $country = "NAMIBIE";
            break;
            case "NR" :
                $country = "NAURU";
            break;
            case "NP" :
                $country = "NEPAL";
            break;
            case "NI" :
                $country = "NICARAGUA";
            break;
            case "NE" :
                $country = "NIGER";
            break;
            case "NG" :
                $country = "NIGERIA";
            break;
            case "NU" :
                $country = "NIUE";
            break;
            case "NF" :
                $country = "ILE NORFOLK";
            break;
            case "NO" :
                $country = "NORVEGE";
            break;
            case "NC" :
                $country = "NOUVELLE-CALEDONIE";
            break;
            case "NZ" :
                $country = "NOUVELLE-ZELANDE";
            break;
            case "IO" :
                $country = "TERRITOIRE BRITANNIQUE DE L'OCEAN INDIEN";
            break;
            case "OM" :
                $country = "OMAN";
            break;
            case "UG" :
                $country = "OUGANDA";
            break;
            case "UZ" :
                $country = "OUZBEKISTAN";
            break;
            case "PK" :
                $country = "PAKISTAN";
            break;
            case "PW" :
                $country = "PALAOS";
            break;
            case "PS" :
                $country = "TERRITOIRE PALESTINIEN OCCUPE";
            break;
            case "PA" :
                $country = "PANAMA";
            break;
            case "PG" :
                $country = "PAPOUASIE-NOUVELLE-GUINEE";
            break;
            case "PY" :
                $country = "PARAGUAY";
            break;
            case "NL" :
                $country = "PAYS-BAS";
            break;
            case "PE" :
                $country = "PEROU";
            break;
            case "PH" :
                $country = "PHILIPPINES";
            break;
            case "PN" :
                $country = "PITCAIRN";
            break;
            case "PL" :
                $country = "POLOGNE";
            break;
            case "PF" :
                $country = "POLYNESIE FRANCAISE";
            break;
            case "PR" :
                $country = "PORTO RICO";
            break;
            case "PT" :
                $country = "PORTUGAL";
            break;
            case "QA" :
                $country = "QATAR";
            break;
            case "RE" :
                $country = "REUNION";
            break;
            case "RO" :
                $country = "ROUMANIE";
            break;
            case "GB" :
                $country = "ROYAUME-UNI";
            break;
            case "RU" :
                $country = "FEDERATION DE RUSSIE";
            break;
            case "RW" :
                $country = "RWANDA";
            break;
            case "EH" :
                $country = "SAHARA OCCIDENTAL";
            break;
            case "BL" :
                $country = "SAINT-BARTHELEMY";
            break;
            case "SH" :
                $country = "ASCENSION ET TRISTAN DA CUNHA SAINTE-HELENE";
            break;
            case "LC" :
                $country = "SAINTE-LUCIE";
            break;
            case "KN" :
                $country = "SAINT-KITTS-ET-NEVIS";
            break;
            case "SM" :
                $country = "SAINT-MARIN";
            break;
            case "MF" :
                $country = "SAINT-MARTIN (PARTIE FRANCAISE)";
            break;
            case "SX" :
                $country = "SAINT-MARTIN (PARTIE NEERLANDAISE)";
            break;
            case "PM" :
                $country = "SAINT-PIERRE-ET-MIQUELON";
            break;
            case "VA" :
                $country = "SAINT-SIEGE (ETAT DE LA CITE DU VATICAN)";
            break;
            case "VC" :
                $country = "SAINT-VINCENT-ET-LES GRENADINES";
            break;
            case "SB" :
                $country = "ILES SALOMON";
            break;
            case "WS" :
                $country = "SAMOA";
            break;
            case "AS" :
                $country = "SAMOA AMERICAINES";
            break;
            case "ST" :
                $country = "SAO TOME-ET-PRINCIPE";
            break;
            case "SN" :
                $country = "SENEGAL";
            break;
            case "RS" :
                $country = "SERBIE";
            break;
            case "SC" :
                $country = "SEYCHELLES";
            break;
            case "SL" :
                $country = "SIERRA LEONE";
            break;
            case "SG" :
                $country = "SINGAPOUR";
            break;
            case "SK" :
                $country = "SLOVAQUIE";
            break;
            case "SI" :
                $country = "SLOVENIE";
            break;
            case "SO" :
                $country = "SOMALIE";
            break;
            case "SD" :
                $country = "SOUDAN";
            break;
            case "LK" :
                $country = "SRI LANKA";
            break;
            case "SE" :
                $country = "SUEDE";
            break;
            case "CH" :
                $country = "SUISSE";
            break;
            case "SR" :
                $country = "SURINAME";
            break;
            case "SJ" :
                $country = "SVALBARD ET ILE JAN MAYEN";
            break;
            case "SZ" :
                $country = "SWAZILAND";
            break;
            case "SY" :
                $country = "REPUBLIQUE ARABE SYRIENNE";
            break;
            case "TJ" :
                $country = "TADJIKISTAN";
            break;
            case "TW" :
                $country = "PROVINCE DE CHINE TAIWAN";
            break;
            case "TZ" :
                $country = "REPUBLIQUE-UNIE DE TANZANIE";
            break;
            case "TD" :
                $country = "TCHAD";
            break;
            case "CZ" :
                $country = "REPUBLIQUE TCHEQUE";
            break;
            case "TF" :
                $country = "TERRES AUSTRALES FRANCAISES";
            break;
            case "TH" :
                $country = "THAILANDE";
            break;
            case "TL" :
                $country = "TIMOR-LESTE";
            break;
            case "TG" :
                $country = "TOGO";
            break;
            case "TK" :
                $country = "TOKELAU";
            break;
            case "TO" :
                $country = "TONGA";
            break;
            case "TT" :
                $country = "TRINITE-ET-TOBAGO";
            break;
            case "TN" :
                $country = "TUNISIE";
            break;
            case "TM" :
                $country = "TURKMENISTAN";
            break;
            case "TC" :
                $country = "ILES TURKS ET CAIQUES";
            break;
            case "TR" :
                $country = "TURQUIE";
            break;
            case "TV" :
                $country = "TUVALU";
            break;
            case "UA" :
                $country = "UKRAINE";
            break;
            case "UY" :
                $country = "URUGUAY";
            break;
            case "VU" :
                $country = "VANUATU";
            break;
            case "VE" :
                $country = "REPUBLIQUE BOLIVARIENNE DU VENEZUELA";
            break;
            case "VN" :
                $country = "VIET NAM";
            break;
            case "WF" :
                $country = "WALLIS ET FUTUNA";
            break;
            case "YE" :
                $country = "YEMEN";
            break;
            case "ZM" :
                $country = "ZAMBIE";
            break;
            case "ZW" :
                $country = "ZIMBABWE";
            break;
        }


        return $country;
    }

    public function getCountry($codeIso){

        $country = $codeIso;
        
        switch($codeIso){
            case "AFG" :
                $country = "AFGHANISTAN";
            break;
            case "ZAF" :
                $country = "AFRIQUE DU SUD";
            break;
            case "ALA" :
                $country = "ÅLAND, ÎLES";
            break;
            case "ALB" :
                $country = "ALBANIE";
            break;
            case "DZA" :
                $country = "ALGERIE";
            break;
            case "DEU" :
                $country = "ALLEMAGNE";
            break;
            case "AND" :
                $country = "ANDORRE";
            break;
            case "AGO" :
                $country = "ANGOLA";
            break;
            case "AIA" :
                $country = "ANGUILLA";
            break;
            case "ATA" :
                $country = "ANTARCTIQUE";
            break;
            case "ATG" :
                $country = "ANTIGUA ET BARBUDA";
            break;
            case "SAU" :
                $country = "ARABIE SAOUDITE";
            break;
            case "ARG" :
                $country = "ARGENTINE";
            break;
            case "ARM" :
                $country = "ARMENIE";
            break;
            case "ABW" :
                $country = "ARUBA";
            break;
            case "AUS" :
                $country = "AUSTRALIE";
            break;
            case "AUT" :
                $country = "AUTRICHE";
            break;
            case "AZE" :
                $country = "AZERBAIDJAN";
            break;
            case "BHS" :
                $country = "BAHAMAS";
            break;
            case "BHR" :
                $country = "BAHREIN";
            break;
            case "BGD" :
                $country = "BANGLADESH";
            break;
            case "BRB" :
                $country = "BARBADE";
            break;
            case "BLY" :
                $country = "BIELORUSSIE";
            break;
            case "BEL" :
                $country = "BELGIQUE";
            break;
            case "BLZ" :
                $country = "BELIZE";
            break;
            case "BEN" :
                $country = "BENIN";
            break;
            case "BMU" :
                $country = "BERMUDES";
            break;
            case "BTN" :
                $country = "BHOUTAN";
            break;
            case "BOL" :
                $country = "ETAT PLURINATIONAL DE BOLIVIE";
            break;




            case "BQ" :
                $country = "SAINT-EUSTACHE ET SABA BONAIRE";
            break;




            case "BIH" :
                $country = "BOSNIE-HERZEGOVINE";
            break;
            case "BWA" :
                $country = "BOTSWANA";
            break;

            case "BVT" :
                $country = "L'ILE BOUVET";
            break;
            case "BRA" :
                $country = "BRESIL";
            break;
            case "BRN" :
                $country = "BRUNEI DARUSSALAM";
            break;
            case "BGR" :
                $country = "BULGARIE";
            break;
            case "BFA" :
                $country = "BURKINA FASO";
            break;
            case "BDI" :
                $country = "BURUNDI";
            break;
            case "CYM" :
                $country = "ILES CAIMANES";
            break;
            case "KHH" :
                $country = "CAMBODGE";
            break;
            case "CMR" :
                $country = "CAMEROUN";
            break;
            case "CAN" :
                $country = "CANADA";
            break;
            case "CPV" :
                $country = "CAP-VERT";
            break;
            case "CAF" :
                $country = "REPUBLIQUE CENTRAFRICAINE";
            break;
            case "ChL" :
                $country = "CHILI";
            break;
            case "CHN" :
                $country = "CHINE";
            break;
            case "CXR" :
                $country = "ILE CHRISTMAS";
            break;
            case "CYP" :
                $country = "CHYPRE";
            break;
            case "CCK" :
                $country = "ILES COCOS (KEELING)";
            break;
            case "COL" :
                $country = "COLOMBIE";
            break;
            case "COM" :
                $country = "COMORES";
            break;
            case "COG" :
                $country = "CONGO (Brazzaville)";
            break;
            case "COD" :
                $country = "LA REPUBLIQUE DEMOCRATIQUE DU CONGO";
            break;
            case "COK" :
                $country = "ILES COOK";
            break;
            case "KOR" :
                $country = "REPUBLIQUE DE COREE";
            break;
            case "PRK" :
                $country = "REPUBLIQUE POPULAIRE DEMOCRATIQUE DE COREE,";
            break;
            case "CRI" :
                $country = "COSTA RICA";
            break;
            case "CIV" :
                $country = "COTE D'IVOIRE";
            break;
            case "HRV" :
                $country = "CROATIE";
            break;
            case "CUB" :
                $country = "CUBA";
            break;




            case "CW" :
                $country = "CURACAO";
            break;




            case "DNK" :
                $country = "DANEMARK";
            break;
            case "DJI" :
                $country = "DJIBOUTI";
            break;
            case "DOM" :
                $country = "REPUBLIQUE DOMINICAINE";
            break;
            case "DMA" :
                $country = "DOMINIQUE";
            break;
            case "EGY" :
                $country = "EGYPTE";
            break;
            case "SVL" :
                $country = "EL SALVADOR";
            break;




            case "AE" :
                $country = "EMIRATS ARABES UNIS";
            break;




            case "ECU" :
                $country = "EQUATEUR";
            break;
            case "ERI" :
                $country = "ERYTHREE";
            break;
            case "ESP" :
                $country = "ESPAGNE";
            break;
            case "EST" :
                $country = "ESTONIE";
            break;
            case "USA" :
                $country = "ETATS-UNIS";
            break;
            case "ETH" :
                $country = "ETHIOPIE";
            break;
            case "FLK" :
                $country = "ILES FALKLAND (MALVINAS)";
            break;
            case "FRO" :
                $country = "ILES FEROE";
            break;
            case "FJI" :
                $country = "FIDJI";
            break;
            case "FIN" :
                $country = "FINLANDE";
            break;
            case "FRA" :
                $country = "FRANCE";
            break;
            case "GAB" :
                $country = "GABON";
            break;
            case "GMB" :
                $country = "GAMBIE";
            break;
            case "GEO" :
                $country = "GEORGIE";
            break;
            case "SGS" :
                $country = "GEORGIE DU SUD ET LES ILES SANDWICH DU SUD";
            break;
            case "GHA" :
                $country = "GHANA";
            break;
            case "GIB" :
                $country = "GIBRALTAR";
            break;
            case "GRC" :
                $country = "GRECE";
            break;
            case "GRD" :
                $country = "GRENADE";
            break;
            case "GRL" :
                $country = "GROENLAND";
            break;
            case "GLP" :
                $country = "GUADELOUPE";
            break;
            case "GUM" :
                $country = "GUAM";
            break;
            case "GTM" :
                $country = "GUATEMALA";
            break;
            case "GGY" :
                $country = "GUERNESEY";
            break;
            case "GIN" :
                $country = "GUINEE";
            break;
            case "GNB" :
                $country = "GUINEE-BISSAU";
            break;
            case "GNQ" :
                $country = "GUINEE EQUATORIALE";
            break;
            case "GUY" :
                $country = "GUYANA";
            break;
            case "GUF" :
                $country = "GUYANE FRANCAISE";
            break;
            case "HTI" :
                $country = "HAITI";
            break;
            case "HMD" :
                $country = "ILES HEARD ET MCDONALD";
            break;
            case "HND" :
                $country = "HONDURAS";
            break;
            case "HKG" :
                $country = "HONG KONG";
            break;
            case "HUN" :
                $country = "HONGRIE";
            break;
            case "IMN" :
                $country = "ILE DE MAN";
            break;
            case "UMI" :
                $country = "ILES MINEURES ELOIGNEES DES ETATS-UNIS";
            break;
            case "VGB" :
                $country = "ILES VIERGES BRITANNIQUES";
            break;
            case "VIR" :
                $country = "ILES VIERGES DES ETATS-UNIS";
            break;
            case "IND" :
                $country = "INDE";
            break;
            case "IDN" :
                $country = "INDONESIE";
            break;
            case "IRN" :
                $country = "REPUBLIQUE ISLAMIQUE D'IRAN";
            break;
            case "IRQ" :
                $country = "IRAQ";
            break;
            case "IRL" :
                $country = "IRLANDE";
            break;
            case "ISL" :
                $country = "ISLANDE";
            break;
            case "ISR" :
                $country = "ISRAEL";
            break;
            case "ITA" :
                $country = "ITALIE";
            break;
            case "JAM" :
                $country = "JAMAIQUE";
            break;
            case "JPN" :
                $country = "JAPON";
            break;
            case "JEY" :
                $country = "JERSEY";
            break;
            case "JOR" :
                $country = "JORDANIE";
            break;
            case "KAZ" :
                $country = "KAZAKHSTAN";
            break;
            case "KEN" :
                $country = "KENYA";
            break;
            case "KGZ" :
                $country = "KIRGHIZISTAN";
            break;
            case "KIR" :
                $country = "KIRIBATI";
            break;
            case "KWT" :
                $country = "KOWEIT";
            break;
            case "LAO" :
                $country = "REPUBLIQUE DEMOCRATIQUE POPULAIRE LAO";
            break;
            case "LSO" :
                $country = "LESOTHO";
            break;
            case "LVA" :
                $country = "LETTONIE";
            break;
            case "LBN" :
                $country = "LIBAN";
            break;
            case "LBR" :
                $country = "LIBERIA";
            break;
            case "LBY" :
                $country = "LIBYE";
            break;
            case "LIE" :
                $country = "LIECHTENSTEIN";
            break;
            case "LTU" :
                $country = "LITUANIE";
            break;
            case "LUX" :
                $country = "LUXEMBOURG";
            break;
            case "MAC" :
                $country = "MACAO";
            break;
            case "MKD" :
                $country = "REPUBLIQUE DE MACEDOINE";
            break;
            case "MDG" :
                $country = "MADAGASCAR";
            break;
            case "MYS" :
                $country = "MALAISIE";
            break;
            case "MWI" :
                $country = "MALAWI";
            break;
            case "MDV" :
                $country = "MALDIVES";
            break;
            case "MLI" :
                $country = "MALI";
            break;
            case "MLT" :
                $country = "MALTE";
            break;
            case "MNP" :
                $country = "ILES MARIANNES DU NORD";
            break;
            case "MAR" :
                $country = "MAROC";
            break;
            case "MHL" :
                $country = "ILES MARSHALL";
            break;
            case "MTQ" :
                $country = "MARTINIQUE";
            break;
            case "MUS" :
                $country = "MAURICE";
            break;
            case "MRT" :
                $country = "MAURITANIE";
            break;
            case "MYT" :
                $country = "MAYOTTE";
            break;
            case "MEX" :
                $country = "MEXIQUE";
            break;
            case "FSM" :
                $country = "ETATS FEDERES DE MICRONESIE";
            break;
            case "MDA" :
                $country = "MOLDAVIE";
            break;
            case "MC" :
                $country = "MCO";
            break;
            case "MNG" :
                $country = "MONGOLIE";
            break;
            case "MNE" :
                $country = "MONTENEGRO";
            break;
            case "MSR" :
                $country = "MONTSERRAT";
            break;
            case "MOZ" :
                $country = "MOZAMBIQUE";
            break;
            case "MMR" :
                $country = "MYANMAR";
            break;
            case "NAM" :
                $country = "NAMIBIE";
            break;
            case "NRU" :
                $country = "NAURU";
            break;
            case "NPL" :
                $country = "NEPAL";
            break;
            case "NIC" :
                $country = "NICARAGUA";
            break;
            case "NER" :
                $country = "NIGER";
            break;
            case "NGA" :
                $country = "NIGERIA";
            break;
            case "NIU" :
                $country = "NIUE";
            break;
            case "NFK" :
                $country = "ILE NORFOLK";
            break;
            case "NOR" :
                $country = "NORVEGE";
            break;
            case "NCL" :
                $country = "NOUVELLE-CALEDONIE";
            break;
            case "NZL" :
                $country = "NOUVELLE-ZELANDE";
            break;
            case "IOT" :
                $country = "TERRITOIRE BRITANNIQUE DE L'OCEAN INDIEN";
            break;
            case "OMN" :
                $country = "OMAN";
            break;
            case "UGA" :
                $country = "OUGANDA";
            break;
            case "UZB" :
                $country = "OUZBEKISTAN";
            break;
            case "PAK" :
                $country = "PAKISTAN";
            break;
            case "PLW" :
                $country = "PALAU";
            break;
            case "PSE" :
                $country = "TERRITOIRE PALESTINIEN OCCUPE";
            break;
            case "PAN" :
                $country = "PANAMA";
            break;
            case "PNG" :
                $country = "PAPOUASIE-NOUVELLE-GUINEE";
            break;
            case "PRY" :
                $country = "PARAGUAY";
            break;
            case "NLD" :
                $country = "PAYS-BAS";
            break;
            case "PER" :
                $country = "PEROU";
            break;
            case "PHL" :
                $country = "PHILIPPINES";
            break;
            case "PCN" :
                $country = "PITCAIRN";
            break;
            case "POL" :
                $country = "POLOGNE";
            break;
            case "PYF" :
                $country = "POLYNESIE FRANCAISE";
            break;
            case "PRI" :
                $country = "PUERTO RICO";
            break;
            case "PRT" :
                $country = "PORTUGAL";
            break;
            case "QAT" :
                $country = "QATAR";
            break;
            case "REU" :
                $country = "REUNION";
            break;
            case "ROU" :
                $country = "ROUMANIE";
            break;
            case "GBR" :
                $country = "ROYAUME-UNI";
            break;
            case "RUS" :
                $country = "FEDERATION DE RUSSIE";
            break;
            case "RWA" :
                $country = "RWANDA";
            break;
            case "ESH" :
                $country = "SAHARA OCCIDENTAL";
            break;
            case "BLM" :
                $country = "SAINT-BARTHELEMY";
            break;
            case "SHN" :
                $country = "SAINTE-HELENE";
            break;
            case "LCA" :
                $country = "SAINTE-LUCIE";
            break;
            case "KNA" :
                $country = "SAINT-KITTS-ET-NEVIS";
            break;
            case "SMR" :
                $country = "SAINT-MARIN";
            break;
            case "MAF" :
                $country = "SAINT-MARTIN (PARTIE FRANCAISE)";
            break;
            case "SX" :
                $country = "SAINT-MARTIN (PARTIE NEERLANDAISE)";
            break;
            case "SPM" :
                $country = "SAINT-PIERRE-ET-MIQUELON";
            break;
            case "VAT" :
                $country = "SAINT-SIEGE (ETAT DE LA CITE DU VATICAN)";
            break;
            case "VCT" :
                $country = "SAINT-VINCENT-ET-LES GRENADINES";
            break;
            case "SLB" :
                $country = "ILES SALOMON";
            break;
            case "WSM" :
                $country = "SAMOA";
            break;
            case "ASM" :
                $country = "SAMOA AMERICAINES";
            break;
            case "STP" :
                $country = "SAO TOME-ET-PRINCIPE";
            break;
            case "SEN" :
                $country = "SENEGAL";
            break;
            case "SRB" :
                $country = "SERBIE";
            break;
            case "SYC" :
                $country = "SEYCHELLES";
            break;
            case "SLE" :
                $country = "SIERRA LEONE";
            break;
            case "SGP" :
                $country = "SINGAPOUR";
            break;
            case "SVK" :
                $country = "SLOVAQUIE";
            break;
            case "SVN" :
                $country = "SLOVENIE";
            break;
            case "SOM" :
                $country = "SOMALIE";
            break;
            case "SDN" :
                $country = "SOUDAN";
            break;
            case "LKA" :
                $country = "SRI LANKA";
            break;
            case "SWE" :
                $country = "SUEDE";
            break;
            case "CHE" :
                $country = "SUISSE";
            break;
            case "SUR" :
                $country = "SURINAME";
            break;
            case "SJM" :
                $country = "SVALBARD ET ILE JAN MAYEN";
            break;
            case "SWZ" :
                $country = "SWAZILAND";
            break;
            case "SYR" :
                $country = "REPUBLIQUE ARABE SYRIENNE";
            break;
            case "TJK" :
                $country = "TADJIKISTAN";
            break;
            case "TWN" :
                $country = "TAIWAN";
            break;
            case "TZA" :
                $country = "TANZANIE";
            break;
            case "TCD" :
                $country = "TCHAD";
            break;
            case "CZE" :
                $country = "REPUBLIQUE TCHEQUE";
            break;
            case "ATF" :
                $country = "TERRES AUSTRALES FRANCAISES";
            break;
            case "THA" :
                $country = "THAILANDE";
            break;
            case "TLS" :
                $country = "TIMOR-LESTE";
            break;
            case "TGO" :
                $country = "TOGO";
            break;
            case "TKL" :
                $country = "TOKELAU";
            break;
            case "TON" :
                $country = "TONGA";
            break;
            case "TTO" :
                $country = "TRINITE-ET-TOBAGO";
            break;
            case "TUN" :
                $country = "TUNISIE";
            break;
            case "TKM" :
                $country = "TURKMENISTAN";
            break;
            case "TCA" :
                $country = "ILES TURKS ET CAIQUES";
            break;
            case "TUR" :
                $country = "TURQUIE";
            break;
            case "TUV" :
                $country = "TUVALU";
            break;
            case "UKR" :
                $country = "UKRAINE";
            break;
            case "URY" :
                $country = "URUGUAY";
            break;
            case "VUT" :
                $country = "VANUATU";
            break;
            case "VEN" :
                $country = "REPUBLIQUE BOLIVARIENNE DU VENEZUELA";
            break;
            case "VNM" :
                $country = "VIET NAM";
            break;
            case "WLF" :
                $country = "WALLIS ET FUTUNA";
            break;
            case "YEM" :
                $country = "YEMEN";
            break;
            case "ZMB" :
                $country = "ZAMBIE";
            break;
            case "ZWE" :
                $country = "ZIMBABWE";
            break;
        }


        return $country;
    }


    //Gestion des URLs

    public function redirectUrl($trackingId, $linkId){
        //trackingId devient transparent
        $link = $this->em->getRepository('AppBundle:Link')->findOneBy(array('id' => $linkId));

        if($link != null){
            $url            = $link->getUrl();
            $privateLinkId  = $link->getId();
        }
        else{
            $url = "#";
        }

        return "http://lncl_tracking/c/".$trackingId."/".$privateLinkId;
    }

    public function unsuscribeLink($trackingId){
        $desaboId = 100;
        return "http://lncl_tracking/c/".$trackingId."/".$desaboId;
    }
}