<?php

namespace AppBundle\Service;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;


use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class ExceptionListener
{
	private $container;

	public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
	{	

	    if ($event->getException() instanceof NotFoundHttpException) {
	        $engine = $this->container->get('templating');
            $content = $engine->render('AppBundle:Home:not_found.html.twig');
            $event->setResponse(new Response($content, 404));
	    }
	}
}
