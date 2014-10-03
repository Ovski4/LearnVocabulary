<?php

namespace Ovski\LanguageBundle\Controller;

use Doctrine\Common\Persistence\ObjectManager;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Ovski\LanguageBundle\Entity\Translation;
use Ovski\LanguageBundle\Form\TranslationType;
use Ovski\LanguageBundle\Form\FilterType\TranslationFilterType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Exception\NotValidCurrentPageException;

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
    public function indexAction(Request $request, $slug)
    {
        $em = $this->getDoctrine()->getManager();
        $learning = $em->getRepository('OvskiLanguageBundle:Learning')->getOneByUser(
            $this->getUser()->getId(),
            array('slug' => $slug)
        );

        if (!$learning) {
            throw new NotFoundHttpException(sprintf("Learning %s could not be found", $slug));
        }
        $translationQueryBuilder = $em->getRepository('OvskiLanguageBundle:Translation')->getQueryBuilder(
            array(
                "learning" => $learning->getId(),
                "user" => $this->getUser()->getId(),
            ),
            array("createdAt" => 'DESC')
        );

        $filterForm = $this->get('form.factory')->create(new TranslationFilterType());

        if ($this->get('request')->query->has($filterForm->getName())) {
            $filterForm->submit($this->get('request')->query->get($filterForm->getName()));
            $this
                ->get('lexik_form_filter.query_builder_updater')
                ->addFilterConditions($filterForm, $translationQueryBuilder)
            ;
        }

        $pager = new Pagerfanta(new DoctrineORMAdapter($translationQueryBuilder));
        $pager->setMaxPerPage($this->getUser()->getMaxitemsPerPage());
        $page = $request->query->get('page', 1);

        try {
            $pager->setCurrentPage($page);
        } catch (NotValidCurrentPageException $e) {
            throw new NotFoundHttpException();
        }

        $entity = new Translation();
        $form  = $this->createCreateForm($entity, $slug);

        return array(
            'pager'      => $pager,
            'slug'       => $slug,
            'learning'   => $learning,
            'filterForm' => $filterForm->createView(),
            'form'       => $form->createView()
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
            $entity->setUser($this->getUser());
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
        $this->checkArticles($entity);
        $entity->setLearning($learning);
    }

    /**
     * Check the article value (null or not) for a word
     */
    private function checkArticles(Translation $entity)
    {
        $wordArray = array($entity->getWord1(), $entity->getWord2());

        foreach($wordArray as $word) {
            // if the words have name as wordType,
            // they must have an article except if the language has requiredArticle to false
            if ($word->getWordType()->getValue() == 'name' && !$word->getArticle()) {
                throw new \Exception('You need to specify an article because the type of the word is \'name\'');
            // if the words have not name as wordType,
            // they must not have an article
            } else if ($word->getWordType()->getValue() != 'name' && $word->getArticle()) {
                throw new \Exception('A word which is not a \'name\' should not have an article');
            }
        }


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
        $em = $this->getDoctrine()->getManager();
        $learning = $em->getRepository('OvskiLanguageBundle:Learning')->findOneBySlug($slug);
        $form = $this->createForm(new TranslationType($learning), $entity, array(
            'action' => $this->generateUrl('translation_create', array('slug' => $slug)),
            'method' => 'POST',
        ));

        return $form;
    }

    /**
     * Finds and displays a Translation entity.
     *
     * @Route("/{slug}/{id}", requirements={"id" = "\d+"}, name="translation_show")
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
        $em = $this->getDoctrine()->getManager();
        $learning = $em->getRepository('OvskiLanguageBundle:Learning')->findOneBySlug($slug);
        $form = $this->createForm(new TranslationType($learning), $entity, array(
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

    /**
     * Star a Translation entity.
     *
     * @Route("/{slug}/star/{id}", name="translation_star")
     * @Method("GET")
     */
    public function starAction(Request $request, $slug, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $translation = $em->getRepository('OvskiLanguageBundle:Translation')->find($id);

        if (!$translation) {
            throw $this->createNotFoundException('Unable to find Translation entity.');
        }
        if ($translation->getUser() != $this->getUser()) {
            throw new AccessDeniedException();
        }

        $translation->setIsStarred(!$translation->getIsStarred());
        $em->persist($translation);
        $em->flush();

        if ($request->isXmlHttpRequest()) {
            return new Response("Starred");
        } else {
            return $this->redirect($this->generateUrl('translation', array('slug' => $slug)));
        }
    }

    /**
     * Download a csv containing translations
     *
     * @Route("/{slug}/csv", name="translation_download_csv")
     * @Method("GET")
     */
    public function downloadCsvAction($slug) {
        $csv = $this->get('translation_manager')->generateCsv($this->getUser()->getId(), $slug);

        return new Response($csv, 200, array(
            'Content-Type' => 'application/force-download',
            'Content-Disposition' => 'attachment; filename="'.$slug.'-vocabulary.csv"'
        ));
    }
}
