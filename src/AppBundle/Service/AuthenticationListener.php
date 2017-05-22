<?php
 
namespace AppBundle\Service;
 
use Symfony\Component\Security\Core\AuthenticationEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Event\AuthenticationFailureEvent;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

use Doctrine\ORM\EntityManager;

use Application\Sonata\UserBundle\Entity\User;
 
class AuthenticationListener implements EventSubscriberInterface
{
    private $em;

	public function __construct(EntityManager $entityManager)
	{
	    $this->em = $entityManager;
	}

	/**
	 * getSubscribedEvents
	 *
	 * @author 	Joe Sexton <joe@webtipblog.com>
	 * @return 	array
	 */
	public static function getSubscribedEvents()
    {
        return array(
            AuthenticationEvents::AUTHENTICATION_FAILURE => 'onAuthenticationFailure',
            AuthenticationEvents::AUTHENTICATION_SUCCESS => 'onAuthenticationSuccess',
        );
    }
 
	/**
	 * onAuthenticationFailure
	 *
	 * @author 	Joe Sexton <joe@webtipblog.com>
	 * @param 	AuthenticationFailureEvent $event
	 */
	public function onAuthenticationFailure( AuthenticationFailureEvent $event )
	{
		$token = $event->getAuthenticationToken();
        $username = $token->getUsername();

        $user = $this->em->getRepository("ApplicationSonataUserBundle:User")->findOneBy(array('username' => $username));

        if($user != null){

            $failCount = $user->getAuthenticationFailure();
            $failCount = $failCount + 1;

            $user->setAuthenticationFailure($failCount);

            if(!$user->hasRole("ROLE_SUPER_ADMIN")){
            	if($failCount > 10) {
            		$user->setLocked(true);
            	}
            }

    	   $this->em->flush();
        }
	}
 
	/**
	 * onAuthenticationSuccess
	 *
	 * @author 	Joe Sexton <joe@webtipblog.com>
	 * @param 	InteractiveLoginEvent $event
	 */
	public function onAuthenticationSuccess( InteractiveLoginEvent $event )
    {
        $token = $event->getAuthenticationToken();
        $username = $token->getUsername();

        $user = $this->em->getRepository("ApplicationSonataUserBundle:User")->findOneBy(array('username' => $username));

        $user->setAuthenticationFailure(0);
        $this->em->flush();
    }

    public function ResetAuthenticationFailure ()
    {
    	try
        {
            $pdo = new \PDO('mysql:host=localhost;dbname=lncl_clienteling_prod;charset=utf8', 'root', '');
        }
        catch(Exception $e)
        {       
            $output->writeln($e->getMessage());
            die('Erreur : '.$e->getMessage());
        }

        $sql = "UPDATE fos_user_user SET authentication_failure = 0";

        $stmt = $pdo->prepare($sql);
        $stmt->execute();
    }
}