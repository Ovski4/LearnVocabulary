<?php

namespace Ovski\WebsiteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                'attr' => array(
                    'pattern' => '.{2,}' //minlength
                )
            ))
            ->add('email', EmailType::class, array(
                'attr' => array(
                    'placeholder' => 'So I can get back to you.'
                )
            ))
            ->add('subject', TextType::class, array(
                'attr' => array(
                    'pattern' => '.{3,}' //minlength
                )
            ))
            ->add('message', TextareaType::class, array(
                'attr' => array(
                    'cols' => 90,
                    'rows' => 10,
                )
            ))
            ->add('submit', SubmitType::class, array('label' => 'Send'))
        ;
    }
}