<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ImportDoublonSuspectCronCommand extends ContainerAwareCommand 
{ 
	protected function configure() 
	{ 
		$this 
			->setName('cron:importDoublonSuspect') 
			->setDescription('Lancement de la tache cron:importDoublonSuspect')
			->addArgument('separator', InputArgument::REQUIRED, 'CSV separator ?')
			//->addOption('yell', null, InputOption::VALUE_NONE, 'Si définie, la tâche criera en majuscules')
		;
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{ 	
		$date = new \DateTime();
		$date = $date->format('H:i:s');
		$output->writeln("Start at ".$date);
		
		$text = $this->getDescription();
		$output->writeln($text);

		$import = $this->getContainer()->get('cron.import.doublon.suspect');

		$output->writeln("Configuration du separateur");
		$import->setSeparator($input->getArgument('separator'));

		$output->writeln("Reset des suspects doublons");
		$import->resetSuspectDoublon();
		
		$output->writeln("Importation des doublons suspects");
		$result = $import->importDoublonSuspectCSVFile($input, $output);

		# -> Get import result and save it
		
		$output->writeln("Archivage du fichier d'import");
		$import->moveUploadedFile();
		

		$date = new \DateTime();
		$date = $date->format('H:i:s');
		$output->writeln("Tache terminee at ".$date);
	}
}