<?php

namespace Ovski\LanguageBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Ovski\LanguageBundle\Entity\Learning;

class TranslationType extends AbstractType
{
    /**
     * @var \Ovski\LanguageBundle\Entity\Learning
     */
    private $learning;

    /**
     * Constructor
     *
     * @param Learning $learning
     */
    public function __construct(Learning $learning)
    {
        $this->learning = $learning;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('wordType', EntityType::class, array(
                'class' => 'OvskiLanguageBundle:WordType',
                'label' => 'Word type',
                'required'  => false,
            ))
            ->add('word1', new WordType($this->learning->getLanguage1()))
            ->add('word2', new WordType($this->learning->getLanguage2()))
            ->add('submit', SubmitType::class, array('label' => 'Add'));
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ovski\LanguageBundle\Entity\Translation',
        ));
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'ovski_languagebundle_translation';
    }
}
