<?php

namespace App\Form;

use App\Model\SongDetailsToUpdate;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class SongDetailsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => [
                    'class' => 'form-control mb-3'
                ],
                'label' => 'Titre',
                'constraints' => [
                    new NotBlank(
                        [
                            'message' => 'Ici tu dois entrer le titre de la chanson',
                        ]
                    ),
                    new Length(
                        [
                            'min' => 2,
                            'minMessage' => 'Le titre doit faire au moins 2 caractères',
                            'max' => 255,
                            'maxMessage' => 'Le titre doit faire maximun {{ limit }} caractères',
                        ]
                    ),
                ],
            ])
            ->add('description', TextType::class, [
                'attr' => [
                    'class' => 'form-control mb-3'
                ],
                'label' => 'Description',
                'required' => false
            ])
            ->add('currentSong', ChoiceType::class, [
                'choices'  => [
                    'Oui' => 1,
                    'Non' => 0,
                ],
                'expanded' => true,
                'multiple' => false,
                'attr' => [
                    'class' => 'form-control m-3 border-0'
                ],
                'label' => 'Chant du moment',
                ])
            ->add('categoryId', ChoiceType::class, [
                'choices'  => [
                    'Aucune pour le moment' => null,
                    'Année en cours' => 1,
                    'Chants de manif / chants communs' => 2,
                    'Chants années précédentes' => 3,
                    'Livrets' => 4,
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SongDetailsToUpdate::class,
        ]);
    }
}
