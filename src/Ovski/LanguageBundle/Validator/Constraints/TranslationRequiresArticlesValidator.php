<?php

namespace Ovski\LanguageBundle\Validator\Constraints;


use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class TranslationRequiresArticlesValidator extends ConstraintValidator
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
        $nameArticle = $this->em->getRepository('OvskiLanguageBundle:WordType')->getDefaultWordTypeValue(
            $translation->getWordType()->getId()
        );

        // if the words have name as wordType,
        // they must have an article except if the language has requiredArticle to false
        if ($nameArticle == 'name') {
            if (!$translation->getWord1()->getArticle() && $translation->getLearning()->getLanguage1()->requireArticles())
            {
                $this->context->addViolationAt(
                    'word1',
                    $constraint->message,
                    array('%language%' => $translation->getLearning()->getLanguage1()->getName()),
                    null
                );
            }
            if (!$translation->getWord2()->getArticle() && $translation->getLearning()->getLanguage2()->requireArticles())
            {
                $this->context->addViolationAt(
                    'word2',
                    $constraint->message,
                    array('%language%' => $translation->getLearning()->getLanguage2()->getName()),
                    null
                );
            }
        }
    }
}