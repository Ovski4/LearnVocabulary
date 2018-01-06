<?php

namespace Ovski\WebsiteBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Ovski\WebsiteBundle\Form\ContactType;

/**
 * Website controller.
 *
 * @Route("/")
 */
class WebsiteController extends Controller
{
    /**
     * Lists all Translation entities.
     *
     * @Route("/", name="home")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }

    /**
     * Old school guidelines
     *
     * @Route("/old-school", name="oldschool")
     * @Method("GET")
     * @Template()
     */
    public function oldSchoolAction()
    {
        return array();
    }

    /**
     * Tips
     *
     * @Route("/tips", name="tips")
     * @Method("GET")
     * @Template()
     */
    public function tipsAction()
    {
        return array();
    }

    /**
     * Contact
     *
     * @Route("/contact", name="contact")
     * @Method("GET")
     * @Template()
     */
    public function contactAction()
    {
        throw new \Exception('Error on purpose');
        $form = $this->createMailForm();

        return array('form' => $form->createView());
    }

    /**
     * Render the dropdown languages list for the menu
     *
     * @Template()
     */
    public function dropdownLanguagesAction(Request $request, $route, $routeParams)
    {
        return array(
            'languages' => array(
                'en' => 'English',
                'fr' => 'Français'
            ),
            'locale' => $request->getLocale(),
            'route' => $route,
            'routeParams' => $routeParams
        );
    }


    /**
     * Creates a form to send a mail
     *
     * @return \Symfony\Component\Form\Form
     */
    private function createMailForm()
    {
        $form = $this->createForm(
            ContactType::class,
            null,
            array(
                'action' => $this->generateUrl('send_mail'),
                'method' => 'POST'
            )
        );
        return $form;
    }

    /**
     * Mail Action
     *
     * @Route("/contact/send-message", name="send_mail")
     * @Method("POST")
     */
    public function mailAction(Request $request)
    {
        $form = $this->createMailForm();
        $form->handleRequest($request);
        if ($form->isValid()) {
            $data = $form->getData();
            $message = \Swift_Message::newInstance()
                ->setSubject($data['subject'])
                ->setFrom('baptiste.bouchereau@gmail.com')
                ->setTo('baptiste.bouchereau@gmail.com')
                ->setBody(
                    $this->renderView('OvskiWebsiteBundle:Mail:email.txt.twig',
                        array('message' => $data['message'], 'from' => $data['email'])
                    )
                )
            ;
            $this->get('mailer')->send($message);
            $this->get('session')->getFlashBag()->add('info', $this->get('translator')->trans('Mail sent!'));
            return $this->redirect($this->generateUrl('contact'));
        } else {
            throw new \Exception("Unvalid form");
        }
    }
}