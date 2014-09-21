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
 * Learning controller.
 *
 * @Route("/learning")
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

        $entities = $em->getRepository('OvskiLanguageBundle:Learning')->getByUser(
            $this->getUser()->getId()
        );

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Learning entity.
     *
     * @Route("/", name="learning_create")
     * @Method("POST")
     * @Template("OvskiLanguageBundle:Learning:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Learning();
        //var_dump($entity); echo("-------------------------------");
        $form = $this->createCreateForm($entity);
        //var_dump($entity); echo("-------------------------------");
        $form->handleRequest($request);
        //var_dump($entity); echo("-------------------------------"); die;
        if ($form->isValid()) {
            if (!$this->checkLearningUserExists($entity)) {
                $error = new FormError("You are already learning those languages");
                $form->get('language1')->addError($error);
            } else if (!$this->checkLanguagesNotIdentical($entity)) {
                $error = new FormError("You must choose 2 differents languages");
                $form->get('language1')->addError($error);
            } else {
                if ($learning = $this->checkLearningExists($entity)) {
                    $entity = $learning;
                }
                $em = $this->getDoctrine()->getManager();
                $entity->addUser($this->getUser());
                $em->persist($entity);
                $em->flush();

                return $this->redirect($this->generateUrl('learning', array('id' => $entity->getId())));
            }
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Check that the 2 languages are not identical
     *
     * @param $entity
     * @return bool
     */
    private function checkLanguagesNotIdentical($entity)
    {
        if ($entity->getLanguage1() == $entity->getLanguage2()) {
            return false;
        }

        return true;
    }

    /**
     * Check the unicity of the languages combination
     *
     * @param $entity
     * @return bool
     */
    private function checkLearningUserExists($entity)
    {
        $em = $this->getDoctrine()->getManager();
        $learning1 = $em->getRepository('OvskiLanguageBundle:Learning')->getByUser(
            $this->getUser()->getId(),
            array(
                'language1' => $entity->getLanguage1()->getId(),
                'language2' => $entity->getLanguage2()->getId()
            )
        );
        $learning2 = $em->getRepository('OvskiLanguageBundle:Learning')->getByUser(
            $this->getUser()->getId(),
            array(
                'language1' => $entity->getLanguage2()->getId(),
                'language2' => $entity->getLanguage1()->getId()
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
     * @param $entity
     * @return mixed
     */
    private function checkLearningExists($entity)
    {
        $em = $this->getDoctrine()->getManager();
        $learning1 = $em->getRepository('OvskiLanguageBundle:Learning')->findOneBy(
            array(
                'language2' => $entity->getLanguage2(),
                'language1' => $entity->getLanguage1()
            )
        );
        $learning2 = $em->getRepository('OvskiLanguageBundle:Learning')->findOneBy(
            array(
                'language1' => $entity->getLanguage2(),
                'language2' => $entity->getLanguage1()
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
     * @param Learning $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Learning $entity)
    {
        $form = $this->createForm(new LearningType(), $entity, array(
            'action' => $this->generateUrl('learning_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

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
        $entity = new Learning();

        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Deletes a Learning entity.
     *
     * @Route("/{id}", name="learning_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('OvskiLanguageBundle:Learning')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Learning entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('learning'));
    }

    /**
     * Creates a form to delete a Learning entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('learning_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
