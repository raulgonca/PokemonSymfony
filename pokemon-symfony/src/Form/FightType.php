<?php

namespace App\Form;

use App\Entity\Fight;
use App\Entity\Pokedex;
use App\Entity\Pokemon;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FightType extends AbstractType
{

    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $this->security->getUser();

        $builder
            ->add('userPokedex', EntityType::class, [
                'class' => Pokedex::class,
                'choice_label' => 'pokemon.name', 
                'query_builder' => function (EntityRepository $er) use ($user) {
                    return $er->createQueryBuilder('pokedex')
                        ->join('pokedex.pokemon', 'pokemon')
                        ->where('pokedex.user = :user')
                        ->setParameter('user', $user);
                },
                'placeholder' => 'Selecciona tu Pokémon',
            ]);
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Fight::class,
        ]);
    }
}
