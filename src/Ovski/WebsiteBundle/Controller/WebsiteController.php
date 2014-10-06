<?php

namespace Ovski\WebsiteBundle\Controller;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Ovski\LanguageBundle\Entity\Translation;
use Ovski\LanguageBundle\Form\TranslationType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Exception\NotValidCurrentPageException;

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
     * Render the dropdown languages list for the menu
     *
     * @param Request $request
     * @param $route
     * @param $routeParams
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
}
