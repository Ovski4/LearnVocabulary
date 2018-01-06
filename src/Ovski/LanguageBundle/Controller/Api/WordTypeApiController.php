<?php

namespace Ovski\LanguageBundle\Controller\Api;

use FOS\RestBundle\Controller\FOSRestController;
use Ovski\LanguageBundle\Entity\WordType;
use FOS\RestBundle\Controller\Annotations\Get;

class WordTypeApiController extends FOSRestController
{
    /**
     * @Get("/word-types")
     */
    public function getWordTypesAction()
    {
        $em = $this->getDoctrine()->getManager();
        $languages = $em->getRepository(WordType::class)->findAll();
        $view = $this->view($languages, 200);

        return $this->handleView($view);
    }
}