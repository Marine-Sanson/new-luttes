<?php

namespace App\Form;

use App\Model\EventToManage;
use App\Entity\EventCategory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class EventToManageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
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
                'label' => 'Description publique',
                'required' => false
            ])
            ->add('status', ChoiceType::class, [
                'multiple' => false,
                'expanded' => true,
                'choices'  => [
                    'Privé' => 1,
                    'Public' => 0,
                ],
                'attr' => [
                    'class' => 'form-control mb-3 border-0'
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
            'data_class' => EventToManage::class,
        ]);
    }
}
