<?php

namespace App\Form;

use App\Entity\Videos;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;


use Symfony\Component\OptionsResolver\OptionsResolver;

class VideoIDType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('video_id', TextType::class, [
                'label' => 'Lien de la vidéo :',
                'attr' => [
                    'class' => 'videoIdclass',
                    'id' => 'video_id_input'
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Videos::class,
        ]);
    }
}
