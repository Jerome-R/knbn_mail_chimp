<?php

namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Application\Sonata\UserBundle\Entity\User;

class PasswordEmailCronService
{
    private $container;
    private $em;
    private $templating;

	public function __construct(EntityManager $entityManager, ContainerInterface $container)
	{
		$this->em = $entityManager;
		$this->container = $container;
		$this->templating = $container->get('templating');
	}

	public function sendEmail()
	{
		$mailer_host=  $this->container->getParameter("mailer_host_aws");
        $mailer_user=  $this->container->getParameter("mailer_user_aws");
        $mailer_password=  $this->container->getParameter("mailer_password_aws");

		$transport = \Swift_SmtpTransport::newInstance( $mailer_host, 587, 'tls' )
                    ->setUsername($mailer_user)
                    ->setPassword($mailer_password)
        ;

        $mailer = \Swift_Mailer::newInstance($transport);

        $message1 = \Swift_Message::newInstance()
            ->setSubject("Modification du mot de passe")
            ->setFrom( array( "no_reply@actions-pdv-l.fr" => "Outil de clienteling Lancel") )
            ->setSender( array( "no_reply@actions-pdv-l.fr" => "Outil de clienteling Lancel") )
            ->setReplyTo("claravista@actions-pdv-l.fr")
            ->setTo($recipient->getClient()->getEmail())
            //->setTo(array("ml.cariou@claravista.fr", "j.rabahi@claravista.fr"))
            ->setBody(
                $this->templating->render(
                    // app/Resources/views/Emails/registration.html.twig
                    'AppBundle:Emails:modification.html.twig',
                    array()
                ),
                'text/html'
            )
        ;

        $message2 = \Swift_Message::newInstance()
            ->setSubject("Votre mot de passe va bientôt expirer")
            ->setFrom( array( "no_reply@actions-pdv-l.fr" => "Outil de clienteling Lancel") )
            ->setSender( array( "no_reply@actions-pdv-l.fr" => "Outil de clienteling Lancel") )
            ->setReplyTo("claravista@actions-pdv-l.fr")
            ->setTo($recipient->getClient()->getEmail())
            //->setTo(array("ml.cariou@claravista.fr", "j.rabahi@claravista.fr"))
            ->setBody(
                $this->templating->render(
                    // app/Resources/views/Emails/registration.html.twig
                    'AppBundle:Emails:expiration.html.twig',
                    array()
                ),
                'text/html'
            )
        ;

        $mailer->send($message1);
        $mailer->send($message2);
	}
}