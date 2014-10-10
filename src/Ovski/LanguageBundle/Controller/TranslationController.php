<?php

namespace Ovski\LanguageBundle\Controller;

use Gedmo\Translatable\TranslatableListener;
use Doctrine\ORM\Query;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Ovski\LanguageBundle\Entity\Translation;
use Ovski\LanguageBundle\Form\TranslationType;
use Ovski\LanguageBundle\Form\FilterType\TranslationFilterType;
use Doctrine\Common\Persistence\ObjectManager;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Exception\NotValidCurrentPageException;

/**
 * Translation controller.
 *
 * @Route("/")
 */
class TranslationController extends Controller
{
    /**
     * Lists all Translation entities for revision
     *
     * @Route("/revision/{slug}", name="translation_revision")
     * @Method("GET")
     * @Template()
     */
    public function revisionAction(Request $request, $slug)
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

        return array(
            'pager'       => $pager,
            'slug'        => $slug,
            'learning'    => $learning,
            'filterForm'  => $filterForm->createView(),
            'buttonTexts' => $this->getButtonTexts()
        );
    }

    /**
     * Get button texts according to locale
     */
    private function getButtonTexts()
    {
        $texts = array();
        $texts['left'] = $this->get ('translator')->trans('Hide left column');
        $texts['right'] = $this->get ('translator')->trans('Hide right column');
        $texts['display'] = $this->get ('translator')->trans('Display everything');

        return $texts;
    }

    /**
     * Lists all Translation entities for edition
     *
     * @Route("/edition/{slug}", name="translation_edition")
     * @Method("GET")
     * @Template()
     */
    public function editionAction(Request $request, $slug)
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

        $translation = new Translation();
        $form  = $this->createCreateForm($translation, $slug);

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
     * @Route("/edition/{slug}/create", name="translation_create")
     * @Method("POST")
     */
    public function createAction(Request $request, $slug)
    {
        $translation = new Translation();
        $form = $this->createCreateForm($translation, $slug);
        $form->handleRequest($request);
        $this->checkArticles($translation);
        if ($form->isValid()) {
            $this->prepareTranslation($slug, $translation);
            $translation->setUser($this->getUser());
            $em = $this->getDoctrine()->getManager();
            $em->persist($translation);
            $em->flush();

            return $this->redirect($this->generateUrl('translation_edition', array('slug' => $slug)));
        }

        return array('form' => $form->createView());
    }

    /**
     * Prepare a translation by setting words attributes
     *
     * @param Translation $translation The entity
     * @param string $slug
     */
    private function prepareTranslation($slug, $translation)
    {
        $em = $this->getDoctrine()->getManager();
        $learning = $em->getRepository('OvskiLanguageBundle:Learning')->findOneBySlug($slug);
        $translation->getWord1()->setLanguage($learning->getLanguage1());
        $translation->getWord2()->setLanguage($learning->getLanguage2());
        $translation->getWord1()->setWordType($translation->getWordType());
        $translation->getWord2()->setWordType($translation->getWordType());
        $this->setWordsIfExist($em, $translation);
        $translation->setLearning($learning);
    }

    /**
     * Check the article value (null or not) for a word
     */
    private function checkArticles(Translation $translation)
    {
        $wordArray = array($translation->getWord1(), $translation->getWord2());
        $em = $this->getDoctrine()->getManager();
        foreach($wordArray as $word) {
            $qb = $em->getRepository('OvskiLanguageBundle:WordType')
                ->createQueryBuilder('wt')
                ->where('wt.id=:id')
                ->setParameter('id', $translation->getWordType()->getId());
            $query = $qb->getQuery();
            $query->setHint(
                Query::HINT_CUSTOM_OUTPUT_WALKER,
                'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker'
            );
            $query->setHint(TranslatableListener::HINT_TRANSLATABLE_LOCALE, 'en');
            $nameArticle = $query->getSingleResult()->getValue();

            // if the words have name as wordType,
            // they must have an article except if the language has requiredArticle to false
            if ($translation->getWordType()->getValue() == $nameArticle && !$word->getArticle() && $word->getLanguage()->requireArticles()) {
                throw new \Exception('You need to specify an article because the type of the word is \'name\'');
            // if the words have not name as wordType,
            // they must not have an article
            } else if ($translation->getWordType()->getValue() != $nameArticle && $word->getArticle()) {
                throw new \Exception('A word which is not a \'name\' should not have an article');
            }
        }


    }

    /**
     * Get words and set them to the translation instead of creating them if they already exist
     *
     * @param ObjectManager $em
     * @param Translation $translation
     */
    private function setWordsIfExist($em, $translation)
    {
        $i = 1;
        $wordArray = array($translation->getWord1(), $translation->getWord2());

        foreach($wordArray as $word) {
            $existingWord = $em->getRepository('OvskiLanguageBundle:Word')->findOneBy(
                array(
                    'language' => $word->getLanguage(),
                    'value'    => $word->getValue(),
                    'article'  => $word->getArticle(),
                    'wordType' => $word->getWordType()
                )
            );
            if ($existingWord) {
                $setter = 'setWord'.$i;
                $translation->$setter($existingWord);
            }
            $i++;
        }
    }

    /**
     * Creates a form to create a Translation entity.
     *
     * @param Translation $translation
     * @param string $slug
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Translation $translation, $slug)
    {
        $em = $this->getDoctrine()->getManager();
        $learning = $em->getRepository('OvskiLanguageBundle:Learning')->findOneBySlug($slug);
        $form = $this->createForm(new TranslationType($learning), $translation, array(
            'action' => $this->generateUrl('translation_create', array('slug' => $slug)),
            'method' => 'POST',
        ));

        return $form;
    }

    /**
     * Displays a form to edit an existing Translation entity.
     *
     * @Route("edition/{slug}/{id}/edit", name="translation_edit", requirements={"id" = "\d+"})
     * @Method("GET")
     * @Template()
     */
    public function editAction($slug, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $translation = $em->getRepository('OvskiLanguageBundle:Translation')->find($id);

        if (!$translation) {
            throw $this->createNotFoundException('Unable to find Translation entity.');
        }

        $editForm = $this->createEditForm($translation, $slug);

        return array(
            'translation' => $translation,
            'edit_form'   => $editForm->createView(),
            'slug'        => $slug
        );
    }

    /**
    * Creates a form to edit a Translation entity.
    *
    * @param Translation $translation
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Translation $translation, $slug)
    {
        $em = $this->getDoctrine()->getManager();
        $learning = $em->getRepository('OvskiLanguageBundle:Learning')->findOneBySlug($slug);
        $form = $this->createForm(new TranslationType($learning), $translation, array(
            'action' => $this->generateUrl('translation_update', array('id' => $translation->getId(), 'slug' => $slug)),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Translation entity.
     *
     * @Route("{slug}/edit/{id}", name="translation_update", requirements={"id" = "\d+"})
     * @Method("PUT")
     * @Template("OvskiLanguageBundle:Translation:edit.html.twig")
     */
    public function updateAction(Request $request, $slug, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $translation = $em->getRepository('OvskiLanguageBundle:Translation')->find($id);

        if (!$translation) {
            throw $this->createNotFoundException('Unable to find Translation entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($translation, $slug);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('Translation successfully updated'));
            return $this->redirect($this->generateUrl('translation_edit', array(
                'id' => $id,
                'slug' => $slug
            )));
        }

        return array(
            'edit_form'   => $editForm->createView()
        );
    }

    /**
     * Deletes a Translation entity.
     *
     * @Route("{slug}/delete/{id}", requirements={"id" = "\d+"}, name="translation_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $slug, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $translation = $em->getRepository('OvskiLanguageBundle:Translation')->find($id);

            if (!$translation) {
                throw $this->createNotFoundException('Unable to find Translation entity.');
            }

            $em->remove($translation);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('Translation successfully deleted'));
        }

        return $this->redirect($this->generateUrl('translation_edition', array('slug' => $slug)));
    }

    /**
     * Display Translation deleteForm.
     *
     * @Template()
     */
    public function deleteFormAction($id, $slug)
    {
        $em = $this->getDoctrine()->getManager();
        $translation = $em->getRepository('OvskiLanguageBundle:Translation')->find($id);

        if (!$translation) {
            throw $this->createNotFoundException('Unable to find Translation');
        }
        if ($translation->getUser() != $this->getUser()) {
            throw new AccessDeniedException();
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'translation' => $translation,
            'delete_form' => $deleteForm->createView(),
            'slug'        => $slug
        );
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
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }

    /**
     * Star a Translation entity.
     *
     * @Route("/{slug}/star/{id}", name="translation_star", requirements={"id" = "\d+"})
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
