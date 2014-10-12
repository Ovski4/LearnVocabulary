<?php

namespace Ovski\LanguageBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class LearningLanguagesNotIdentical extends Constraint
{
    public $message = 'learning.languages.not.identical';

    public function validatedBy()
    {
        return 'learning_languages_not_identical_validator';
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}