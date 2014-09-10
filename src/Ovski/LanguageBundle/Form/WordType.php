<?php

namespace Ovski\LanguageBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
use Ovski\LanguageBundle\Entity\Language;

class WordType extends AbstractType
{
    /**
     * @var \Ovski\LanguageBundle\Entity\Language
     */
    private $language;

    /**
     * Constructor
     *
     * @param Language $language
     */
    public function __construct($language)
    {
        $this->language = $language;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('article', 'entity', array(
                'attr' => array('class' => 'ovski-article-selectbox'),
                'required'  => false,
                'class' => 'OvskiLanguageBundle:Article',
                'query_builder' => function(EntityRepository $er) {
                    return
                        $er
                            ->createQueryBuilder('a')
                            ->where('a.language=:languageId')
                            ->setParameter('languageId', $this->language->getId())
                    ;
                }
            ))
            ->add('value')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ovski\LanguageBundle\Entity\Word',
            'label' => $this->language->getName(),
            'attr' => array('class' => 'ovski-word'),
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ovski_languagebundle_word';
    }
}
