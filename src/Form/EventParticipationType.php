<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Event;
use App\Entity\Status;
use App\Entity\Participation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class EventParticipationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('status', ChoiceType::class, [
                'multiple' => false,
                'expanded' => true,
                'choices'  => [
                    'Oui' => 'oui',
                    'Non' => 'non',
                    'Ne sais pas' => 'nsp',
                ],
                'attr' => [
                    'class' => 'form-control mb-3 border-0'
                ],
                'label' => false,
                'mapped' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Participation::class,
        ]);
    }
}
