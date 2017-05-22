<?php

namespace AppBundle\Service;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;


use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class ResponseListener
{
	private $container;

	public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function onKernelResponse(FilterResponseEvent $event)
    {   

        //$request = $this->container->get('request');
        $request    = $event->getRequest();
        $session    = $request->getSession();
        $routeName  = $request->get('_route');

        $ip = $_SERVER['REMOTE_ADDR'];
        
        if( $routeName == "app_home" ){
            $session->remove('filtre_trigger_client_boutique');
            $session->remove('filtre_trigger_client_local');
            $session->remove('filtre_trigger_client_fullname');
            $session->remove('filtre_trigger_client_filters');
            $session->remove('filtre_trigger_client_dr');
            $session->remove('filtre_trigger_client_RM');
            $session->remove('filtre_trigger_client_vendorCode');

            $session->remove('kpi_boutique_filtre');
        }

        if ( !in_array($routeName, array("app_preview_email")) ) {
            if ( !in_array($ip, array('127.0.0.1', '213.152.19.130')) && preg_match($pattern, $routeName) ){
                $event->getResponse()->headers->set('x-frame-options', 'deny');
            }
        }

		$pattern ='/admin/';

        if ( !in_array($ip, array('127.0.0.1', '213.152.19.130')) && preg_match($pattern, $routeName) ){
            throw new AccessDeniedHttpException();
        }
    }
}