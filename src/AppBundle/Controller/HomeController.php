<?php

namespace AppBundle\Controller;


// src/OC/PlatformBundle/Controller/AdvertController.php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AppBundle\Entity\Campaign;
use AppBundle\Entity\Client;
use AppBundle\Entity\Recipient;
use AppBundle\Entity\CampaignClient;
use AppBundle\Entity\Tracking as TrackingUpdate;
use Application\Sonata\UserBundle\Entity\User;
use AppBundle\Entity\ExportCsv;

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
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $session = $request->getSession();

        if ($session->get('count_home') != null) {
            $count_home = $session->get('count_home') + 1;   
        }
        else{
            $count_home = 1;
        }

        $session->set('count_home', $count_home);

    	return $this->render('AppBundle:Home:index.html.twig', array(
                'count_home' => $count_home
            )
        );
        // replace this example code with whatever you need
        /*return $this->render('default/index.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
        ));*/
    }
    
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

    public function _bounceAction(Request $request)
    {
        $mailer_host= "{mail.gandi.net:993/imap/ssl}bounces_checked";
        $mailer_trash= "{mail.gandi.net:993/imap/ssl}TRASH";
        $mailer_user=  "bounce@actions-pdv-l.fr";
        $mailer_password=  "claravista123";

        /* try to connect */
        $mbox = imap_open($mailer_host,$mailer_user,$mailer_password) or die('Cannot connect to mailbox: ' . imap_last_error());

        /*$list = imap_list($mbox, "$mailer_host", "*");
        if (is_array($list)) {
            foreach ($list as $val) {
                echo imap_utf7_decode($val) . "\n";
            }
        } else {
            echo "imap_list a échoué : " . imap_last_error() . "\n";
        }*/

        /* grab emails */
        //$emails = imap_search($mbox,'ALL');
        //$emails = imap_search($mbox,'FROM "MAILER-DAEMON@eu-west-1.amazonses.com"');
        $emails = imap_search($mbox,'SINCE "1 January 2017"');

        if($emails) {
            /* begin output var */
            $output = '';

            /* put the newest emails on top */
            rsort($emails);
            $count = count($emails);

            $i=1;

            /* for every email... */
            foreach($emails as $email_number) {

                /* get information specific to this email */
                $overview = imap_fetch_overview($mbox,$email_number,0);
                /*
                0 - Message header
                1 - MULTIPART/ALTERNATIVE
                1.1 - TEXT/PLAIN
                1.2 - TEXT/HTML
                2 - file.ext
                */
                $message = htmlspecialchars(imap_fetchbody($mbox,$email_number,2));
                $original = htmlspecialchars(imap_fetchbody($mbox,$email_number,3));
                //$header=imap_rfc822_parse_headers(imap_fetchheader($mbox,$i));
                //$header=imap_headerinfo($mbox,$i);

                //Parse message to get bounced adress.
                preg_match("/Status:\s+?(.*)/i", $message, $status);
                preg_match("/Action:\s+?(.*)/i", $message, $action);
                preg_match("/Diagnostic-Code:\s+?smtp;\s+?(.*)/s", $message, $error);
                if ($error == "" or $error == null){
                    preg_match("/Diagnostic-Code:\s+?smtp;+?(.*)/s", $message, $error);
                    if ($error == "" or $error == null){
                        preg_match("/Diagnostic-Code:\s+?(.*)/s", $message, $error);
                        if ($error == "" or $error == null){
                            preg_match("/Status:\s+?(.*)/s", $message, $error);

                            if ($error == "" or $error == null){
                                $error[1] = "n/a";
                            }
                        }
                    }
                }
                preg_match("/Final-Recipient:\s+?RFC822;\s+?(.*)/i", $message, $recipient);
                if ($recipient == "" or $recipient == null){
                    preg_match("/Final-Recipient:\s+?RFC822;+?(.*)/i", $message, $recipient);
                    if ($recipient == "" or $recipient == null){
                        $recipient[1] = "n/a";
                    }
                }

                preg_match("/X-TrackingId:\s+?(.*)/i", $original, $trackingId);
                if ($trackingId == "" or $trackingId == null){
                    $trackingId[1] = "n/a";
                }
                preg_match("/X-CampaignId:\s+?(.*)/i", $original, $campaignId);
                if ($campaignId == "" or $campaignId == null){
                    $campaignId[1] = "n/a";
                }
                preg_match("/Feedback-ID:\s+?(.*)/i", $original, $feed);
                if ($feed == "" or $feed == null){
                    $feed[1] = "n/a";
                }


                $date = new \DateTime($overview[0]->date);
                $date = $date->format("Y/m/d H:i");

                /* output the email header information */
                $output.="<tr>";
                //$output.= '<td>'.imap_utf8($overview[0]->subject).'</td>';
                //$output.= '<td>'.imap_utf8($overview[0]->from).'</td>';
                //$output.= '<td>'.imap_utf8($overview[0]->to).'</td>';
                if (isset($recipient[1]))
                    $output.= '<td>'.imap_utf8($recipient[1]).'</td>';
                else
                    $output.= '<td></td>';                
                $output.= '<td>'.imap_utf8($campaignId[1]).'</td>';
                $output.= '<td>'.imap_utf8($trackingId[1]).'</td>';
                $output.= '<td>'.imap_utf8($date).'</td>';
                if (isset($error[1]))
                    $output.= '<td>'.imap_utf8($error[1]).'</td>';
                else
                    $output.= '<td></td>';
                if (isset($status[1]))
                    $output.= '<td>'.imap_utf8($status[1]).'</td>';
                else
                    $output.= '<td></td>';
                $output.= '<td>'.imap_utf8(number_format($overview[0]->size/1000)).'&nbsp;ko</td>';
                if (isset($action[1]))
                    $output.= '<td>'.imap_utf8($action[1]).'</td>';
                else
                    $output.= '<td></td>';                
                if (isset($error[1]) &&  (imap_utf8($error[1])[0] == "5"))
                    $output.= '<td>Hard</td>';
                elseif (isset($error[1]) && (imap_utf8($error[1])[0] == "4" || imap_utf8($error[1])[0] == "X"))
                    $output.= '<td>Soft</td>';
                else
                    $output.= '<td>n/a</td>';
                //$output.= '<td>'.$overview[0]->recent.'</td>';
                //$output.= '<td>'.$overview[0]->msgno.'</td>';
                $output.= '</tr>';
            }
            $i++;
        }

        imap_close($mbox);

        return $this->render('AppBundle::_bounce.html.twig', array(
            'output' => $output,
            'count'  => $count
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

    //Gestion du tracking email ici
    public function _openAction($idClient, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        return $this->render('AppBundle::_open.html.twig', array(
            )
        );
    }

    //Monitoring
    public function _monitorAction($page, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $qb = $em->createQueryBuilder();
        $qb ->select("r")
            ->from('AppBundle:Recipient', 'r')
            ->where('r.user is null');

        //var_dump($qb->getDql());

        //$recipientsUser = $em->getRepository('AppBundle:Recipient')->findBy( array('user' => null), array('campaign' => 'ASC') );
        //Paginator : Pagination
        $recipientsUser  = $this
                ->get('knp_paginator')
                ->paginate(
                    $qb,
                    //$request->query->get('page', 1),//page number,
                    $page,
                    50//limit per page
        );

        $recipientsDataRecipient = $em->getRepository('AppBundle:Recipient')->findBy(array('dataRecipient' => null), array('campaign' => 'ASC'));

        return $this->render('AppBundle::_monitor.html.twig', array(
            'recipientsU' => $recipientsUser,
            'recipientsDR' => $recipientsDataRecipient,
            )
        );
    }

    public function logExportAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $session = $request->getSession();

        $exports = $em->getRepository('AppBundle:ExportCsv')->findAll();
        

        return $this->render('AppBundle:Home:logExport.html.twig', array(
            'exports' => $exports
        ));
    }

    public function _trackingAction(Request $request)
    {
        $em     = $this->getDoctrine()->getManager();
        $em2    = $this->getDoctrine()->getManager('tracking');

        $session = $request->getSession();

        $trackings = $em->getRepository('AppBundle:Tracking')->findAll();        

        return $this->render('AppBundle:Home:tracking.html.twig', array(
            'trackings' => $trackings
        ));
    }

    public function _doublonsSuspects(Request $request)
    {
        //$em2 = $this->getDoctrine()->getManager('tracking');
        $em = $this->getDoctrine()->getManager('default');

        //Si le numero de page est dans la requete on le recupère
        if( $request->query->get('page') != null )  {
            if ($request->query->get('page') != null && $request->query->get('page') > 0)
                $page = $request->query->get('page');
            else 
                $page = 1;
        }
        else
            $page = 1;

        $suspects = $em->getRepository('AppBundle:ClientSuspectDoublon')->getAllSuspectDoublons();
        $suspects = $em->getRepository('AppBundle:ClientSuspectDoublon')->getSuspectDoublonsBTQ('Opéra');
        $suspects = $em->getRepository('AppBundle:ClientSuspectDoublon')->getSuspectDoublonsDR('Véra Brénugat');
        $suspects = $em->getRepository('AppBundle:ClientSuspectDoublon')->getSuspectDoublonsRM('Elisa Piano');

        
        //Paginator : Pagination
        $suspects  = $this
                ->get('knp_paginator')
                ->paginate(
                    $suspects,
                    //$request->query->get('page', 1),//page number,
                    $page,
                    50//limit per page
        );

        $date1 = new \DateTime('2016-04-21');
        $date2 = new \DateTime();

        $dateDiff =  $date1->diff($date2);



        return $this->render('AppBundle::_doublons_suspects.html.twig', array(
            'date1' => $date1,
            'date2' => $date2,
            'dateDiff' => $dateDiff->days,
            'suspects' => $suspects
        ));
    }

    public function notFoundAction(Request $request)
    {   
        return $this->render('AppBundle:Home:not_found.html.twig', array(
            )
        );       
    }

}
