<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ResetAuthenticationFailureCronCommand extends ContainerAwareCommand 
{ 
	protected function configure() 
	{ 
		$this 
			->setName('cron:resetAuthenticationFailure') 
			->setDescription('Lancement de la tache cron:resetAuthenticationFailure')
		;
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{ 
		
		$text = $this->getDescription();

		$func = $this->getContainer()->get('login.security.interactive_login_listener');		

		$output->writeln($text);

		$func->ResetAuthenticationFailure();

		$output->writeln("End");
		
	}
}