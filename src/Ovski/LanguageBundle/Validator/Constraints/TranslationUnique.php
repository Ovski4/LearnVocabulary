<?php

namespace Ovski\LanguageBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class TranslationUnique extends Constraint
{
    public $message = "You already entered this translation";

    public function validatedBy()
    {
        return 'translation_unique_validator';
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}