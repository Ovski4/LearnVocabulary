<?php

namespace Ovski\LanguageBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\FormError;
use Ovski\LanguageBundle\Entity\Learning;
use Ovski\LanguageBundle\Form\LearningType;

/**
 * Page controller.
 *
 * @Route("/")
 */
class PageController extends Controller
{
    /**
     * Lists all Learning entities.
     *
     * @Route("/", name="home")
     * @Method("GET")
     * @Template()
     */
    public function homeAction()
    {
        return array();
    }
}
