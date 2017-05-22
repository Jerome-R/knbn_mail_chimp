<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class PasswordEmailCronCommand extends ContainerAwareCommand
{ 
	protected function configure() 
	{ 
		$this 
			->setName('cron:sendEmailPassword') 
			->setDescription('Lancement de la tache cron:sendEmailPassword')
			//->addArgument('host', InputArgument::REQUIRED, 'FTP Host?')
			//->addArgument('username', InputArgument::REQUIRED, 'Username?')
			//->addArgument('password', InputArgument::REQUIRED, 'Password?')
			//->addArgument('sourceFile', InputArgument::REQUIRED, 'sourceFile?')
			//->addArgument('destinationFile', InputArgument::REQUIRED, 'destinationFile?')
			//->addOption('yell', null, InputOption::VALUE_NONE, 'Si définie, la tâche criera en majuscules')
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

		$email = $this->getContainer()->get('cron.send_password_email');

		$email->sendEmail();
		//$import->exportCSVFileToFtp($host, $username, $password, $sourceFile, $destinationFile);

		$date = new \DateTime();
		$date = $date->format('H:i:s');
		$output->writeln("End at ".$date);
	}
}