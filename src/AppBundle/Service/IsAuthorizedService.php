<?php
 
namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;

use Application\Sonata\UserBundle\Entity\User;
use AppBundle\Entity\Client;
use AppBundle\Entity\DataRecipient;
use Symfony\Component\DependencyInjection\ContainerInterface;
 
class IsAuthorizedService
{
    private $em;
    private $isAuthorized = 0;

	public function __construct(EntityManager $entityManager)
	{
        $this->em = $entityManager;
	}

    public function IsAuthorized (User $user, Client $client = null, $module, DataRecipient $dataRecipient = null)
    {
        if($client != null)
        {
            if($module == "trigger")
            {
                switch($user->getRole() ){
                    case 'ROLE_BOUTIQUE':
                        if( $dataRecipient->getLibelleBoutiqueAchat() == $user->getLibelle() )
                        {
                            $this->isAuthorized = 1;
                        }
                    break;
                    case 'ROLE_DIRECTEUR':
                        $boutiques = $this->em->getRepository("ApplicationSonataUserBundle:User")->findby(
                            array('directeur' => $user->getLibelle(), 'role' => 'ROLE_BOUTIQUE')
                        );

                        foreach ($boutiques as $boutique) {
                            if( $dataRecipient->getLibelleBoutiqueAchat() == $boutique->getLibelle() )
                            {
                                $this->isAuthorized = 1;
                            }
                        }

                    break;
                    case 'ROLE_RETAIL_MANAGER':
                        $boutiques = $this->em->getRepository("ApplicationSonataUserBundle:User")->findby(
                            array('retailManager' => $user->getLibelle(), 'role' => 'ROLE_BOUTIQUE')
                        );

                        foreach ($boutiques as $boutique) {
                            if( $dataRecipient->getLibelleBoutiqueAchat() == $boutique->getLibelle() )
                            {
                                $this->isAuthorized = 1;
                            }
                        }
                    break;
                    case 'ROLE_SIEGE':
                        $this->isAuthorized = 1;
                    break;
                    default:
                        $this->isAuthorized = 0;
                    break;
                }
            }
            elseif($module == "topclient"){
                switch($user->getRole() ){
                    case 'ROLE_BOUTIQUE':
                        if( $client->getLibelleBoutiqueRattachementTopclient() == $user->getLibelle() )
                        {
                            $this->isAuthorized = 1;
                        }
                    break;
                    case 'ROLE_DIRECTEUR':
                        $boutiques = $this->em->getRepository("ApplicationSonataUserBundle:User")->findby(
                            array('directeur' => $user->getLibelle(), 'role' => 'ROLE_BOUTIQUE')
                        );

                        foreach ($boutiques as $boutique) {
                            if( $client->getLibelleBoutiqueRattachementTopclient() == $boutique->getLibelle() )
                            {
                                $this->isAuthorized = 1;
                            }
                        }

                    break;
                    case 'ROLE_RETAIL_MANAGER':
                        $boutiques = $this->em->getRepository("ApplicationSonataUserBundle:User")->findby(
                            array('retailManager' => $user->getLibelle(), 'role' => 'ROLE_BOUTIQUE')
                        );

                        foreach ($boutiques as $boutique) {
                            if( $client->getLibelleBoutiqueRattachementTopclient() == $boutique->getLibelle() )
                            {
                                $this->isAuthorized = 1;
                            }
                        }
                    break;
                    case 'ROLE_SIEGE':
                        $this->isAuthorized = 1;
                    break;
                    default:
                        $this->isAuthorized = 0;
                    break;
                }
            }
        }
        return $this->isAuthorized;
    }
}