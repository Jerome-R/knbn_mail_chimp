<?php

// src/Fungus/ShortyBundle/Controller/CSVController.php

namespace Application\Sonata\HelperBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\ConfigurationRoute;
use Sensio\Bundle\FrameworkExtraBundle\ConfigurationTemplate;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Application\Sonata\HelperBundle\Helper\CSVTypes;

use Ddeboer\DataImport\Reader\CsvReader;
#use Ddeboer\DataImport\Source\StreamSource;
use Ddeboer\DataImport\Workflow;
use Ddeboer\DataImport\Writer\DoctrineWriter;

use AppBundle\Entity\Recipient;
//use Doctrine\Common\Collections\ArrayCollection;


class CSVController extends Controller
{

    public function importFileAction(Request $request) {

        // Get FileId to "import"
        $param=$request->request;
        $fileId=(int)trim($param->get("fileId"));

        $curType=trim($param->get("fileType"));
        $uploadedFile=$request->files->get("csvFile");        

        $separator = $param->get('separator');    

        // if upload was not ok, just redirect to "shortyStatWrongPArameters"
        if (!CSVTypes::existsType($curType) || $uploadedFile==null) return new Response("Erreur !");#return $this->redirect($this->generateUrl('sonata_admin_dashboard'));

        // generate dummy dir
        $import=getcwd()."/sonataAdminImport";
        $fname="import.csv";
        $filename=$import."/".$fname;
        @mkdir($import);
        @unlink($filename);

        // move file to dummy filename
        $uploadedFile->move($import,$fname);            

        echo "Starting to Import ".$filename.", Type: ".CSVTypes::getNameOfType($curType)."<br />";

        // open file
        $file = new \SplFileObject($filename);
        if ($file === false) die("Can't open filestream $filename");

        // Create and configure the reader
        $csvReader = new CsvReader($file, $separator);
        if ($csvReader===false) die("Can't create csvReader $filename");
        $csvReader->setHeaderRowNumber(0);

        // this must be done to import CSVs where one of the data-field has CRs within!
        $file->setFlags(\SplFileObject::READ_CSV |
            \SplFileObject::SKIP_EMPTY |
            \SplFileObject::READ_AHEAD);

        // Set Database into "nonchecking Foreign Keys"
        $em=$this->getDoctrine()->getManager();
        $em->getConnection()->exec("SET FOREIGN_KEY_CHECKS=0;");

        // Create the workflow
        $workflow = new Workflow($csvReader);
        if ($workflow===false) die("Can't create workflow $filename");
        
        $curEntityClass = CSVTypes::getEntityClass($curType);
        
        // parameters in array to find existing lines in BDD and update id found
        //check header to up^date on email if found.
        $headers = $csvReader->getColumnHeaders();

        #le champ id_client devra être créer plus tard et sera utilisé pour faire le lien avec le datamart
        #Gestion pour les dirrentes entités ici :
        if( in_array('idClient' , $headers) && $curEntityClass == "AppBundle:Client" ) {
            $writer = new DoctrineWriter($em, $curEntityClass, array('idClient'));
        }
        elseif( in_array('idclient' , $headers) && $curEntityClass == "AppBundle:TopClient" ) {
            $csvReader->setColumnHeaders(['idClient','isLinked','isStaff','isEntreprise','isFictif','isAdresseRens','isAdresseValide','isTelRens','isTelValide','isTelPortableValide','isEmailRens','isEmailValide','typeClient','cdPointVenteOrigineId','statut','clientProspect','optinLancel','optin','optinTel','optinCourrier','optinEmail','email','telephone3','telephone2','telephone1','portable3','portable2','portable1','pays','ville','codepostal','adresse3','adresse2','adresse1','cdParticulier','civilite','nationalite','sexe','jourNaissance','moisNaissance','anneeNaissance','nom','prenom','isEmailBounce','isEmailDesabo','isEmailBlacklist','isEmailActif','isContactableEmail','isContactableTelPortable','isContactableTel','isContactableAdresse','caHisto','ca3ans','ca12mois','freqHisto','freq12mois','freq3ans','pmHisto','pm12mois','pm3ans','uptHisto','upt12mois','upt3ans','pmaHisto','pma12mois','pma3ans','qteHisto','qte12mois','qte3ans','prixMaxArticleHisto','prixMaxArticle12mois','prixMaxArticle3ans','date1erachat','dateDernachat','statutPromoHisto','isProspect','segmentActuel','boutiqueRattachement','libelleBoutiqueRattachement','paysBoutiqueRattachement','libelleBoutiqueOrigine','paysBoutiqueOrigine']);
            $writer = new DoctrineWriter($em, $curEntityClass, array('idClient'));
        }else {
            $writer = new DoctrineWriter($em, $curEntityClass);
        }

        $writer->setTruncate(false);

        $entityMetadata=$em->getClassMetadata($curEntityClass);
        $entityMetadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);

        $workflow->addWriter($writer);

        $workflow->process();

        

        // RESetting Database Check Status        
        $em->getConnection()->exec("SET FOREIGN_KEY_CHECKS=1;");

        // After successfully import, some files need special treatment --> Reset some DB fields
        $recipients = array();


        //If client imported
        if ( $curType == CSVTypes::Client ) {
            $clients = $em->getRepository('AppBundle:Client')->getClientsWithCampaignId();

            //For each Client
            foreach ($clients as $key => $client) { 
                //Get Campaign that match with campaignId
                $campaign = $em->getRepository('AppBundle:Campaign')->findOneBy(array( 'idCampaignName' =>  $client->getIdCampaignName() ));          

                //If Campaign IS NOT NULL
                if($campaign) {
                    //Get Recipient
                    $recipient = $em->getRepository('AppBundle:Recipient')->findOneBy(array('campaign' => $campaign, 'client' => $client));
                    
                    //If Recipient DO NOT EXIST
                    if ($recipient == null){
                        //CREATE Recipient
                        $recipients[$key] = new Recipient();

                        $recipients[$key]->setCampaign($campaign);
                        $recipients[$key]->setClient($client);

                        $em->persist( $recipients[$key] );
                    }
                }
            }
            $em->flush();
        }

        return $this->render('ApplicationSonataHelperBundle:CSV:csv_import.html.twig');
    }
}