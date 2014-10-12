<?php

namespace Ovski\LanguageBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class TranslationWordsNotBlank extends Constraint
{
    public $message = "%language%.word.blank";

    public function validatedBy()
    {
        return 'translation_words_not_blank_validator';
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}