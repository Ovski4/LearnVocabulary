<?php

namespace Ovski\LanguageBundle\Controller;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Ovski\LanguageBundle\Entity\Translation;
use Ovski\LanguageBundle\Form\TranslationType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Translation controller.
 *
 * @Route("/translation")
 */
class TranslationController extends Controller
{
    /**
     * Lists all Translation entities.
     *
     * @Route("/{slug}", name="translation")
     * @Method("GET")
     * @Template()
     */
    public function indexAction($slug)
    {
        $em = $this->getDoctrine()->getManager();
        $learning = $em->getRepository('OvskiLanguageBundle:Learning')->findOneBySlug($slug);

        if (!$learning) {
            throw new NotFoundHttpException(sprintf("Learning %s could not be found", $slug));
        }
        $entities = $em->getRepository('OvskiLanguageBundle:Translation')->findBy(array("learning" => $learning));
        $entity = new Translation();
        $form  = $this->createCreateForm($entity, $slug);

        return array(
            'entities' => $entities,
            'slug'     => $slug,
            'learning' => $learning,
            'form'     => $form->createView()
        );
    }

    /**
     * Creates a new Translation entity.
     *
     * @Route("/{slug}", name="translation_create")
     * @Method("POST")
     * @Template("OvskiLanguageBundle:Translation:new.html.twig")
     */
    public function createAction(Request $request, $slug)
    {
        $entity = new Translation();
        $form = $this->createCreateForm($entity, $slug);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $this->prepareTranslation($slug, $entity);
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('translation', array('slug' => $slug)));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Prepare a translation by setting words attributes
     *
     * @param Translation $entity The entity
     * @param string $slug
     */
    private function prepareTranslation($slug, $entity)
    {
        $em = $this->getDoctrine()->getManager();
        $learning = $em->getRepository('OvskiLanguageBundle:Learning')->findOneBySlug($slug);
        $entity->getWord1()->setLanguage($learning->getLanguage1());
        $entity->getWord2()->setLanguage($learning->getLanguage2());
        $entity->getWord1()->setWordType($entity->getWordType());
        $entity->getWord2()->setWordType($entity->getWordType());
        $this->setWordsIfExist($em, $entity);
        $entity->setLearning($learning);
    }

    /**
     * Get words and set them to the translation instead of creating them if they already exist
     *
     * @param ObjectManager $em
     * @param Translation $entity
     */
    private function setWordsIfExist($em, $entity)
    {
        $i = 1;
        $wordArray = array($entity->getWord1(), $entity->getWord2());

        foreach($wordArray as $word) {
            $existingWord = $em->getRepository('OvskiLanguageBundle:Word')->findOneBy(
                array(
                    'language' => $word->getLanguage(),
                    'value' => $word->getValue(),
                    'article' => $word->getArticle(),
                    'wordType' => $word->getWordType()
                )
            );
            if ($existingWord) {
                $setter = 'setWord'.$i;
                $entity->$setter($existingWord);
            }
            $i++;
        }
    }

    /**
     * Creates a form to create a Translation entity.
     * 
     * @param Translation $entity
     * @param string $slug
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Translation $entity, $slug)
    {
        $form = $this->createForm(new TranslationType(), $entity, array(
            'action' => $this->generateUrl('translation_create', array('slug' => $slug)),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Finds and displays a Translation entity.
     *
     * @Route("/{slug}/{id}", name="translation_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OvskiLanguageBundle:Translation')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Translation entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Translation entity.
     *
     * @Route("/{slug}/{id}/edit", name="translation_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OvskiLanguageBundle:Translation')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Translation entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Translation entity.
    *
    * @param Translation $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Translation $entity)
    {
        $form = $this->createForm(new TranslationType(), $entity, array(
            'action' => $this->generateUrl('translation_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Translation entity.
     *
     * @Route("{slug}/{id}", name="translation_update")
     * @Method("PUT")
     * @Template("OvskiLanguageBundle:Translation:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OvskiLanguageBundle:Translation')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Translation entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('translation_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Translation entity.
     *
     * @Route("{slug}/{id}", name="translation_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('OvskiLanguageBundle:Translation')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Translation entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('translation'));
    }

    /**
     * Creates a form to delete a Translation entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('translation_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
