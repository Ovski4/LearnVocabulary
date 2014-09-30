<?php

namespace Ovski\LanguageBundle\Form\FilterType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TranslationFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('wordType', 'filter_entity', array(
                'class' => 'OvskiLanguageBundle:WordType',
                'label' => 'Word Type'
            ))
            ->add('isStarred', 'filter_boolean', array(
                'label' => 'Starred'
            ))
        ;
    }

    public function getName()
    {
        return 'translation_filter';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection'   => false,
            'validation_groups' => array('filtering') // avoid NotBlank() constraint-related message
        ));
    }
}