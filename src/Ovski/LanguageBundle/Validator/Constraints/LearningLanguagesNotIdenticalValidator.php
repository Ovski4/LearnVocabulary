<?php

namespace Ovski\LanguageBundle\Validator\Constraints;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class LearningLanguagesNotIdenticalValidator extends ConstraintValidator
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

    public function validate($learning, Constraint $constraint)
    {
        if ($learning->getLanguage1() == $learning->getLanguage2()) {
            $this->context->addViolationAt(
                null, $constraint->message, array(), null
            );
        }
    }
}