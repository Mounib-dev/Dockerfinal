<?php

namespace App\Form;

use App\Entity\Book;
use App\Entity\Author;
use App\Entity\Category;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Intitulé',
                'required' => true,
                'attr' => [
                    'placeholder' => '12 Rules of Life'
                ]
            ])
            ->add('isbestseller', CheckboxType::class, [
                'label' => 'Est-ce un bestseller?',
                'required' => false,
            ])
            ->add('categories', EntityType::class, [
                'label' => 'Catégorie',
                'required' => true,
                'class' => Category::class,
                'choice_label' => 'name',
                'query_builder' => function(EntityRepository $er): QueryBuilder {
                    return $er->createQueryBuilder('cat')
                        ->orderBy('cat.name', 'ASC');
                },
                'expanded' => false,
                'multiple' => true,
                'by_reference' => false,
            ])
            ->add('author', EntityType::class, [
                'label' => 'Auteur du Livre',
                'required' => true,
                'class' => Author::class,
                'choice_label' => 'lastname',
                'query_builder' => function(EntityRepository $er): QueryBuilder {
                    return $er->createQueryBuilder('aut')
                        ->orderBy('aut.firstname', 'ASC');
                },
                'expanded' => false,
                'multiple' => false,
                'by_reference' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
