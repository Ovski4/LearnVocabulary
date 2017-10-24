<?php

namespace Ovski\UserBundle\Controller;

use Ovski\UserBundle\Form\ParametersType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
     * @Method({"GET", "PUT"})
     * @Template()
     */
    public function editAction(Request $request)
    {
        $editForm = $this->createEditForm();

        if ($request->getMethod() == 'PUT') { // add a new translation
            $editForm->handleRequest($request);
            if ($editForm->isValid()) {
                $this->getDoctrine()->getManager()->flush();
                $this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('Settings successfully updated'));
                return $this->redirect($this->generateUrl('parameters_edit'));
            }
        }
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
        $form = $this->createForm(ParametersType::class, $this->getUser(), array(
            'action' => $this->generateUrl('parameters_edit'),
            'method' => 'PUT',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Update'));

        return $form;
    }
}
