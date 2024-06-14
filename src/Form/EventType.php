<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\EventCategory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateprov', DateType::class, [
                'attr' => [
                    'class' => 'form-control mb-3'
                ],
                'label' => 'Date de l\'évenement',
                'mapped' => false,
            ])
            ->add('privateDetails', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control mb-3'
                ],
                'label' => 'Description pour les membres'
            ])
            ->add('publicDetails', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control mb-3'
                ],
                'label' => 'Description publique'
            ])
            ->add('status', ChoiceType::class, [
                'multiple' => false,
                'expanded' => true,
                'choices'  => [
                    'publique' => 0,
                    'privé' => 1,
                ],
                'attr' => [
                    'class' => 'form-control mb-3'
                ],
                'label' => 'Status'
            ])
            ->add('EventCategory', EntityType::class, [
                'class' => EventCategory::class,
                'choice_label' => 'name',
                'attr' => [
                    'class' => 'form-control mb-3'
                ],
                'label' => 'Catégorie de l\'évenement',
                'placeholder' => 'Choisir ici...',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
