<?php

namespace Ovski\LanguageBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

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
            ->add('submit', 'submit', array('label' => 'Learn it now!'));
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ovski\LanguageBundle\Entity\Learning'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ovski_languagebundle_learning';
    }
}
