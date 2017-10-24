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
            ->add('word1', WordType::class, array(
                'language' => $options['learning']->getLanguage1()
            ))
            ->add('word2', WordType::class, array(
                'language' => $options['learning']->getLanguage2()
            ))
            ->add('submit', SubmitType::class, array('label' => 'Add'));
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired(array(
                'learning'
            ))
            ->setDefaults(array(
                'data_class' => 'Ovski\LanguageBundle\Entity\Translation',
            )
        );
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'ovski_languagebundle_translation';
    }
}
