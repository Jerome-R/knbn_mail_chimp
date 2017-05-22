<?php
// src/OC/PlatformBundle/Antispam/OCAntispam.php

namespace Application\Sonata\HelperBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\ConfigurationRoute;
use Sensio\Bundle\FrameworkExtraBundle\ConfigurationTemplate;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Application\Sonata\HelperBundle\Helper\CSVTypes;

use Ddeboer\DataImport\Reader\CsvReader;
use Ddeboer\DataImport\Workflow;
use Ddeboer\DataImport\Writer\DoctrineWriter;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Doctrine\ORM\EntityManager;

use Application\Sonata\UserBundle\Entity\User;

class CSVController extends Controller
{
    private $em;
    private $separator;
    private $ip;
    private $db_host;
    private $db_name;
    private $db_user;
    private $db_password;

    public function importFileAction(Request $request) {

        $param=$request->request;

        $this->ip           = $this->getParameter('local_ip');
        $this->db_host      = $this->getParameter('database_host');
        $this->db_name      = $this->getParameter('database_name');
        $this->db_user      = $this->getParameter('database_user');
        $this->db_password  = $this->getParameter('database_password');
        $this->separator    = $param->get('separator');

        $date = new \DateTime();
        $created_at = $date;

        $em = $this->getDoctrine()->getManager();
    
        //Set DB connexion
        try
        {
            $pdo = new \PDO('mysql:host='.$this->db_host.';dbname='.$this->db_name.';charset=utf8', $this->db_user, $this->db_password);
        }
        catch(Exception $e)
        {       
            die('Erreur : '.$e->getMessage());
        }

        gc_enable();
        $em->getConnection()->getConfiguration()->setSQLLogger(null);

        $sql2 = "UPDATE app_recipient_adoc SET adoc_id = NULL WHERE 1";
        $stmt2 = $pdo->prepare($sql2);
        $stmt2->execute();

        $sql3 = "DELETE FROM app_adoc WHERE 1";
        $stmt3 = $pdo->prepare($sql3);
        $stmt3->execute();

        /*if($this->ip == "127.0.0.1")
        {
            //$file = fopen('D:\wamp\www\StoreApp\web\imports\lancel_ciblage_ad_hoc_PRCaen_clienteling_20160908.csv', "r");
            $file = fopen('D:\wamp\www\StoreApp\web\imports\lancel_ciblage_ad_hoc_'.$csv.'.csv', "r");
        }
        else{
            
            //$file = fopen('/data/ftp/imports/lancel_ciblage_ad_hoc_PRCaen_clienteling_20160908.csv', "r");
            $file = fopen('/data/ftp/imports/lancel_ciblage_ad_hoc_'.$csv.'.csv', "r");
        }*/
        // Get FileId to "import"
        $fileId=(int)trim($param->get("fileId"));

        $curType=trim($param->get("fileType"));
        $uploadedFile=$request->files->get("csvFile");

        // generate dummy dir
        //$import=getcwd()."/sonataAdminImport";
        $fname="import.csv";
        $filename=$import."/".$fname;
        //@mkdir($import);
        @unlink($filename);

        //$uploadedFile->move($import,$fname);  
        $file = fopen($filename, "r");
        
        $sql = "REPLACE INTO app_client_adoc ( id_client, nom, prenom, civilite, store, email, phone1, phone2, local, country, city, postal_code, adress1, adress2, adress3, nationality, ca_3_years, ca_12_months, frequence_3_years, frequence_12_months, max_price_3_years, max_price_12_months, prix_max_article_histo, pm_histo, date_1erachat, date_dernachat, segment, vendor_code, created_at) 
                VALUES ( :id_client, :nom, :prenom, :civilite, :store, :email, :phone1, :phone2, :local, :country, :city, :postal_code, :adress1, :adress2, :adress3, :nationality, :ca_3_years, :ca_12_months, :frequence_3_years, :frequence_12_months, :max_price_3_years, :max_price_12_months, :prix_max_article_histo, :pm_histo, :date_1erachat, :date_dernachat, :segment,:vendor_code, :created_at)";


        $sql3 = "REPLACE INTO app_adoc (id_client, id_campagne, date_entree, canal, code_uvc, sku_desc, genre_desc, ligne_desc, prix_paye)
                VALUES (:id_client, :id_campagne, :date_entree, :canal, :code_uvc, :sku_desc, :genre_desc, :ligne_desc, :prix_paye)";

        //, vide_1, vide_2, vide_3, vide_4, vide_5, vide_6, vide_7, vide_8, vide_9, vide_10)
        //, :vide_1, :vide_2, :vide_3, :vide_4, :vide_5, :vide_6, :vide_7, :vide_8, :vide_9, :vide_10)";
        /*
        $sql4 = "UPDATE app_top_client SET (date_entree, ...)
                VALUES (:date_entree, ...) WHERE id_client = :id_client ";
        */

        $i = 0;
        $flag = true;

        while( ($csvfilelines = fgetcsv($file, 0, $this->separator)) != FALSE )
        {
            if($flag) { $flag = false; continue; } //ignore first line of csv

            $stmt = $pdo->prepare($sql);
            $stmt3 = $pdo->prepare($sql3);
            //$stmt4 = $pdo->prepare($sql4);
            //idclient|idcampagne|dateentree|canalclienteling|nom|prenom|civilite|boutiqueachat|email|telephone1|telephone2|local|pays|ville|codepostal|adresse1
            //|adresse2|adresse3|nationalite|ca3ans|ca12mois|freq3ans|freq12mois|pmax3ans|pmax12mois|pmaxhisto|pmhisto|date1erachat|datedernierachat|segment|
            //codeuvc|skudesc|genredesc|lignedesc|prixpaye|codevendeur|vide1|vide2|vide3|vide4|vide5|vide6|vide7|vide8|vide9|vide10            

            $stmt->bindValue(':id_client', $csvfilelines[0], \PDO::PARAM_STR);
            $stmt3->bindValue(':id_client', $csvfilelines[0], \PDO::PARAM_STR);
            //$stmt4->bindValue(':id_client', $csvfilelines[0], \PDO::PARAM_STR);
            $stmt3->bindValue(':id_campagne', $csvfilelines[1], \PDO::PARAM_STR);
            $stmt3->bindValue(':date_entree', $csvfilelines[2], \PDO::PARAM_STR);
            //$stmt4->bindValue(':date_entree', $csvfilelines[1], \PDO::PARAM_STR);
            $stmt3->bindValue(':canal', $csvfilelines[3], \PDO::PARAM_STR);
            $stmt->bindValue(':nom', $csvfilelines[4], \PDO::PARAM_STR);
            $stmt->bindValue(':prenom', $csvfilelines[5], \PDO::PARAM_STR);
            $stmt->bindValue(':civilite', $csvfilelines[6], \PDO::PARAM_STR);
            $stmt->bindValue(':store', $csvfilelines[7], \PDO::PARAM_STR);
            $stmt->bindValue(':email', $csvfilelines[8], \PDO::PARAM_STR);
            $stmt->bindValue(':phone1', $csvfilelines[9], \PDO::PARAM_STR);
            $stmt->bindValue(':phone2', $csvfilelines[10], \PDO::PARAM_STR);
            $stmt->bindValue(':local', $csvfilelines[11], \PDO::PARAM_STR);
            $stmt->bindValue(':country', $csvfilelines[12], \PDO::PARAM_STR);
            $stmt->bindValue(':city', $csvfilelines[13], \PDO::PARAM_STR);
            $stmt->bindValue(':postal_code', $csvfilelines[14], \PDO::PARAM_STR);
            $stmt->bindValue(':adress1', $csvfilelines[15], \PDO::PARAM_STR);
            $stmt->bindValue(':adress2', $csvfilelines[16], \PDO::PARAM_STR);
            $stmt->bindValue(':adress3', $csvfilelines[17], \PDO::PARAM_STR);
            $stmt->bindValue(':nationality', $csvfilelines[18], \PDO::PARAM_STR);
            $stmt->bindValue(':ca_3_years', $csvfilelines[19], \PDO::PARAM_INT);
            $stmt->bindValue(':ca_12_months', $csvfilelines[20], \PDO::PARAM_INT);
            $stmt->bindValue(':frequence_3_years', $csvfilelines[21], \PDO::PARAM_INT);
            $stmt->bindValue(':frequence_12_months', $csvfilelines[22], \PDO::PARAM_INT);
            $stmt->bindValue(':max_price_3_years', $csvfilelines[23], \PDO::PARAM_INT);
            $stmt->bindValue(':max_price_12_months', $csvfilelines[24], \PDO::PARAM_INT);
            $stmt->bindValue(':prix_max_article_histo', $csvfilelines[25], \PDO::PARAM_INT);
            $stmt->bindValue(':pm_histo', $csvfilelines[26], \PDO::PARAM_INT);
            $stmt->bindValue(':date_1erachat', $csvfilelines[27], \PDO::PARAM_STR);
            $stmt->bindValue(':date_dernachat', $csvfilelines[28], \PDO::PARAM_STR);
            $stmt->bindValue(':segment', $csvfilelines[29], \PDO::PARAM_STR);
            $stmt3->bindValue(':code_uvc', $csvfilelines[30], \PDO::PARAM_STR);
            $stmt3->bindValue(':sku_desc', $csvfilelines[31], \PDO::PARAM_STR);
            $stmt3->bindValue(':genre_desc', $csvfilelines[32], \PDO::PARAM_STR);
            $stmt3->bindValue(':ligne_desc', $csvfilelines[33], \PDO::PARAM_STR);
            $stmt3->bindValue(':prix_paye', $csvfilelines[34], \PDO::PARAM_INT);
            $stmt->bindValue(':vendor_code', $csvfilelines[35], \PDO::PARAM_STR);
            /*$stmt3->bindValue(':vide_1', $csvfilelines[36], \PDO::PARAM_STR);
            $stmt3->bindValue(':vide_2', $csvfilelines[37], \PDO::PARAM_STR);
            $stmt3->bindValue(':vide_3', $csvfilelines[38], \PDO::PARAM_STR);
            $stmt3->bindValue(':vide_4', $csvfilelines[39], \PDO::PARAM_STR);
            $stmt3->bindValue(':vide_5', $csvfilelines[40], \PDO::PARAM_STR);
            $stmt3->bindValue(':vide_6', $csvfilelines[41], \PDO::PARAM_STR);
            $stmt3->bindValue(':vide_7', $csvfilelines[42], \PDO::PARAM_STR);
            $stmt3->bindValue(':vide_8', $csvfilelines[43], \PDO::PARAM_STR);
            $stmt3->bindValue(':vide_9', $csvfilelines[44], \PDO::PARAM_STR);
            $stmt3->bindValue(':vide_10', $csvfilelines[45], \PDO::PARAM_STR);*/
            $stmt->bindValue(':created_at', $created_at->format('Y-m-d H:i:s'), \PDO::PARAM_STR);

            $stmt->execute();
            $stmt3->execute();
            //$stmt4->execute();

            $i++;
        }

        //End update ClientAdoc and Adoc
        
        //Update Recipient Adoc
        
        $batchSize = 100;
        $loop = 0;

        //$clients = $em->getRepository('AppBundle:Client')->getClientsWithCampaignId();
        $q = $em->createQuery('select a from AppBundle:Adoc a');
        $triggers = $q->iterate();


        //For each Client
        while (($row = $triggers->next()) !== false) { 
            $adoc = $row[0];

            //Get Campaign that match with campaignId
            $campaign = $em->getRepository('AppBundle:CampaignAdoc')->findOneBy(array( 'idCampaignName' =>  $adoc->getIdCampagne() ));     
            $client = $em->getRepository('AppBundle:ClientAdoc')->findOneBy(array( 'idClient' =>  $adoc->getIdClient() ));       

            //If Campaign IS NOT NULL
            if($campaign) {

                $trigger_id = $adoc->getId();
                $client_id = $client->getId();
                $campaign_id = $campaign->getId();

                $sql2 = "UPDATE `app_recipient_adoc` SET `adoc_id` = :id_adoc WHERE `client_id` = :client_id AND `campaign_id` = :id_campagne";
                $stmt2 = $pdo->prepare($sql2);
                $stmt2->bindValue(':id_adoc', $trigger_id, \PDO::PARAM_INT);
                $stmt2->bindValue(':client_id', $client_id, \PDO::PARAM_STR);
                $stmt2->bindValue(':id_campagne', $campaign_id, \PDO::PARAM_INT);
                $stmt2->execute();
                
                //Get Recipient
                $recipient = $em->getRepository('AppBundle:RecipientAdoc')->findOneBy(array('campaign' => $campaign, 'client' => $client));
                
                //If Recipient DO NOT EXIST
                if ($recipient == null){
                    //CREATE Recipient
                    $recipients[$loop] = new RecipientAdoc();

                    $canal = $adoc->getCanal();
                    if ($adoc->getCanal() == 'email'){
                        $canal = "Email";
                    }elseif ($adoc->getCanal() == 'print'){
                        $canal = "Mail";
                    }

                    $recipients[$loop]->setChannel($canal);

                    $recipients[$loop]->setCampaign($campaign);
                    $recipients[$loop]->setClient($client);
                    $recipients[$loop]->setAdoc($adoc);

                    $em->persist( $recipients[$loop] );
                }
            }

            if (($loop % $batchSize) === 0) {
                $em->flush(); // Executes all deletions.
                $em->clear(); // Detaches all objects from Doctrine!
                gc_collect_cycles();
            }
            $loop++;
        }
        $em->flush(); // Executes all deletions.
        $em->clear(); // Detaches all objects from Doctrine!
        gc_collect_cycles();


        //End Update Recipient

        //Update User field

        $batchSize = 20;
        $i = 0;

        //$recipients = $em->getRepository('AppBundle:Recipient')->findAll();
        $q = $em->createQuery('select r from AppBundle:RecipientAdoc r');

        $recipients = $q->iterate();

        foreach ($recipients as $row) {
            $user = $em->getRepository('ApplicationSonataUserBundle:User')->findOneBy( array( 'username' => $row[0]->getClient()->getLibelleBoutiqueRattachement() ) );

            $row[0]->setUser($user);

            if (($i % $batchSize) === 0) {
                $em->flush(); // Executes all deletions.
                $em->clear(); // Detaches all objects from Doctrine!
                gc_collect_cycles();
            }
            $i++;
        }

        $em->flush();
        $em->clear(); // Detaches all objects from Doctrine!
        gc_collect_cycles();
        

        //End update UserField

        //Update Client Field        

        $batchSize = 20;
        $i = 0;

        $q = $em->createQuery('select a from AppBundle:Adoc a');

        $adoc = $q->iterate();

        foreach ($adoc as $row) {
            $client = $em->getRepository('AppBundle:ClientAdoc')->findOneBy( array( 'idClient' => $row[0]->getIdClient() ) );

            $row[0]->setClient($client);

            if (($i % $batchSize) === 0) {
                $em->flush(); // Executes all deletions.
                $em->clear(); // Detaches all objects from Doctrine!
                gc_collect_cycles();
            }
            $i++;
        }

        $em->flush();
        $em->clear(); // Detaches all objects from Doctrine!
        gc_collect_cycles();

        return $this->render('ApplicationSonataHelperBundle:CSV:csv_import.html.twig');
    }

}