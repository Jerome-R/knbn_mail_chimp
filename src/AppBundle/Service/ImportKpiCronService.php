<?php

namespace AppBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ImportKpiCronService
{
    private $separator;
    private $filesList;
    private $ip;
    private $pdo;
    private $container;


    public function __construct($local_ip, ContainerInterface $container)
    {
        $this->ip = $local_ip;
        $this->container = $container;

        $this->pdo = $this->container->get('app.pdo_connect');
        $this->pdo = $this->pdo->initPdoClienteling();
    }

    public function setSeparator($separator) 
    {
        $this->separator = $separator;
    }

    public function scanDir(){

        if($this->ip == "127.0.0.1")
        {
            $this->filesList = scandir("D:\wamp\www\StoreApp\web\imports\kpis");
        }
        else{
            $this->filesList = scandir("/data/ftp/imports/kpis");
        }

        return $this->filesList;
    }

    //////////////////////////////////////////
    public function importKpiTriggerCSVFile(InputInterface $input, OutputInterface $output)
    {        
       
        gc_enable();

        $date = new \DateTime();
        $date = $date->format("Ymd");

       if($this->ip == "127.0.0.1")
        {
            $file = fopen("D:\\wamp\\www\\StoreApp\\web\\imports\\kpis\\lancel_tdb_boutiques_trigger_".$date.".csv", "r");
        }
        else{
            $file = fopen("/data/ftp/imports/kpis/lancel_tdb_boutiques_trigger_".$date.".csv", "r");
        }

        //colonnes du la requete à mettre à jour
        $header = "user_id,code_boutique_vendeur,point_vente_desc,niveau,date,nb_cli_tocontact_trigger_AA,nb_cli_contact_trigger_AA,pct_cli_contact_trigger_AA,nb_cli_tocontact_trigger_WB,nb_cli_contact_trigger_WB,pct_cli_contact_trigger_WB,nb_cli_tocontact_trigger_WP,nb_cli_contact_trigger_WP,pct_cli_contact_trigger_WP,nb_cli_tocontact_trigger_WS,nb_cli_contact_trigger_WS,pct_cli_contact_trigger_WS";
        //valeurs de la requête (correspond au header du fichier)
        $values = ":".str_replace(",", ",:", $header);
        $values = str_replace(":user_id,", "", $values);
        //tableau des headers à mettre à jours pour la boucle
        $headers = explode(",", str_replace("user_id,", "", $header));
        $update = "";
        $i = 0;
        $len = count($headers);

        foreach ($headers as $key => $value) {
            if ($i == $len - 1) $update .= $value." = :".$value;
            else $update .= $value." = :".$value.",";
            $i++;
        }

        $sql = "INSERT INTO app_kpi_trigger ( ".$header." ) 
                VALUES (  (SELECT id from fos_user_user u WHERE u.libelle = :libelle), ".$values.")
                ON DUPLICATE KEY UPDATE ".$update."";
        $i = 0;
        $flag = true;

        while( ($csvfilelines = fgetcsv($file, 0, $this->separator)) != FALSE )
        {
            if($flag) { $flag = false; continue; } //ignore first line of csv             
            
            $stmt = $this->pdo->prepare($sql);

            foreach ($headers as $key => $col) {
                $stmt->bindValue(':'.$col, $csvfilelines[$key], \PDO::PARAM_STR);
            }

            $stmt->bindValue(':libelle', $csvfilelines[1], \PDO::PARAM_STR);

            $stmt->execute();

            if($i % 20 == 0){
                $output->writeln($i." lignes importees");
            }
            $i++;
        }
        $output->writeln($i." lignes importees");
    }


