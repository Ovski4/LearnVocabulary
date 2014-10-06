<?php

namespace Ovski\LanguageBundle\Controller;

use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Ovski\LanguageBundle\Entity\Learning;
use Ovski\LanguageBundle\Form\LearningType;

/**
 * Learning controller.
 *
 * @Route("/languages")
 */
class LearningController extends Controller
{
    /**
     * Lists all Learning entities.
     *
     * @Route("/", name="learning")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $learnings = $em->getRepository('OvskiLanguageBundle:Learning')->getByUser(
            $this->getUser()->getId()
        );

        return array('learnings' => $learnings);
    }

    /**
     * Render the dropdown learnings for the menu
     *
     * @Template()
     */
    public function dropdownLearningsAction()
    {
        $em = $this->getDoctrine()->getManager();

        $learnings = $em->getRepository('OvskiLanguageBundle:Learning')->getByUser(
            $this->getUser()->getId()
        );

        return array('learnings' => $learnings);
    }

    /**
     * Creates a new Learning entity.
     *
     * @Route("/new", name="learning_create")
     * @Method("POST")
     * @Template("OvskiLanguageBundle:Learning:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $learning = new Learning();
        $form = $this->createCreateForm($learning);
        $form->handleRequest($request);
        if ($form->isValid()) {
            if (!$this->checkLearningExistsForUser($learning)) {
                $error = new FormError($this->get('translator')->trans("You are already learning those languages"));
                $form->get('language1')->addError($error);
            } else if (!$this->checkLearningLanguagesAreNotIdentical($learning)) {
                $error = new FormError($this->get('translator')->trans("You must choose 2 differents languages"));
                $form->get('language1')->addError($error);
            } else {
                if ($existingLearning = $this->checkLearningExists($learning)) {
                    $learning = $existingLearning;
                }
                $em = $this->getDoctrine()->getManager();
                $learning->addUser($this->getUser());
                $em->persist($learning);
                $em->flush();

                return $this->redirect($this->generateUrl('learning'));
            }
        }

        return array('form' => $form->createView());
    }

    /**
     * Check that the 2 languages are not identical
     *
     * @param $learning
     * @return bool
     */
    private function checkLearningLanguagesAreNotIdentical($learning)
    {
        if ($learning->getLanguage1() == $learning->getLanguage2()) {
            return false;
        }

        return true;
    }

    /**
     * Check the unicity of the languages combination
     *
     * @param $learning
     * @return bool
     */
    private function checkLearningExistsForUser($learning)
    {
        $em = $this->getDoctrine()->getManager();
        $learning1 = $em->getRepository('OvskiLanguageBundle:Learning')->getByUser(
            $this->getUser()->getId(),
            array(
                'language1' => $learning->getLanguage1()->getId(),
                'language2' => $learning->getLanguage2()->getId()
            )
        );
        $learning2 = $em->getRepository('OvskiLanguageBundle:Learning')->getByUser(
            $this->getUser()->getId(),
            array(
                'language1' => $learning->getLanguage2()->getId(),
                'language2' => $learning->getLanguage1()->getId()
            )
        );

        if ($learning1 || $learning2) {
            return false;
        }

        return true;
    }

    /**
     * Check if a learning already exists
     *
     * @param $learning
     * @return mixed
     */
    private function checkLearningExists($learning)
    {
        $em = $this->getDoctrine()->getManager();
        $learning1 = $em->getRepository('OvskiLanguageBundle:Learning')->findOneBy(
            array(
                'language2' => $learning->getLanguage2(),
                'language1' => $learning->getLanguage1()
            )
        );
        $learning2 = $em->getRepository('OvskiLanguageBundle:Learning')->findOneBy(
            array(
                'language1' => $learning->getLanguage2(),
                'language2' => $learning->getLanguage1()
            )
        );

        if (!$learning1 && !$learning2) {
            return false;
        } else if ($learning1) {
            return $learning1;
        } else {
            return $learning2;
        }
    }

    /**
     * Creates a form to create a Learning entity.
     *
     * @param Learning $learning
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Learning $learning)
    {
        $form = $this->createForm(new LearningType(), $learning, array(
            'action' => $this->generateUrl('learning_create'),
            'method' => 'POST',
        ));

        return $form;
    }

    /**
     * Displays a form to create a new Learning entity.
     *
     * @Route("/new", name="learning_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $learning = new Learning();
        $form = $this->createCreateForm($learning);

        return array(
            'form'   => $form->createView(),
        );
    }

    /**
     * Deletes a Learning entity.
     *
     * @Route("/{id}", name="learning_delete")
     * @Method("DELETE")
     */
    /*public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $learning = $em->getRepository('OvskiLanguageBundle:Learning')->find($id);

            if (!$learning) {
                throw $this->createNotFoundException('Unable to find Learning entity.');
            }
            if ($learning->getUser() != $this->getUser()) {
                throw new AccessDeniedException();
            }
            $em->remove($learning);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('learning'));
    }*/

    /**
     * Creates a form to delete a Learning entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    /*private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('learning_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }*/
}
