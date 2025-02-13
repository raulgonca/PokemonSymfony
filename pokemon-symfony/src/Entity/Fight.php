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
    private ?Pokedex $pokedex_player_one = null;

    #[ORM\ManyToOne(inversedBy: 'fights')]
    private ?Pokedex $pokedex_player_two = null;

    #[ORM\Column(length: 255)]
    private ?string $winner = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPokedexPlayerOne(): ?Pokedex
    {
        return $this->pokedex_player_one;
    }

    public function setPokedexPlayerOne(?Pokedex $pokedex_player_one): static
    {
        $this->pokedex_player_one = $pokedex_player_one;

        return $this;
    }

    public function getPokedexPlayerTwo(): ?Pokedex
    {
        return $this->pokedex_player_two;
    }

    public function setPokedexPlayerTwo(?Pokedex $pokedex_player_two): static
    {
        $this->pokedex_player_two = $pokedex_player_two;

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
