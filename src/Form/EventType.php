<?php

namespace App\Form;

use App\Entity\Competition;
use App\Entity\Event;
use App\Entity\Sport;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de l\'événement'
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Détails',
                'required' => false,
                'attr' => ['rows' => 5]
            ])
            ->add('startTime', DateTimeType::class, [
                'label' => 'Date et Heure de début',
                'widget' => 'single_text',
            ])
            ->add('sport', EntityType::class, [
                'class' => Sport::class,
                'choice_label' => 'name',
                'label' => 'Sport concerné',
                'placeholder' => 'Sélectionner un sport',
            ])
            ->add('competition', EntityType::class, [
                'class' => Competition::class,
                'choice_label' => 'name',
                'label' => 'Dans quelle compétition ?',
                'placeholder' => 'Sélectionner une compétition',
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