    public function importKpiCaptureCSVFile( InputInterface $input, OutputInterface $output, $csv = null)
    {        
        $date = new \DateTime();
        $date = $date->format("Ymd");

        if($csv == null)
        {
            if($this->ip == "127.0.0.1")
            {
                $file = fopen("D:\\wamp\\www\\StoreApp\\web\\imports\\kpis\\lancel_tdb_boutiques_capture_".$date.".csv", "r");
            }
            else{
                $file = fopen("/data/ftp/imports/kpis/lancel_tdb_boutiques_capture_".$date.".csv", "r");
            }
        }
        else
        {
            if($this->ip == "127.0.0.1")
            {
                $file = fopen("D:\\wamp\\www\\StoreApp\\web\\imports\\kpis\\".$csv."", "r");
            }
            else{
                $file = fopen("/data/ftp/imports/kpis/".$csv."", "r");
            }  
        }

               
        $header = "user_id,code_boutique_vendeur,point_vente_desc,niveau,date,nb_cli_m_l,pct_cli_coord_valid_m_l,pct_cli_coord_nonvalid_m_l,pct_cli_coord_nonrens_m_l,pct_cli_email_valid_m_l,pct_cli_email_nonvalid_m_l,pct_cli_email_nonrens_m_l,pct_cli_tel_valid_m_l,pct_cli_tel_nonvalid_m_l,pct_cli_tel_nonrens_m_l,pct_cli_add_valid_m_l,pct_cli_add_nonvalid_m_l,pct_cli_add_nonrens_m_l,nb_cli_actifs_m_l,nb_cli_actifs_new_m_l,nb_cli_actifs_exist_m_l,nb_prosp_m_l,pct_prosp_coord_valid_m_l,pct_prosp_coord_nonvalid_m_l,pct_prosp_coord_nonrens_m_l,pct_prosp_email_valid_m_l,pct_prosp_email_nonvalid_m_l,pct_prosp_email_nonrens_m_l,pct_prosp_tel_valid_m_l,pct_prosp_tel_nonvalid_m_l,pct_prosp_tel_nonrens_m_l,pct_prosp_add_valid_m_l,pct_prosp_add_nonvalid_m_l,pct_prosp_add_nonrens_m_l,nb_cli_m_nl,pct_cli_coord_valid_m_nl,pct_cli_coord_nonvalid_m_nl,pct_cli_coord_nonrens_m_nl,pct_cli_email_valid_m_nl,pct_cli_email_nonvalid_m_nl,pct_cli_email_nonrens_m_nl,pct_cli_tel_valid_m_nl,pct_cli_tel_nonvalid_m_nl,pct_cli_tel_nonrens_m_nl,pct_cli_add_valid_m_nl,pct_cli_add_nonvalid_m_nl,pct_cli_add_nonrens_m_nl,nb_cli_actifs_m_nl,nb_cli_actifs_new_m_nl,nb_cli_actifs_exist_m_nl,nb_prosp_m_nl,pct_prosp_coord_valid_m_nl,pct_prosp_coord_nonvalid_m_nl,pct_prosp_coord_nonrens_m_nl,pct_prosp_email_valid_m_nl,pct_prosp_email_nonvalid_m_nl,pct_prosp_email_nonrens_m_nl,pct_prosp_tel_valid_m_nl,pct_prosp_tel_nonvalid_m_nl,pct_prosp_tel_nonrens_m_nl,pct_prosp_add_valid_m_nl,pct_prosp_add_nonvalid_m_nl,pct_prosp_add_nonrens_m_nl,nb_cli_y_l,pct_cli_coord_valid_y_l,pct_cli_coord_nonvalid_y_l,pct_cli_coord_nonrens_y_l,pct_cli_email_valid_y_l,pct_cli_email_nonvalid_y_l,pct_cli_email_nonrens_y_l,pct_cli_tel_valid_y_l,pct_cli_tel_nonvalid_y_l,pct_cli_tel_nonrens_y_l,pct_cli_add_valid_y_l,pct_cli_add_nonvalid_y_l,pct_cli_add_nonrens_y_l,nb_email_y_l,nb_tel_y_l,nb_adr_y_l,nb_cli_actifs_y_l,nb_cli_actifs_new_y_l,nb_cli_actifs_exist_y_l,pct_cli_donnees_nonvalid_y_l,nb_email_nonvalid_y_l,nb_tel_nonvalid_y_l,nb_adr_nonvalid_y_l,nb_prosp_y_l,pct_prosp_coord_valid_y_l,pct_prosp_coord_nonvalid_y_l,pct_prosp_coord_nonrens_y_l,pct_prosp_email_valid_y_l,pct_prosp_email_nonvalid_y_l,pct_prosp_email_nonrens_y_l,pct_prosp_tel_valid_y_l,pct_prosp_tel_nonvalid_y_l,pct_prosp_tel_nonrens_y_l,pct_prosp_add_valid_y_l,pct_prosp_add_nonvalid_y_l,pct_prosp_add_nonrens_y_l,nb_cli_y_nl,pct_cli_coord_valid_y_nl,pct_cli_coord_nonvalid_y_nl,pct_cli_coord_nonrens_y_nl,pct_cli_email_valid_y_nl,pct_cli_email_nonvalid_y_nl,pct_cli_email_nonrens_y_nl,pct_cli_tel_valid_y_nl,pct_cli_tel_nonvalid_y_nl,pct_cli_tel_nonrens_y_nl,pct_cli_add_valid_y_nl,pct_cli_add_nonvalid_y_nl,pct_cli_add_nonrens_y_nl,nb_email_y_nl,nb_tel_y_nl,nb_adr_y_nl,nb_cli_actifs_y_nl,nb_cli_actifs_new_y_nl,nb_cli_actifs_exist_y_nl,pct_cli_donnees_nonvalid_y_nl,nb_email_nonvalid_y_nl,nb_tel_nonvalid_y_nl,nb_adr_nonvalid_y_nl,nb_prosp_y_nl,pct_prosp_coord_valid_y_nl,pct_prosp_coord_nonvalid_y_nl,pct_prosp_coord_nonrens_y_nl,pct_prosp_email_valid_y_nl,pct_prosp_email_nonvalid_y_nl,pct_prosp_email_nonrens_y_nl,pct_prosp_tel_valid_y_nl,pct_prosp_tel_nonvalid_y_nl,pct_prosp_tel_nonrens_y_nl,pct_prosp_add_valid_y_nl,pct_prosp_add_nonvalid_y_nl,pct_prosp_add_nonrens_y_nl,nb_trans_linked_y,nb_trans_local_y,pct_trans_local_y,nb_trans_nlocal_y,pct_trans_nlocal_y,nb_trans_not_linked_y,pct_trans_not_linked_y,nb_trans_tot_y,nb_trans_linked_m,nb_trans_local_m,pct_trans_local_m,nb_trans_nlocal_m,pct_trans_nlocal_m,nb_trans_not_linked_m,pct_trans_not_linked_m,nb_trans_tot_m,nb_optin_y_l,nb_optout_y_l,pct_optin_y_l,pct_optout_y_l,nb_cli_coord_valid_y_l,nb_cli_coord_nonvalid_y_l,nb_cli_coord_nonrens_y_l,nb_cli_email_valid_y_l,nb_cli_email_nonvalid_y_l,nb_cli_email_nonrens_y_l,nb_cli_tel_valid_y_l,nb_cli_tel_nonvalid_y_l,nb_cli_tel_nonrens_y_l,nb_cli_add_valid_y_l,nb_cli_add_nonvalid_y_l,nb_cli_add_nonrens_y_l,nb_prosp_coord_valid_y_l,nb_prosp_coord_nonvalid_y_l,nb_prosp_coord_nonrens_y_l,nb_prosp_email_valid_y_l,nb_prosp_email_nonvalid_y_l,nb_prosp_email_nonrens_y_l,nb_prosp_tel_valid_y_l,nb_prosp_tel_nonvalid_y_l,nb_prosp_tel_nonrens_y_l,nb_prosp_add_valid_y_l,nb_prosp_add_nonvalid_y_l,nb_prosp_add_nonrens_y_l,nb_optin_y_nl,nb_optout_y_nl,pct_optin_y_nl,pct_optout_y_nl,nb_cli_coord_valid_y_nl,nb_cli_coord_nonvalid_y_nl,nb_cli_coord_nonrens_y_nl,nb_cli_email_valid_y_nl,nb_cli_email_nonvalid_y_nl,nb_cli_email_nonrens_y_nl,nb_cli_tel_valid_y_nl,nb_cli_tel_nonvalid_y_nl,nb_cli_tel_nonrens_y_nl,nb_cli_add_valid_y_nl,nb_cli_add_nonvalid_y_nl,nb_cli_add_nonrens_y_nl,nb_prosp_coord_valid_y_nl,nb_prosp_coord_nonvalid_y_nl,nb_prosp_coord_nonrens_y_nl,nb_prosp_email_valid_y_nl,nb_prosp_email_nonvalid_y_nl,nb_prosp_email_nonrens_y_nl,nb_prosp_tel_valid_y_nl,nb_prosp_tel_nonvalid_y_nl,nb_prosp_tel_nonrens_y_nl,nb_prosp_add_valid_y_nl,nb_prosp_add_nonvalid_y_nl,nb_prosp_add_nonrens_y_nl,nb_optin_m_l,nb_optout_m_l,pct_optin_m_l,pct_optout_m_l,nb_cli_coord_valid_m_l,nb_cli_coord_nonvalid_m_l,nb_cli_coord_nonrens_m_l,nb_cli_email_valid_m_l,nb_cli_email_nonvalid_m_l,nb_cli_email_nonrens_m_l,nb_cli_tel_valid_m_l,nb_cli_tel_nonvalid_m_l,nb_cli_tel_nonrens_m_l,nb_cli_add_valid_m_l,nb_cli_add_nonvalid_m_l,nb_cli_add_nonrens_m_l,nb_prosp_coord_valid_m_l,nb_prosp_coord_nonvalid_m_l,nb_prosp_coord_nonrens_m_l,nb_prosp_email_valid_m_l,nb_prosp_email_nonvalid_m_l,nb_prosp_email_nonrens_m_l,nb_prosp_tel_valid_m_l,nb_prosp_tel_nonvalid_m_l,nb_prosp_tel_nonrens_m_l,nb_prosp_add_valid_m_l,nb_prosp_add_nonvalid_m_l,nb_prosp_add_nonrens_m_l,nb_optin_m_nl,nb_optout_m_nl,pct_optin_m_nl,pct_optout_m_nl,nb_cli_coord_valid_m_nl,nb_cli_coord_nonvalid_m_nl,nb_cli_coord_nonrens_m_nl,nb_cli_email_valid_m_nl,nb_cli_email_nonvalid_m_nl,nb_cli_email_nonrens_m_nl,nb_cli_tel_valid_m_nl,nb_cli_tel_nonvalid_m_nl,nb_cli_tel_nonrens_m_nl,nb_cli_add_valid_m_nl,nb_cli_add_nonvalid_m_nl,nb_cli_add_nonrens_m_nl,nb_prosp_coord_valid_m_nl,nb_prosp_coord_nonvalid_m_nl,nb_prosp_coord_nonrens_m_nl,nb_prosp_email_valid_m_nl,nb_prosp_email_nonvalid_m_nl,nb_prosp_email_nonrens_m_nl,nb_prosp_tel_valid_m_nl,nb_prosp_tel_nonvalid_m_nl,nb_prosp_tel_nonrens_m_nl,nb_prosp_add_valid_m_nl,nb_prosp_add_nonvalid_m_nl,nb_prosp_add_nonrens_m_nl,nb_prosp_optout_y_l,nb_prosp_optout_y_nl,nb_prosp_optout_m_l,nb_prosp_optout_m_nl,pct_prosp_optout_y_l,pct_prosp_optout_y_nl,pct_prosp_optout_m_l,pct_prosp_optout_m_nl";
        
        //valeurs de la requête (correspond au header du fichier)
        $values = ":".str_replace(",", ",:", $header);
        $values = str_replace(":user_id,", "", $values);
        //tableau des headers à mettre à jours pour la boucle
        $headers = explode(",", str_replace("user_id,", "", $header));
        $update = "";
        $i = 0;
        $len = count($headers);

        foreach ($headers as $key => $value) {
            if ($i == $len - 1) $update .= $value." = :".$value;
            else $update .= $value." = :".$value.",";
            $i++;
        }

        $sql = "INSERT INTO app_kpi_capture ( ".$header." ) VALUES (  (SELECT id from fos_user_user u WHERE u.libelle = :libelle) , ".$values.")
                ON DUPLICATE KEY UPDATE ".$update."
        "; 

        $i = 0;
        $flag = true;

        while( ($csvfilelines = fgetcsv($file, 0, $this->separator)) != FALSE )
        {
            if($flag) { $flag = false; continue; } //ignore first line of csv             
            
            $stmt = $this->pdo->prepare($sql);

            foreach ($headers as $key => $col) {
                $stmt->bindValue(':'.$col, $csvfilelines[$key], \PDO::PARAM_STR);
            }


            $stmt->bindValue(':libelle', $csvfilelines[1], \PDO::PARAM_STR);
            $stmt->execute();

            if($i % 20 == 0){
                $output->writeln($i." lignes importees");
                gc_collect_cycles();
            }
            $i++;
            //die();
        }
        $output->writeln($i." lignes importees");
        
    }

    public function moveUploadedFile()
    {
        $date = new \DateTime();
        $date = $date->format("Ymd");

        if($this->ip == "127.0.0.1")
        {
            rename ("D:\\wamp\\www\\StoreApp\\web\\imports\\kpis\\lancel_tdb_boutiques_trigger_".$date.".csv" , "D:\\wamp\\www\\StoreApp\\web\\imports\\kpis\\archives\\lancel_tdb_boutiques_trigger_".$date.".csv");
            rename ("D:\\wamp\\www\\StoreApp\\web\\imports\\kpis\\lancel_tdb_boutiques_capture_".$date.".csv" , "D:\\wamp\\www\\StoreApp\\web\\imports\\kpis\\archives\\lancel_tdb_boutiques_capture_".$date.".csv");
        }
        else{
            rename ("/data/ftp/imports/kpis/lancel_tdb_boutiques_trigger_".$date.".csv" , "/data/ftp/imports/kpis/archives/lancel_tdb_boutiques_trigger_".$date.".csv");
            rename ("/data/ftp/imports/kpis/lancel_tdb_boutiques_capture_".$date.".csv" , "/data/ftp/imports/kpis/archives/lancel_tdb_boutiques_capture_".$date.".csv");
        }
    }

}