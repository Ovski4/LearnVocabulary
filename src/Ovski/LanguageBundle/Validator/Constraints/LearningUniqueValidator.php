<?php

namespace Ovski\LanguageBundle\Validator\Constraints;


use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Security\Core\SecurityContext;

class LearningUniqueValidator extends ConstraintValidator
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

    public function validate($learning, Constraint $constraint)
    {
        $userId = $this->securityContext->getToken()->getUser()->getId();
        $language1Id = $learning->getLanguage1()->getId();
        $language2Id = $learning->getLanguage2()->getId();

        $learning1 = $this->em->getRepository('OvskiLanguageBundle:Learning')->getByUser(
            $userId,
            array(
                'language1' => $language1Id,
                'language2' => $language2Id
            )
        );
        if ($learning1) {
            $this->context->addViolationAt(
                null, $constraint->message, array(), null
            );
        }

        $learning2 = $this->em->getRepository('OvskiLanguageBundle:Learning')->getByUser(
            $this->securityContext->getToken()->getUser()->getId(),
            array(
                'language1' => $language2Id,
                'language2' => $language1Id
            )
        );
        if ($learning2) {
            $this->context->addViolationAt(
                null, $constraint->message, array(), null
            );
        }
    }
}