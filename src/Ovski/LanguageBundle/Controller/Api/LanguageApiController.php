<?php

namespace Ovski\LanguageBundle\Controller\Api;

use FOS\RestBundle\Controller\FOSRestController;
use Ovski\LanguageBundle\Entity\Language;

class LanguageApiController extends FOSRestController
{
    public function getLanguagesAction()
    {
        $em = $this->getDoctrine()->getManager();
        $languages = $em->getRepository(Language::class)->findAll();
        $view = $this->view($languages, 200);

        return $this->handleView($view);
    }
}