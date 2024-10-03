<?php

namespace App\Form;

use App\Entity\Song;
use App\Entity\Voice;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class VoiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type', ChoiceType::class, [
                'attr' => [
                    'class' => 'form-control mb-3'
                ],
                'label' => 'Type de voix',
                'mapped' => false,
                'required' => true,
                'multiple' => false,
                'expanded' => true,
                'choices'  => [
                    'Aiguë' => 'aigue',
                    'Médium' => 'medium',
                    'Grave' => 'grave',
                    'Tutti' => 'tutti',
                    'Autre' => 'autre',
                ],
            ])
            ->add('other', TextType::class, [
                'attr' => [
                    'class' => 'form-control mb-3'
                ],
                'label' => 'Si autre, précise',
                'mapped' => false,
                'required' => false,
            ])
            ->add('voice', FileType::class, [
                'attr' => [
                    'class' => 'form-control mb-3'
                ],
                'label' => 'Fichier (en mp3, mp4 ou mpeg)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '40M',
                        'maxSizeMessage' => 'Attention, le fichier ne doit pas faire plus de {{ limit }} megabyte',
                        'mimeTypes' => [
                            'audio/mp3',
                            'video/mp4',
                            'audio/mpeg',
                        ],
                        'mimeTypesMessage' => 'Merci de choisir un format valide : ficher mp3, mp4 ou mpeg',
                    ])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Voice::class,
        ]);
    }
}
