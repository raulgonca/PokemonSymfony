<?php

namespace App\Entity;

use App\Repository\FightRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FightRepository::class)]
class Fight
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'fights')]
    private ?Pokedex $userPokedex = null;

    #[ORM\ManyToOne(inversedBy: 'fights')]
    private ?Pokemon $enemyPokemon = null;

    #[ORM\Column(length: 255)]
    private ?string $winner = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserPokedex(): ?Pokedex
    {
        return $this->userPokedex;
    }

    public function setUserPokedex(?Pokedex $userPokedex): static
    {
        $this->userPokedex = $userPokedex;

        return $this;
    }

    public function getEnemyPokemon(): ?Pokemon
    {
        return $this->enemyPokemon;
    }

    public function setEnemyPokemon(?Pokemon $enemyPokemon): static
    {
        $this->enemyPokemon = $enemyPokemon;

        return $this;
    }

    public function getWinner(): ?string
    {
        return $this->winner;
    }

    public function setWinner(string $winner): static
    {
        $this->winner = $winner;

        return $this;
    }
}
