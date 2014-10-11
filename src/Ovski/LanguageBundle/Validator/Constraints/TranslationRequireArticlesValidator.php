<?php

namespace Ovski\LanguageBundle\Validator\Constraints;

use Doctrine\ORM\Query;
use Doctrine\ORM\EntityManager;
use Gedmo\Translatable\TranslatableListener;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class TranslationRequireArticlesValidator extends ConstraintValidator
{
    private $em;

    /**
     * Constructor
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function validate($translation, Constraint $constraint)
    {
        //echo"<pre>"; var_dump($translation); echo "</pre>"; die;
        $qb = $this->em->getRepository('OvskiLanguageBundle:WordType')
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
        if ($translation->getWordType()->getValue() == 'name'
            && !$translation->getWord1()->getArticle()
            && $translation->getLearning()->getLanguage1()->requireArticles())
        {
            $this->context->addViolationAt('word1', $constraint->message, array(), null);
        }
        if ($translation->getWordType()->getValue() == 'name'
            && !$translation->getWord2()->getArticle()
            && $translation->getLearning()->getLanguage2()->requireArticles())
        {
            $this->context->addViolationAt('word2', $constraint->message, array(), null);
        }
    }
}