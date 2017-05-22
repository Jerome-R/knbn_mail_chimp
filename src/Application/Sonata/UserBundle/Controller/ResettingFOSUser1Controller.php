<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Sonata\UserBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\UserBundle\Model\UserInterface;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\Security\Core\SecurityContext;

use FOS\UserBundle\Controller\ResettingController as BaseController;

class ResettingFOSUser1Controller extends BaseController
{   
    public function resetAction($token)
    {
        $user = $this->container->get('fos_user.user_manager')->findUserByConfirmationToken($token);
        $em = $this->container->get('doctrine')->getEntityManager();
        
        if (null === $user) {
            throw new NotFoundHttpException(sprintf('The user with "confirmation token" does not exist for value "%s"', $token));
        }

        if (!$user->isPasswordRequestNonExpired($this->container->getParameter('fos_user.resetting.token_ttl'))) {
            return new RedirectResponse($this->container->get('router')->generate('fos_user_resetting_request'));
        }

        $form = $this->container->get('fos_user.resetting.form');
        $formHandler = $this->container->get('fos_user.resetting.form.handler');
        $process = $formHandler->process($user);

        $error = '';

        if ($process) {
            $date = new \DateTime();
            $date = $date->modify('+6 months');

            $user->setCredentialsExpireAt($date);
            $user->setIsEmailCredentialExpiredSent(false);
            $em->flush();

            $this->setFlash('fos_user_success', 'resetting.flash.success');
            $response = new RedirectResponse($this->container->get('router')->generate('app_home'));
            $this->authenticateUser($user, $response);

            return $response;
        }
        else{
            $error = "Votre mot de passe doit contenir au moins 8 caractères dont une majuscule, un chiffre et un caratère spécial : !,@,#,$,%,^,*.";
        }

        return $this->container->get('templating')->renderResponse('FOSUserBundle:Resetting:reset.html.'.$this->getEngine(), array(
            'token' => $token,
            'form'  => $form->createView(),
            'error' => $error
        ));
    }
}
