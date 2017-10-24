<?php

namespace Ovski\LanguageBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;
use Ovski\LanguageBundle\Entity\Language;

class WordType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $attr = $this->getAttr($options['language']);

        $builder
            ->add('article', EntityType::class, array(
                'attr' => $attr,
                'required'  => false,
                'class' => 'OvskiLanguageBundle:Article',
                'query_builder' => function(EntityRepository $er) use ($options) {
                    return
                        $er
                            ->createQueryBuilder('a')
                            ->where('a.language=:languageId')
                            ->setParameter('languageId', $options['language']->getId())
                        ;
                }
            ))
            ->add('value', null, array(
                'label' => 'Word',
            ))
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults(array(
                'data_class' => 'Ovski\LanguageBundle\Entity\Word',
                'attr' => array('class' => 'ovski-word'),
                'label'
            ))
            ->setDefault('label', function (Options $options) {
                return $options['language']->getName();
            })
            ->setRequired(array(
                'language'
            ))
        ;
    }

    /**
     * Get attributes to set on article selectbox
     *
     * @param $language
     *
     * @return array
     * @throws \Exception
     */
    private function getAttr($language) {
        $class = "ovski-article-selectbox";
        if (!$language->requireArticles()) {
            $onlyArticle = $language->getArticles()[0];
            if (!$onlyArticle) {
                throw new \Exception(sprintf("You should have at least one article for language %s", $language->getName()));
            }
            $class = sprintf("%s empty", $class);
            $attr = array(
                'class' => $class,
                'data-article' => $onlyArticle
            );
        } else {
            $attr = array('class' => $class);
        }

        return $attr;
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'ovski_languagebundle_word';
    }
}
