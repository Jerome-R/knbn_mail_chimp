<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ImportTopClientCronCommand extends ContainerAwareCommand 
{ 
	protected function configure() 
	{ 
		$this 
			->setName('cron:importTopClient') 
			->setDescription('Lancement de la tache cron:import')
			->addArgument('separator', InputArgument::REQUIRED, 'CSV separator?')
			//->addOption('yell', null, InputOption::VALUE_NONE, 'Si définie, la tâche criera en majuscules')
		;
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{ 
		$ip = $this->getContainer()->getParameter('local_ip');
		
		$date1 = new \DateTime();
		$date1 = $date1->format('H:i:s');

		$date = new \DateTime();
        $date = $date->format("Ymd");

        if($ip == "127.0.0.1")
        {
            $filename1 = "D:\\wamp\\www\\StoreApp\\web\\imports\\topclients\\lancel_liste_top_clients_clienteling_".$date.".csv";
            $filename2 = "D:\\wamp\\www\\StoreApp\\web\\imports\\topclients\\lancel_archive_top_clients_sortants_clienteling_".$date.".csv";
        }
        else{
            $filename1 = "/data/ftp/imports/topclients/lancel_liste_top_clients_clienteling_".$date.".csv";
            $filename2 = "/data/ftp/imports/topclients/lancel_archive_top_clients_sortants_clienteling_".$date.".csv";
        }


        if ( file_exists($filename1) && file_exists($filename2) ) {
			$text = $this->getDescription();
			$output->writeln($text);

			$import = $this->getContainer()->get('cron.import.top.client');

			$output->writeln("Configuration du separateur");
			$import->setSeparator($input->getArgument('separator'));

			/*$output->writeln("Scan du repertoire d'update");
			$files = $import->scanDir();

			foreach ($files as $key => $file) {
				if($key > 1 ){
					$output->writeln('Import du fichier '.$key.' : '.$file);
					$import->importTopClientCSVFile($file, $input, $output);
				}
			}*/

			$output->writeln('Reset des Top clients');
			$import->resetTopClients($input, $output);		

			$output->writeln('Import des top Clients');
			$import->importTopClientCSVFile($input, $output);

			$output->writeln('Import des top Clients sortants');
			$import->importTopClientSortantCSVFile($input, $output);



			/*$output->writeln("Mise a jour TopClient Boutique");
			$import->updateUserField($input, $output);*/

			/*$output->writeln("Mise a jour des commentaires TopClient");
			$import->updateCommentField($input, $output);*/

			$output->writeln("Archivage du fichier");
			$import->moveUploadedFile();
		} else {
		    $output->writeln("Aucun fichier, annulation de l'import");
		}

		$date2 = new \DateTime();
		$date2 = $date2->format('H:i:s');
		$output->writeln("debut : ".$date1." | fin : ".$date2);
	}
}