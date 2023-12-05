<?php

namespace App\Form;

use App\Entity\Tags;
use App\Repository\TagsRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\Autocomplete\Form\AsEntityAutocompleteField;
use Symfony\UX\Autocomplete\Form\BaseEntityAutocompleteType;
use Symfony\UX\Autocomplete\Form\ParentEntityAutocompleteType;

#[AsEntityAutocompleteField]
class TagsAutocompleteField extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => Tags::class,
            'placeholder' => 'Search a tag',
            'choice_label' => 'tags_libelle',
            'multiple' => true,

            'query_builder' => function(TagsRepository $tagsRepository) {
                return $tagsRepository->createQueryBuilder('tags');
            },
            //'security' => 'ROLE_SOMETHING',
        ]);
    }

    public function getParent(): string
    {
        return BaseEntityAutocompleteType::class;
        // return ParentEntityAutocompleteType::class;
    }
}
