<?php

namespace App\Form;

use App\Entity\Championship;
use App\Entity\Sport;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChampionshipType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du championnat'
            ])
            ->add('type', TextType::class, [
                'label' => 'Type'
            ])
            ->add('sport', EntityType::class, [
                'class' => Sport::class,
                'choice_label' => 'name',
                'label' => 'Sport associé',
                'placeholder' => 'Choisir un sport...',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Championship::class,
        ]);
    }
}
