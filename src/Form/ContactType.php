<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('mail', EmailType::class, [
                'attr' => [
                    'class' => 'form-control mb-3'
                ],
                'label' => 'Entrez votre email',
                'constraints' => [
                    new NotBlank(
                        [
                            'message' => 'Merci d\'entrer un email valide',
                        ]
                    ),
                    new Length(
                        [
                            'min' => 8,
                            'minMessage' => 'L\'email doit faire au moins {{ limit }} caractères',
                            'max' => 255,
                            'maxMessage' => 'L\'email doit faire maximun {{ limit }} caractères',
                        ]
                    ),
                ]
            ])
            ->add('object', TextType::class, [
                'attr' => [
                    'class' => 'form-control mb-3'
                ],
                'label' => 'L\'objet de votre message',
                'constraints' => [
                    new NotBlank(
                        [
                            'message' => 'Merci de mettre un objet à votre message',
                        ]
                    ),
                    new Length(
                        [
                            'min' => 5,
                            'minMessage' => 'L\'objet doit faire au moins {{ limit }} caractères',
                            'max' => 255,
                            'maxMessage' => 'L\'objet doit faire maximun {{ limit }} caractères',
                        ]
                    ),
                ]
            ])
            ->add('content', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control mb-3'
                ],
                'label' => 'Le contenu de votre message',
                'constraints' => [
                    new NotBlank(
                        [
                            'message' => 'Merci de rentrer un message',
                        ]
                    ),
                    new Length(
                        [
                            'min' => 5,
                            'minMessage' => 'Le message doit faire au moins {{ limit }} caractères',
                        ]
                    ),
                ]
            ])
            ->add('result', IntegerType::class, [
                'mapped' => false,
                'label' => 'Résultat',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
