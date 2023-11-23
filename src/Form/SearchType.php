<?php

namespace App\Form;

use App\Entity\Videos;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('searchBarTags', TextType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Search by tag',
                    'class' => 'tag-content-input'
                ]
            ])
            ->add('searchBarAuthors', TextType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Search by authors',
                    'class' => 'tag-content-input'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}
