<?php

namespace AppBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

//use Ijanki\Bundle\FtpBundle\Exception\FtpException;

class ExportRecipientCronService
{
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

    public function createExportClientCSVFile(InputInterface $input, OutputInterface $output, $type)
    {
        gc_enable();
        $batchSize = 20;
        $i = 0;

        $date = new \DateTime();
        $date = $date->format("Ymd"); 

        if($this->ip == "127.0.0.1")
        {
            @rename ('D:\wamp\www\StoreApp\web\exports\export_'.$type.'.csv' , 'D:\wamp\www\StoreApp\web\exports\archives\export_'.$type.'_'.$date.'.csv' );
            $handle = fopen('D:\wamp\www\StoreApp\web\exports\export_'.$type.'.csv', 'w+');
        }
        else
        {
            @rename ('/data/ftp/exports/export_'.$type.'.csv' , '/data/ftp/exports/archives/export_'.$type.'_'.$date.'.csv' );
            $handle = fopen('/data/ftp/exports/export_'.$type.'.csv', 'w+');
        }


        $sql =  "SELECT r.id, c.id_client, ca.type, ca.id_campaign_name, date(d.date_entree) as date_entree, r.contacted_at, r.canal, r.language, c.is_email_valide, c.is_adresse_valide, c.is_tel_valide, r.optin
                    FROM app_recipient r
                    LEFT JOIN app_campaign AS ca ON campaign_id = ca.id
                    LEFT JOIN app_client AS c ON client_id = c.id
                    LEFT JOIN app_data_recipient AS d ON d.id = r.data_recipient_id
                    WHERE ca.type = :type AND (contacted_at IS NOT NULL OR r.optin = 0)";
        
        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(':type', $type, \PDO::PARAM_STR);

        // Nom des colonnes du CSV 
        fputcsv($handle, array(
            'idclient',
            'idcampagne',
            'dateentree',
            'contactedAt',
            'channel',
            'language',
            'emailerr',
            'adrerr',
            'telerr',
            'optin'
            ),'|');

        try
        {
            $stmt->execute();
        }
        catch(Exception $e)
        {       
            $output->writeln($e->getMessage());
            die('Erreur 1 : '.$e->getMessage());
        }

        while( $recipient = $stmt->fetch(\PDO::FETCH_ASSOC) )
        {
            //$output->writeln(print_r($recipient));die();

            fputcsv($handle,array(
                $recipient['id_client'],
                $recipient['id_campaign_name'],
                $recipient['date_entree'],
                $recipient['contacted_at'],
                $recipient['canal'],
                $recipient['language'],
                $recipient['is_email_valide'],
                $recipient['is_adresse_valide'],
                $recipient['is_tel_valide'],
                $recipient['optin'],
                ),'|');

            if (($i % $batchSize) === 0) {
                gc_collect_cycles();
            }
            $i++;
        }
        gc_collect_cycles();

        fclose($handle);
    }
}
