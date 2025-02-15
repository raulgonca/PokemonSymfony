<?php

namespace App\Controller;

use App\Entity\Fight;
use App\Entity\Pokedex;
use App\Form\FightType;
use App\Repository\FightRepository;
use App\Repository\PokedexRepository;
use App\Repository\PokemonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/fight')]
final class FightController extends AbstractController
{
    #[Route(name: 'app_fight_index', methods: ['GET'])]
    public function index(FightRepository $fightRepository): Response
    {
        return $this->render('fight/index.html.twig', [
            'fights' => $fightRepository->findAll(),
        ]);
    }


    #[Route('/new', name: 'app_fight_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $fight = new Fight();
        $form = $this->createForm(FightType::class, $fight);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($fight);
            $entityManager->flush();

            return $this->redirectToRoute('app_fight_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('fight/new.html.twig', [
            'fight' => $fight,
            'form' => $form,
        ]);
    }

    #[Route('/new/ai', name: 'app_fight_new_ai', methods: ['GET', 'POST'])]
    public function newFightAI(Request $request, EntityManagerInterface $entityManager, PokemonRepository $pokemonRepository): Response
    {
        $fight = new Fight();
        $form = $this->createForm(FightType::class, $fight);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userPokedex = $fight->getPokedexPlayerOne();
            $userPokemonLevel = $userPokedex->getPokemonLevel();
            $userPokemonStrength = $userPokedex->getPokemonStrength();

            $allPokemons = $pokemonRepository->findAll();
            $pokemon = $allPokemons[array_rand($allPokemons)];

            $enemyPokemon = new Pokedex();
            $enemyPokemon->setPokemon($pokemon);
            $enemyPokemon->setPokemonLevel(1);
            $enemyPokemon->setPokemonStrength(rand(0, 5));
            $enemyPokemon->setStatus('sano');

            $entityManager->persist($enemyPokemon);
            $entityManager->flush();

            $fight->setPokedexPlayerTwo($enemyPokemon);

            $result = (($userPokemonLevel * $userPokemonStrength)) - (($enemyPokemon->getPokemonLevel() * $enemyPokemon->getPokemonStrength()));

            // Comprobar resultado
            if ($result > 0) {
                $fight->setWinner($userPokedex->getId());
                $userPokedex->setStatus('sano');
                $enemyPokemon->setStatus('malherido');
            } else {
                $fight->setWinner($enemyPokemon->getId());
                $userPokedex->setStatus('malherido');
                $enemyPokemon->setStatus('sano');
            }

            $entityManager->persist($fight);
            $entityManager->flush();

            return $this->redirectToRoute('app_fight_result', [
                'id' => $fight->getId(),
            ]);
        }

        return $this->render('fight/new.html.twig', [
            'fight' => $fight,
            'form' => $form,
        ]);
    }

    // Batalla contra usuario real
    // Implementar aquí el código para la batalla contra un usuario real
    #[Route('/new/online', name: 'app_fight_new_online', methods: ['GET', 'POST'])]
    public function newFightOnline(Request $request, EntityManagerInterface $entityManager): Response
    {
        $fight = new Fight();
        $form = $this->createForm(FightType::class, $fight);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $fight->setPokedexPlayerTwo(null); // Deja al jugador 2 en espera

            $entityManager->persist($fight);
            $entityManager->flush();

            return $this->redirectToRoute('app_main');
        }

        return $this->render('fight/new.html.twig', [
            'fight' => $fight,
            'form' => $form,
        ]);
    }

    #[Route('/join/online', name: 'app_fight_join_online', methods: ['GET', 'POST'])]
    public function joinFightOnline(Request $request, EntityManagerInterface $entityManager, FightRepository $fightRepository, PokedexRepository $pokedexRepository): Response
    {
        // Buscar una batalla pendiente (sin jugador 2)
        $fight = $fightRepository->findOneBy(['pokedex_player_two' => null]);

        if (!$fight) {
            return $this->redirectToRoute('app_fight_index');
        }

        // Obtener la lista de Pokémon del usuario actual
        $user = $this->getUser();
        $userPokedex = $pokedexRepository->findPokedexesByUser($user);

        // Manejo del formulario
        if ($request->isMethod('POST')) {
            $pokemonId = $request->request->get('pokedex_player_two');
            $pokedexPlayerTwo = $pokedexRepository->find($pokemonId);

            $fight->setPokedexPlayerTwo($pokedexPlayerTwo);

            // Calcular el resultado de la batalla
            $playerOne = $fight->getPokedexPlayerOne();
            $playerTwo = $pokedexPlayerTwo;

            $result = ($playerOne->getPokemonLevel() * $playerOne->getPokemonStrength()) -
                ($playerTwo->getPokemonLevel() * $playerTwo->getPokemonStrength());

            if ($result > 0) {
                $fight->setWinner($playerOne->getId());
                $playerOne->setStatus('sano');
                $playerTwo->setStatus('malherido');
            } else {
                $fight->setWinner($playerTwo->getId());
                $playerOne->setStatus('malherido');
                $playerTwo->setStatus('sano');
            }

            // Persistir los cambios
            $entityManager->persist($fight);
            $entityManager->persist($playerOne);
            $entityManager->persist($playerTwo);
            $entityManager->flush();

            return $this->redirectToRoute('app_fight_result', ['id' => $fight->getId()]);
        }

        // Renderizar la vista con el formulario manual
        return $this->render('fight/join_online.html.twig', [
            'fight' => $fight,
            'userPokedex' => $userPokedex,
        ]);
    }
    #[Route('/fight/result/{id}', name: 'app_fight_result')]
    public function fightResult(Fight $fight): Response
    {
        return $this->render('fight/result_combat.html.twig', [
            'fight' => $fight,
            'userPokemon' => $fight->getPokedexPlayerOne()->getPokemon(),
            'enemyPokemon' => $fight->getPokedexPlayerTwo()->getPokemon(),
        ]);
    }

    #[Route('/{id}', name: 'app_fight_show', methods: ['GET'])]
    public function show(Fight $fight): Response
    {
        return $this->render('fight/show.html.twig', [
            'fight' => $fight,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_fight_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Fight $fight, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FightType::class, $fight);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_fight_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('fight/edit.html.twig', [
            'fight' => $fight,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_fight_delete', methods: ['POST'])]
    public function delete(Request $request, Fight $fight, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $fight->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($fight);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_fight_index', [], Response::HTTP_SEE_OTHER);
    }
}
