<?php

namespace App\Entity;

use App\Repository\PokemonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PokemonRepository::class)]
class Pokemon
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['pokemon:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['pokemon:read'])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups(['pokemon:read'])]
    private ?string $type = null;

    #[ORM\Column(length: 255)]
    #[Groups(['pokemon:read'])]
    private ?string $image = null;

    #[ORM\OneToMany(targetEntity: Pokedex::class, mappedBy: 'pokemon')]
    private Collection $pokedexes;

    #[ORM\Column]
    #[Groups(['pokemon:read'])]
    private ?int $number = null;

    #[ORM\OneToOne(targetEntity: self::class, cascade: ['persist', 'remove'])]
    #[Groups(['pokemon:read'])]
    private ?self $pokemon_evolution = null;

    public function __construct()
    {
        $this->pokedexes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection<int, Pokedex>
     */
    public function getPokedexes(): Collection
    {
        return $this->pokedexes;
    }

    public function addPokedex(Pokedex $pokedex): static
    {
        if (!$this->pokedexes->contains($pokedex)) {
            $this->pokedexes->add($pokedex);
            $pokedex->setPokemon($this);
        }

        return $this;
    }

    public function removePokedex(Pokedex $pokedex): static
    {
        if ($this->pokedexes->removeElement($pokedex)) {
            // set the owning side to null (unless already changed)
            if ($pokedex->getPokemon() === $this) {
                $pokedex->setPokemon(null);
            }
        }

        return $this;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): static
    {
        $this->number = $number;

        return $this;
    }

    public function getPokemonEvolution(): ?self
    {
        return $this->pokemon_evolution;
    }

    public function setPokemonEvolution(?self $pokemon_evolution): static
    {
        $this->pokemon_evolution = $pokemon_evolution;

        return $this;
    }

}
