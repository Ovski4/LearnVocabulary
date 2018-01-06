<?php

namespace Ovski\LanguageBundle\Form\FilterType;

use Lexik\Bundle\FormFilterBundle\Filter\FilterOperands;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\BooleanFilterType;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\EntityFilterType;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\TextFilterType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TranslationFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('wordType', EntityFilterType::class, array(
                'class' => 'OvskiLanguageBundle:WordType',
                'label' => 'Word type'
            ))
            ->add('isStarred', BooleanFilterType::class, array(
                'label' => 'Favorite'
            ))
            /*
            https://github.com/lexik/LexikFormFilterBundle/blob/master/Resources/doc/working-with-the-bundle.md#iii-working-with-entity-associations-and-embeddeding-filters
            ->add('word1', TextFilterType::class, array(
                'condition_pattern' => FilterOperands::STRING_CONTAINS,
                'label' => 'Word 1'
            ))
            ->add('word2', TextFilterType::class, array(
                'condition_pattern' => FilterOperands::STRING_CONTAINS,
                'label' => 'Word 2'
            ))
            */
        ;
    }

    public function getBlockPrefix()
    {
        return 'translation_filter';
    }

    /**
     * Configure options
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection'   => false,
            'validation_groups' => array('filtering') // avoid NotBlank() constraint-related message
        ));
    }
}