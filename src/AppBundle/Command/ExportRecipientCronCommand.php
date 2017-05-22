<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ExportRecipientCronCommand extends ContainerAwareCommand
{ 
	protected function configure() 
	{ 
		$this 
			->setName('cron:exportRecipient') 
			->setDescription('Lancement de la tache cron:exportRecipient')
			->addArgument('type', InputArgument::REQUIRED, 'Type : adHoc | trigger ?')
		;
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{ 
		
		$text = $this->getDescription();

		$date = new \DateTime();
		$date = $date->format('H:i:s');
		$output->writeln("Start ".$text." at ".$date);

		$type = $input->getArgument('type');

		$export = $this->getContainer()->get('cron.export.recipient');

		$export->createExportClientCSVFile($input, $output, $type);

		$date = new \DateTime();
		$date = $date->format('H:i:s');
		$output->writeln("End at ".$date);
	}
}