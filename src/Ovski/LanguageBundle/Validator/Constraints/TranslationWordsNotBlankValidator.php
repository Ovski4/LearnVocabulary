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
        if (empty($translation->getWord1()->getValue())) {
            $this->context->addViolationAt(
                null,
                $constraint->message,
                array('%language%' => $translation->getLearning()->getLanguage1()->getName()),
                null
            );
        }
        if (empty($translation->getWord2()->getValue())) {
            $this->context->addViolationAt(
                null,
                $constraint->message,
                array('%language%' => $translation->getLearning()->getLanguage2()->getName()),
                null
            );
        }
    }
}