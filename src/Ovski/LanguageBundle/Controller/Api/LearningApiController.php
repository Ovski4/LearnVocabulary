<?php

namespace Ovski\LanguageBundle\Controller\Api;

use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\FOSRestController;
use Ovski\LanguageBundle\Entity\Learning;

class LearningApiController extends FOSRestController
{
    public function getLearningsAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $languages = $em->getRepository(Learning::class)->getByUser($user->getId());

        $view = $this->view($languages, 200);
        $context = new Context();
        $context->setGroups(['ids', 'learning']);
        $view->setContext($context);

        return $this->handleView($view);
    }

    //post
}
