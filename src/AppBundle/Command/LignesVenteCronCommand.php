<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class LignesVenteCronCommand extends ContainerAwareCommand 
{ 
	protected function configure() 
	{ 
		$this 
			->setName('cron:importLigneVente') 
			->setDescription('Lancement de la tache cron:import')
			->addArgument('type', InputArgument::REQUIRED, 'Type : topClient | doublonSuspect ?')
			->addArgument('separator', InputArgument::REQUIRED, 'CSV separator?')
			//->addOption('yell', null, InputOption::VALUE_NONE, 'Si définie, la tâche criera en majuscules')
		;
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{ 
		$ip = $this->getContainer()->getParameter('local_ip');

		$dir1 = "Lignes_Vente_Par_Btq";
		$dir2 = "Lignes_Vente_SAV_Par_Btq";

		$date1 = new \DateTime();
		$date1 = $date1->format('H:i:s');

		$date = new \DateTime();
        $date = $date->format("Ymd");

        $type = $input->getArgument('type');


		
		$text = $this->getDescription();
		$output->writeln($text);

		$import = $this->getContainer()->get('cron.import.lignes.vente');

		$output->writeln("Configuration du separateur");
		$import->setSeparator($input->getArgument('separator'));


        if($ip == "127.0.0.1")
        {
            $filename1 = "D:\\wamp\\www\\StoreApp\\web\\imports\\topclients\\lancel_lignes_vente_top_clients_clienteling_".$date.".csv";
			$filename2 = "D:\\wamp\\www\\StoreApp\\web\\imports\\topclients\\lancel_lignes_vente_SAV_top_clients_clienteling_".$date.".csv";
			$filename3 = "D:\\wamp\\www\\StoreApp\\web\\imports\\topclients\\lancel_lignes_vente_top_clients_sortants_clienteling_".$date.".csv";
			$filename4 = "D:\\wamp\\www\\StoreApp\\web\\imports\\topclients\\lancel_lignes_vente_SAV_top_clients_sortants_clienteling_".$date.".csv";
			$filename5 = "D:\\wamp\\www\\StoreApp\\web\\imports\\suspects\\lancel_lignes_vente_doublons_suspects_clienteling_".$date.".csv";
			$filename6 = "D:\\wamp\\www\\StoreApp\\web\\imports\\suspects\\lancel_lignes_vente_SAV_doublons_suspects_clienteling_".$date.".csv";
        }
        else{
            $filename1 = "/data/ftp/imports/topclients/lancel_lignes_vente_top_clients_clienteling_".$date.".csv";
			$filename2 = "/data/ftp/imports/topclients/lancel_lignes_vente_SAV_top_clients_clienteling_".$date.".csv";
            $filename3 = "/data/ftp/imports/topclients/lancel_lignes_vente_top_clients_sortants_clienteling_".$date.".csv";
            $filename4 = "/data/ftp/imports/topclients/lancel_lignes_vente_SAV_top_clients_sortants_clienteling_".$date.".csv";
			$filename5 = "/data/ftp/imports/suspects/lancel_lignes_vente_doublons_suspects_clienteling_".$date.".csv";
			$filename6 = "/data/ftp/imports/suspects/lancel_lignes_vente_SAV_doublons_suspects_clienteling_".$date.".csv";
        }

        if ( (file_exists($filename1) && file_exists($filename2) && file_exists($filename3) && file_exists($filename4)) || file_exists($filename5) ) {

			//$output->writeln("Reset des lignes de ventes");
			//$import->resetLignesVentes( $input, $output);

			/*$output->writeln("Scan du repertoire d'update");
			$files = $import->scanDir($dir1);

			$output->writeln("Creation / Mise a jour des tickets des lignes de vente");
			foreach ($files as $key => $file) {
				if($key > 1 ){
					$output->writeln('Ouverture du fichier '.$key.' : '.$file);
					$import->createTickets($dir1, $file, $input, $output);
				}
			}
			$output->writeln("Importation des lignes de ventes");
			foreach ($files as $key => $file) {
				if($key > 1 ){
					$output->writeln('Ouverture du fichier '.$key.' : '.$file);
					$import->importLignesVentesCSVFile($dir1, $file, $input, $output);
				}
			}*/
			
			if($type == "topClient"){
				$output->writeln("Creation des tickets et des lignes de ventes topclients");
				$import->createTickets($filename1, $input, $output);
				$output->writeln("Creation des tickets et des lignes de ventes topclients sortants");
				$import->createTicketsSortants($filename3, $input, $output);

				$output->writeln("Creation des tickets et des lignes de ventes SAV topclients");
				$import->createTickets($filename2, $input, $output);
				$output->writeln("Creation des tickets et des lignes de ventes SAV topclients sortants");
				$import->createTicketsSortants($filename4, $input, $output);

				$output->writeln("Archivages des fichiers");
				$import->moveUploadedFile1();
			}
			elseif($type == "doublonSuspect"){
				$output->writeln("Creation des tickets et des lignes de ventes doublons suspects");
				$import->createTickets($filename5, $input, $output);
				
				$output->writeln("Creation des tickets et des lignes de ventes SAV doublons suspects");
				$import->createTickets($filename6, $input, $output);

				$output->writeln("Archivages des fichiers");
				$import->moveUploadedFile2();
			}

			//$output->writeln("Archivage des fichiers");
			//$import->moveUploadedFile();
		}else {
		    $output->writeln("Aucun fichier, annulation de l'import");
		}

		$date2 = new \DateTime();
		$date2 = $date2->format('H:i:s');
		$output->writeln("debut : ".$date1." | fin : ".$date2);
	}
}