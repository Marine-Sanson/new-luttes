<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'attr' => [
                    'class' => 'form-control mb-3'
                ],
                'label' => 'Adresse email',
                'constraints' => [
                    new Email(
                        [
                            'message' => 'Vous devez entrer un email valide.'
                        ]
                    ),
                ],
            ])
            ->add('role', ChoiceType::class, [
                'choices'  => [
                    'User' => 'USER',
                    'Admin' => 'ADMIN',
                    'Dates' => 'DATES',
                    'Chants' => 'CHANTS'
                ],
                'expanded' => true,
                'multiple' => false,
                'attr' => [
                    'class' => 'form-control m-3 border-0'
                ],
                'label' => 'Role',
                'mapped' => false
            ])
            ->add('plainPassword', PasswordType::class, [
                'mapped' => false,
                'attr' => [
                    'autocomplete' => 'new-password',
                    'class' => 'form-control mb-3'
                ],
                'label' => 'Mot de passe',
                'constraints' => [
                    new NotBlank(
                        [
                            'message' => 'Ici tu dois créer un mot de passe temporaire et le transmettre à la personne',
                        ]
                    ),
                    new Length(
                        [
                            'min' => 6,
                            'minMessage' => 'Le mot de passe doit faire au moins {{ limit }} caractères',
                            'max' => 25,
                            'maxMessage' => 'Le mot de passe doit faire maximun {{ limit }} caractères',
                        ]
                    ),
                ],
            ])
            ->add('name', TextType::class, [
                'attr' => [
                    'class' => 'form-control mb-3'
                ],
                'label' => 'Nom d\'utilisateur',
                'constraints' => [
                    new NotBlank(
                        [
                            'message' => 'Ici tu dois entrer le prénom de la personne et l\'initiale de son prénom si il y a des homonymes',
                        ]
                    ),
                    new Length(
                        [
                            'min' => 2,
                            'minMessage' => 'Le prénom doit faire au moins 2 caractères',
                            'max' => 255,
                            'maxMessage' => 'Le prénom doit faire maximun {{ limit }} caractères',
                        ]
                    ),
                ],
            ])
            ->add('tel', TextType::class, [
                'attr' => [
                    'class' => 'form-control mb-3'
                ],
                'label' => 'Telephone',
                'required' => false
            ])
            ->add('agreement', ChoiceType::class, [
                'choices'  => [
                    'Non' => '0',
                    'Mail seul' => '1',
                    'Tel seul' => '3',
                    'Mail + Tel' => '2',
                ],
                'expanded' => true,
                'multiple' => false,
                'attr' => [
                    'class' => 'form-control m-3 border-0'
                ],
                'label' => 'Accord sur les coordonnées'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
