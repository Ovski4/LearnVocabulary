<?php

namespace Ovski\LanguageBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class TranslationRequireArticles extends Constraint
{
    public $message = 'This article is missing';

    public function validatedBy()
    {
        return 'translation_require_article_validator';
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}