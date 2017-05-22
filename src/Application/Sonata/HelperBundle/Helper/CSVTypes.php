<?php
// Fungus/ShortyBundle/Helper/CSVTypes.php
namespace Application\Sonata\HelperBundle\Helper;

class CSVTypes {
    #const User          = 0;
    #const Campaign      = 1;
    #const Client        = 2;
    #const Recipient     = 3;
    #const TopClient     = 4;
    #const KpiCapture    = 5;
    #const Import        = 6;
    #const ImportFile    = 7;
    #const Ticket        = 8;
    #const LigneVente    = 9;
    const ClientAdoc     = 0;

    public static function getTypes() {
        return array(
                //self::User          => 'User',
                //self::Campaign      => 'Campaign',
                //self::Client        => 'Client',
                //self::Recipient     => 'Recipient',
                //self::TopClient     => 'TopClient',
                //self::KpiCapture    => 'KpiCapture',
                //self::Import        => 'Import',
                //self::ImportFile    => 'ImportFile',
                //self::Ticket        => 'Ticket',
                //self::LigneVente    => 'LigneVente',
                self::ClientAdoc      => 'ClientAdoc',

        );
    }

    public static function getTypesAndIds() {
        
        $all=self::getTypes();
        $return=array();
        
        foreach($all as $key => $value) {
            $return[]=array("id" => $key, "title" => $value);
        }

        return $return;
    }

    public static function getNameOfType($type) {
        
        $allTypes=self::getTypes();
        
        if (isset($allTypes[$type])) return $allTypes[$type];
        
        return "- Unknown Type -";
    }

    public static function getEntityClass($type) {
        
        switch ($type) {
            //case self::User:            return "ApplicationSonataUserBundle:User";
            //case self::Campaign:        return "AppBundle:Campaign";
            //case self::Client:          return "AppBundle:Client";
            //case self::Recipient:       return "AppBundle:Recipient";
            //case self::TopClient:       return "AppBundle:TopClient";
            //case self::KpiCapture:      return "AppBundle:KpiCapture";
            //case self::Import:          return "AppBundle:Import";
            //case self::ImportFile:      return "AppBundle:ImportFile";
            //case self::Ticket:          return "AppBundle:Ticket";
            //case self::LigneVente:      return "AppBundle:LigneVente";
            case self::ClientAdoc:        return "AppBundle:ClientAdoc";
            default: return false;
        }
    }

    public static function existsType($type) {
        
        $allTypes=self::getTypes();
        
        if (isset($allTypes[$type])) return true;
        
        return false;
    }

}
