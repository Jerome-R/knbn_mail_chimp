<?php

namespace AppBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class PdoService
{
    private $db_host;
    private $db_name;
    private $db_user;
    private $db_password;
    private $db_tracking_host;
    private $db_tracking_name;
    private $db_tracking_user;
    private $db_tracking_password;
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

        $this->db_host = $this->container->getParameter('database_host');
        $this->db_name = $this->container->getParameter('database_name');
        $this->db_user = $this->container->getParameter('database_user');
        $this->db_password = $this->container->getParameter('database_password');

        $this->db_host2 = $this->container->getParameter('database_tracking_host');
        $this->db_name2 = $this->container->getParameter('database_tracking_name');
        $this->db_user2 = $this->container->getParameter('database_tracking_user');
        $this->db_password2 = $this->container->getParameter('database_tracking_password');
    }

    public function initPdoClienteling()
    {
        try
        {
            $pdo = new \PDO('mysql:host='.$this->db_host.';dbname='.$this->db_name.';charset=utf8', $this->db_user, $this->db_password);
        }
        catch(Exception $e)
        {       
            $output->writeln($e->getMessage());
            die('Erreur : '.$e->getMessage());
        }
        
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        return $pdo;
    }

    public function initPdoTracking()
    {
        try
        {
            $pdo = new \PDO('mysql:host='.$this->db_host2.';dbname='.$this->db_name2.';charset=utf8', $this->db_user2, $this->db_password2);
        }
        catch(Exception $e)
        {       
            $output->writeln($e->getMessage());
            die('Erreur : '.$e->getMessage());
        }
        
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        return $pdo;
    }
}
