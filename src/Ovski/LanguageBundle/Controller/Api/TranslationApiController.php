<?php

namespace Ovski\LanguageBundle\Controller\Api;

use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\FOSRestController;
use Ovski\LanguageBundle\Entity\Translation;

class TranslationApiController extends FOSRestController
{
    public function getTranslationsAction($learningSlug)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();

        $translations = $em->getRepository(Translation::class)->get(
            array(
                "learningSlug" => $learningSlug,
                "user" => $user,
            ),
            array("createdAt" => 'DESC')
        );

        $view = $this->view($translations, 200);
        $context = new Context();
        $context->setGroups(['ids', 'translation']);
        $view->setContext($context);

        return $this->handleView($view);
    }

    public function postTranslationsAction($learningSlug)
    {

    }

    // post

    // patch starred
}
