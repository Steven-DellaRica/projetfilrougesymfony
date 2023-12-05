<?php

namespace App\Form;

use App\Entity\Tags;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('searchBarTags', EntityType::class, [
                'class' => Tags::class,
                'placeholder' => 'What should we eat?',
                'autocomplete' => true,
            ])
            // ->add('searchBarTags', EntityType::class
            // , [
            //     'class' => Tags::class,
            //     'placeholder' => 'Choisir un tag',
            //     'choice_label' => 'tags_libelle',
            //     'autocomplete' => true,
            //     'multiple' => true,
            //     // 'attr' => [
            //     //     'class' => 'tag-content-input'
            //     // ]
            // ]
            // )
            // ->add('searchBarAuthors', TextType::class, [
            //     'required' => false,
            //     'attr' => [
            //         'placeholder' => 'Search by authors',
            //         'class' => 'tag-content-input'
            //     ]
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}
