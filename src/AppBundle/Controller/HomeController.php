<?php

namespace AppBundle\Controller;


// src/OC/PlatformBundle/Controller/AdvertController.php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Application\Sonata\UserBundle\Entity\User;

use AppTrackingBundle\Entity\Tracking;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

// Annotaitonss :
// Pour gérer les autorisations
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
// Pour gérer le ParamConverter et utiliser un entité en parametre à la place d'une simple variable
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class HomeController extends Controller
{
       
    public function _testAction(Request $request)
    {
        //$em2 = $this->getDoctrine()->getManager('tracking');
        $em = $this->getDoctrine()->getManager('default');


        $date1 = new \DateTime('2017-01-02');
        $date2 = new \DateTime();

        $dateDiff =  $date1->diff($date2);

        $mailChimp = $this->get('app.mailchimp');

        // API Mail chimp par batch avec class sf2
        $mailChimp = $this->get('app.mailchimp');

        $listId = '3bd0150f0a';

        $data1 =array(
            'email_address' => 'j.rabahi@claravista.fr',
            'status' => 'subscribed',
            'merge_fields' => array('FNAME' => 'Jérôme', 'LNAME' => 'Rabahi'));
        $data2 =
            array(
                'email_address' => 'e.busuttil@claravista.fr',
                'status' => 'subscribed',
                'merge_fields' => array('FNAME' => 'Elisa', 'LNAME' => 'Busuttil'));
        $data3 =
            array(
                'email_address' => 'ml.cariou@claravista.fr',
                'status' => 'subscribed',
                'merge_fields' => array('FNAME' => 'Marie-Line', 'LNAME' => 'Cariou'));
        $attributes = array(
            'operations' => array(
                array(
                    'path' => 'lists/' . $listId . '/members/'.md5($data1['email_address']),
                    'method' => 'PUT',
                    'body' => json_encode($data1)
                ),
                array(
                    'path' => 'lists/' . $listId . '/members/'.md5($data2['email_address']),
                    'method' => 'PUT',
                    'body' => json_encode($data2)
                ),
                array(
                    'path' => 'lists/' . $listId . '/members/'.md5($data3['email_address']),
                    'method' => 'PUT',
                    'body' => json_encode($data3)
                ),
            ));

        //$result = $mailChimp->post('batches/', $attributes);
        //$result = $mailChimp->get('batches/');
        $result = $mailChimp->get('batches/');

        return $this->render('AppBundle::_test.html.twig', array(
            'date1' => $date1,
            'date2' => $date2,
            'dateDiff' => $dateDiff->days,
            'result' => $result,
            'date_test' => $date1->modify('last Monday')
        ));
    }

    

    public function _impersonateAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository('ApplicationSonataUserBundle:User')->findBy(array());

        return $this->render('AppBundle::_impersonate.html.twig', array(
            'users' => $users
            )
        );
        // replace this example code with whatever you need
        /*return $this->render('default/index.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
        ));*/
    }

    public function notFoundAction(Request $request)
    {   
        return $this->render('AppBundle:Home:not_found.html.twig', array(
            )
        );       
    }

}
