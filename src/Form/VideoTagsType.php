<?php

namespace App\Form;

use App\Entity\Videos;
use App\Entity\Tags;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VideoTagsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('tags', EntityType::class, array(
            'class'=> Tags::class,
            'choice_label' => 'tags_libelle', 
            'mapped' => false, 
            'multiple' => true,
            'expanded' => true,
            'by_reference' => false,
        ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Videos::class,
        ]);
    }
}
