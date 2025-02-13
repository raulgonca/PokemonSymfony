<?php

namespace App\Form;

use App\Entity\Fight;
use App\Entity\Pokedex;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FightType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('winner')
            ->add('pokedex_player_one', EntityType::class, [
                'class' => Pokedex::class,
'choice_label' => 'id',
            ])
            ->add('pokedex_player_two', EntityType::class, [
                'class' => Pokedex::class,
'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Fight::class,
        ]);
    }
}