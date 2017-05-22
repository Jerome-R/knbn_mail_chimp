<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ImportKpiCronCommand extends ContainerAwareCommand 
{ 
	protected function configure() 
	{ 
		$this 
			->setName('cron:importKpi') 
			->setDescription('Lancement de l\'import des kpi')
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
            $filename1 = "D:\\wamp\\www\\StoreApp\\web\\imports\\kpis\\lancel_tdb_boutiques_trigger_".$date.".csv";
            $filename2 = "D:\\wamp\\www\\StoreApp\\web\\imports\\kpis\\lancel_tdb_boutiques_capture_".$date.".csv";
        }
        else{
            $filename1 = "/data/ftp/imports/kpis/lancel_tdb_boutiques_trigger_".$date.".csv";
            $filename2 = "/data/ftp/imports/kpis/lancel_tdb_boutiques_capture_".$date.".csv";
        }

		if ( file_exists($filename1) && file_exists($filename2) ) {
		    $text = $this->getDescription();
			$output->writeln($text);

			$import = $this->getContainer()->get('cron.import.kpi');

			$output->writeln("Configuration du separateur");
			$import->setSeparator($input->getArgument('separator'));

			$output->writeln("Import des Kpi Trigger");
			$import->importKpiTriggerCSVFile($input, $output);

			$output->writeln("Import des Kpi Capture");
			$import->importKpiCaptureCSVFile($input, $output);
		
			$import->moveUploadedFile();
		} else {
		    $output->writeln("Aucun fichier, annulation de l'import");
		}

		
	    /*$text = $this->getDescription();
		$output->writeln($text);

		$import = $this->getContainer()->get('cron.import.kpi');

		$output->writeln("Configuration du separateur");
		$import->setSeparator($input->getArgument('separator'));

		$output->writeln("Import des Kpi Capture");
		$files = $import->scanDir();
		$i = 1;
		foreach ($files as $csv) {
			if(substr($csv, -4) == ".csv" ){
				$output->writeln('Ouverture du fichier '.$i.' : '.$csv);
				$import->importKpiCaptureCSVFile($input, $output, $csv);
				$i++;
			}
		}*/

		$date2 = new \DateTime();
		$date2 = $date2->format('H:i:s');
		$output->writeln("debut : ".$date1." | fin : ".$date2);
	}
}