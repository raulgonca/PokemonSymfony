<?php

namespace App\Entity;

use App\Repository\PokedexRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PokedexRepository::class)]
class Pokedex
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'pokedexes')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'pokedexes')]
    private ?Pokemon $pokemon = null;

    #[ORM\Column]
    private ?int $pokemonLevel = null;

    #[ORM\Column]
    private ?int $pokemonStrength = null;

 
    #[ORM\Column(length: 255)]
    private ?string $status = null;

    /**
     * @var Collection<int, Fight>
     */
    #[ORM\OneToMany(targetEntity: Fight::class, mappedBy: 'pokedex_player_one')]
    private Collection $fights;

    public function __construct()
    {
        $this->fights = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getPokemon(): ?Pokemon
    {
        return $this->pokemon;
    }

    public function setPokemon(?Pokemon $pokemon): static
    {
        $this->pokemon = $pokemon;

        return $this;
    }

    public function getPokemonLevel(): ?int
    {
        return $this->pokemonLevel;
    }

    public function setPokemonLevel(int $pokemonLevel): static
    {
        $this->pokemonLevel = $pokemonLevel;

        return $this;
    }

    public function getPokemonStrength(): ?int
    {
        return $this->pokemonStrength;
    }

    public function setPokemonStrength(int $pokemonStrength): static
    {
        $this->pokemonStrength = $pokemonStrength;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection<int, Fight>
     */
    public function getFights(): Collection
    {
        return $this->fights;
    }

    public function addFight(Fight $fight): static
    {
        if (!$this->fights->contains($fight)) {
            $this->fights->add($fight);
            $fight->setPokedexPlayerOne($this);
        }

        return $this;
    }

    public function removeFight(Fight $fight): static
    {
        if ($this->fights->removeElement($fight)) {
            // set the owning side to null (unless already changed)
            if ($fight->getPokedexPlayerOne() === $this) {
                $fight->setPokedexPlayerOne(null);
            }
        }

        return $this;
    }
}
