<?php

namespace App\Form;

use App\Entity\Videos;
use App\Entity\Tags;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VideosType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('video_id')
            ->add('video_title')
            ->add('tags', EntityType::class, [
                'class'=> Tags::class,
                'choice_label' => 'tags_libelle', 
                'mapped' => false, 
                'multiple' => true 
            ])
            ->add('video_author')
            ->add('video_views')
            ->add('video_date')
            ->add('video_thumbnail')
            ->add('video_timecode')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Videos::class,
        ]);
    }
}
