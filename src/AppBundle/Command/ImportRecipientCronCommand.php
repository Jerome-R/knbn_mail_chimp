<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ImportRecipientCronCommand extends ContainerAwareCommand 
{ 
	protected function configure() 
	{ 
		$this 
			->setName('cron:importRecipient') 
			->setDescription('Lancement de la tache cron:importRecipient')
			->addArgument('separator', InputArgument::REQUIRED, 'CSV separator ?')
			->addArgument('type', InputArgument::REQUIRED, 'Type : adhoc | trigger ?')
			->addArgument('filename', InputArgument::OPTIONAL)
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

		$type = $input->getArgument('type');

		$filename = $input->getArgument('filename');

		$import = $this->getContainer()->get('cron.import.recipient');

		$output->writeln("Configuration du separateur");
		$import->setSeparator($input->getArgument('separator'));
		
		#Lncl
		# -> Set Import table clean for new update
		# -> import clients - update campaign
		# -> Delete users not in import file

		if($type == "trigger"){
			$output->writeln("Nettoyage de la table Import et Trigger");
			$import->deleteImport();
			
			$output->writeln("Importation des clients et mise a jour des triggers");
			$result = $import->importClientCSVFile($input, $output, $type);

			$output->writeln("Mise a jour de la table Recipient");
			$import->updateRecipients($input, $output, $type);

			# -> Get import result and save it
			
			$output->writeln("Archivage du fichier d'import");
			$import->moveUploadedFile();
			$output->writeln("Archivage de l'import");
			$importFile = $this->getContainer()->get('import.file.log');
			$importFile->AddImportFile($result);

			$output->writeln("Effacement des lignes qui ne sont pas l'import");
			$import->deleteRecipientNotInImport($input, $output);
		}
		elseif($type == "adhoc"){
			$output->writeln("Mise a jour des clients ".$filename);
			$result = $import->importClientCSVFile($input, $output, $filename);

			$output->writeln("Mise a jour de la table Recipient ".$filename);
			$import->updateRecipients($input, $output, $filename);
		}

		$date = new \DateTime();
		$date = $date->format('H:i:s');
		$output->writeln("Tache terminee at ".$date);
	}
}