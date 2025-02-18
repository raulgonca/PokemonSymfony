<?php

namespace App\Controller;

use App\Entity\Pokemon;
use App\Repository\PokemonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/pokemon')]
class PokemonApiController extends AbstractController
{
    #[Route('/', name: 'api_pokemon_index', methods: ['GET'])]
    public function index(PokemonRepository $pokemonRepository): JsonResponse
    {
        $pokemons = $pokemonRepository->findAll();
        return $this->json($pokemons, Response::HTTP_OK, [], ['groups' => 'pokemon:read']);
    }

    #[Route('/{id}', name: 'api_pokemon_show', methods: ['GET'])]
    public function show(Pokemon $pokemon): JsonResponse
    {
        return $this->json($pokemon, Response::HTTP_OK, [], ['groups' => 'pokemon:read']);
    }

    #[Route('/', name: 'api_pokemon_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        try {
            $pokemon = $serializer->deserialize($request->getContent(), Pokemon::class, 'json');
            $entityManager->persist($pokemon);
            $entityManager->flush();

            return $this->json($pokemon, Response::HTTP_CREATED, [], ['groups' => 'pokemon:read']);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/{id}', name: 'api_pokemon_update', methods: ['PUT'])]
    public function update(Request $request, Pokemon $pokemon, EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        try {
            $serializer->deserialize($request->getContent(), Pokemon::class, 'json', [
                'object_to_populate' => $pokemon
            ]);

            $entityManager->flush();

            return $this->json($pokemon, Response::HTTP_OK, [], ['groups' => 'pokemon:read']);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/{id}', name: 'api_pokemon_delete', methods: ['DELETE'])]
    public function delete(Pokemon $pokemon, EntityManagerInterface $entityManager): JsonResponse
    {
        try {
            $entityManager->remove($pokemon);
            $entityManager->flush();

            return $this->json(null, Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
