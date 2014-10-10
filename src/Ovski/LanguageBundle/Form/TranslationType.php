<?php

namespace Ovski\LanguageBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
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
            ->add('wordType', 'entity', array(
                'class' => 'OvskiLanguageBundle:WordType',
                'label' => 'Word type'
            ))
            ->add('word1', new WordType($this->learning->getLanguage1()))
            ->add('word2', new WordType($this->learning->getLanguage2()))
            ->add('submit', 'submit', array('label' => 'Add'));
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ovski\LanguageBundle\Entity\Translation',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ovski_languagebundle_translation';
    }
}
