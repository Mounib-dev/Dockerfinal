<?php

namespace App\Form;

use App\Entity\Author;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AuthorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'Prénom:',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Michael'
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom:', 
                'required' => true,
                'attr' => [
                    'placeholder' => 'Jackson'
                ]
            ])
            ->add('date_of_birth', DateType::class, [
                'label' => 'Date de naissance', 
                'required' => true,
                'widget' => 'single_text'
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Author::class,
        ]);
    }
}
