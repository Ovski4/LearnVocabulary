<?php

namespace Ovski\LanguageBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LearningType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('language1', null, array('label' => 'Language 1'))
            ->add('language2', null, array('label' => 'Language 2'))
            ->add('submit', SubmitType::class, array('label' => 'Validate'));
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ovski\LanguageBundle\Entity\Learning'
        ));
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'ovski_languagebundle_learning';
    }
}
