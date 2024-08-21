<?php

namespace App\Form;

use App\Entity\ChatItem;
use App\Entity\ChatAnswer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ChatAnswerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('content', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control mb-3'
                ],
                'label' => 'Ta réponse :',
                'constraints' => [
                    new NotBlank(
                        [
                            'message' => 'Ici tu dois entrer ton message',
                        ]
                    ),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ChatAnswer::class,
        ]);
    }
}
