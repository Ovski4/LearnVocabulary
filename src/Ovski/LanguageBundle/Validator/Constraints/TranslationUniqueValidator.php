<?php

namespace Ovski\LanguageBundle\Validator\Constraints;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Security\Core\SecurityContext;

class TranslationUniqueValidator extends ConstraintValidator
{
    private $em;
    private $securityContext;

    /**
     * Constructor
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em, SecurityContext $securityContext)
    {
        $this->em = $em;
        $this->securityContext = $securityContext;
    }

    public function validate($translation, Constraint $constraint)
    {
        $nameArticle = $this->em->getRepository('OvskiLanguageBundle:WordType')->getDefaultWordTypeValue(
            $translation->getWordType()->getId()
        );

        // if the first word does not exist, the translation will be unique

        if ($nameArticle == 'name') {
            $article1 = $this->em->getRepository('OvskiLanguageBundle:Article')->findBy(
                array(
                    'language' => $translation->getLearning()->getLanguage1(),
                    'value'    => $translation->getWord1()->getArticle()->getValue()
                )
            );
        } else {
            $article1 = null;
        }
        $word1 = $this->em->getRepository('OvskiLanguageBundle:Word')->findBy(
            array(
                'article'  => $article1,
                'wordType' => $translation->getWordType(),
                'value'    => $translation->getWord1()->getValue()
            )
        );
        if (!$word1) {
            return;
        }

        // if the second word does not exist, the translation will be unique
        if ($nameArticle == 'name') {
            $article2 = $this->em->getRepository('OvskiLanguageBundle:Article')->findBy(
                array(
                    'language' => $translation->getLearning()->getLanguage2(),
                    'value'    => $translation->getWord2()->getArticle()->getValue(),
                )
            );
        } else {
            $article2 = null;
        }
        $word2 = $this->em->getRepository('OvskiLanguageBundle:Word')->findBy(
            array(
                'article'  => $article2,
                'wordType' => $translation->getWordType(),
                'value'    => $translation->getWord2()->getValue()
            )
        );
        if (!$word2) {
            return;
        }

        $translations = $this->em->getRepository('OvskiLanguageBundle:Translation')->findBy(
            array(
                'word1'    => $word1,
                'word2'    => $word2,
                'wordType' => $translation->getWordType(),
                'learning' => $translation->getLearning(),
                'user'     => $this->securityContext->getToken()->getUser()
            )
        );

        if (!empty($translations))
        {
            $this->context->addViolationAt('wordType', $constraint->message, array(), null);
        }
    }
}