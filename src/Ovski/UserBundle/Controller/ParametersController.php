<?php

namespace Ovski\UserBundle\Controller;

use Ovski\UserBundle\Form\ParametersType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Parameters controller.
 *
 * @Route("/settings")
 */
class ParametersController extends Controller
{
    /**
     * Displays a form to edit the maxItemsPerPage parameter.
     *
     * @Route("/", name="parameters_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction()
    {
        $editForm = $this->createEditForm();

        return array(
            'edit_form' => $editForm->createView()
        );
    }

    /**
     * Creates a form to edit a User parameters
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm()
    {
        $form = $this->createForm(new ParametersType(), $this->getUser(), array(
            'action' => $this->generateUrl('parameters_update'),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing parameters user
     *
     * @Route("/", name="parameters_update")
     * @Method("PUT")
     * @Template("OvskiUserBundle:User:edit.html.twig")
     */
    public function updateAction(Request $request)
    {
        $editForm = $this->createEditForm();
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirect($this->generateUrl('parameters_edit'));
        }

        return array(
            'edit_form' => $editForm->createView(),
        );
    }
}
