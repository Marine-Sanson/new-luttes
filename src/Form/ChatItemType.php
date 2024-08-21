<?php

namespace App\Form;

use App\Entity\ChatItem;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ChatItemType extends AbstractType
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
                            'message' => 'Ici tu dois entrer le titre de ton message',
                        ]
                    ),
                    new Length(
                        [
                            'min' => 2,
                            'minMessage' => 'Le titre doit faire au moins {{ limit }} caractères',
                            'max' => 255,
                            'maxMessage' => 'Le titre doit faire maximun {{ limit }} caractères',
                        ]
                    ),
                ],
            ])
            ->add('message', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control mb-3'
                ],
                'label' => 'Contenu de ton message'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ChatItem::class,
        ]);
    }
}
