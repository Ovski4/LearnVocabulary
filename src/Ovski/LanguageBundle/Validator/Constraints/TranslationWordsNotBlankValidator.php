<?php

namespace Ovski\LanguageBundle\Validator\Constraints;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Security\Core\SecurityContext;

class TranslationWordsNotBlankValidator extends ConstraintValidator
{
    public function validate($translation, Constraint $constraint)
    {
        $word1Value = $translation->getWord1()->getValue();
        if (empty($word1Value)) {
            $this->context->addViolationAt(
                null,
                $constraint->message,
                array('%language%' => $translation->getLearning()->getLanguage1()->getName()),
                null
            );
        }
        $word2Value = $translation->getWord2()->getValue();
        if (empty($word2Value)) {
            $this->context->addViolationAt(
                null,
                $constraint->message,
                array('%language%' => $translation->getLearning()->getLanguage2()->getName()),
                null
            );
        }
    }
}