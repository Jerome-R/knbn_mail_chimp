<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateLinkTrackingCommand extends ContainerAwareCommand
{ 
	protected function configure() 
	{ 
		$this 
			->setName('cron:updateLinkTracking') 
			->setDescription('Lancement de la tache cron:exportTrigger')
		;
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{ 
		
		$text = $this->getDescription();

		$date = new \DateTime();
		$date = $date->format('H:i:s');
		$output->writeln("Start ".$text." at ".$date);

		//$host = $input->getArgument('host');
		//$username = $input->getArgument('username');
		//$password = $input->getArgument('password');
		//$sourceFile = $input->getArgument('sourceFile');
		//$destinationFile = $input->getArgument('destinationFile');

		$import = $this->getContainer()->get('cron.update.links');

		$import->update($input, $output);
		//$import->exportCSVFileToFtp($host, $username, $password, $sourceFile, $destinationFile);

		$date = new \DateTime();
		$date = $date->format('H:i:s');
		$output->writeln("End at ".$date);
	}
}