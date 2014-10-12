<?php

namespace Ovski\LanguageBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class LearningUnique extends Constraint
{
    public $message = "learning.already";

    public function validatedBy()
    {
        return 'learning_unique_validator';
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}