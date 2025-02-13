<?php

namespace App\Controller;

use App\Entity\Pokedex;
use App\Entity\Pokemon;
use App\Form\PokedexType;
use App\Repository\PokedexRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/pokedex')]
final class PokedexController extends AbstractController
{
    #[Route(name: 'app_pokedex_index', methods: ['GET'])]
    public function index(PokedexRepository $pokedexRepository): Response
    {
        return $this->render('main/capturados.html.twig', [
            'pokedexes' => $pokedexRepository->findPokedexesByUser($this->getUser()),
        ]);
    }

    #[Route('/new', name: 'app_pokedex_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $pokedex = new Pokedex();
        $form = $this->createForm(PokedexType::class, $pokedex);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($pokedex);
            $entityManager->flush();

            return $this->redirectToRoute('app_pokedex_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pokedex/new.html.twig', [
            'pokedex' => $pokedex,
            'form' => $form,
        ]);
    }


    #[Route('/{id}', name: 'app_pokedex_show', methods: ['GET'])]
    public function show(Pokedex $pokedex): Response
    {
        return $this->render('pokedex/show.html.twig', [
            'pokedex' => $pokedex,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_pokedex_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Pokedex $pokedex, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PokedexType::class, $pokedex);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_pokedex_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pokedex/edit.html.twig', [
            'pokedex' => $pokedex,
            'form' => $form,
        ]);
    }

    // Funcion que intenta capturar un pokemon
    #[Route('/catch/{id}', name: 'app_pokedex_catch', methods: ['GET'])]
    public function catch(Pokemon $pokemon, Request $request, EntityManagerInterface $entityManager): Response
    {
        // El pokemon tiene un 60% de probabilidad de captura
        $probabilidad = rand(0, 100);

        $resultado = 'fracaso'; // Creo una variable para mostrar el resultado de la captura
        $pokedex = new Pokedex();
        if ($probabilidad <= 60) {

            $pokedex = new Pokedex();
            $pokedex->setUser($this->getUser());
            $pokedex->setPokemon($pokemon);
            $pokedex->setPokemonLevel(1);
            $pokedex->setPokemonStrength(10);
            $pokedex->setStatus("sano");

            $entityManager->persist($pokedex);
            $entityManager->flush();

            $resultado = 'exito';
        }
        return $this->render('main/capture.html.twig', ['resultado' => $resultado, 'pokedex' => $pokedex]);
        // return $this->redirectToRoute(
        //     'app_capture',
        //     ['resultado' => $resultado]
        // );
    }

    #[Route('/{id}/train', name: 'app_pokedex_train', methods: ['GET', 'POST'])]
    public function train(Pokedex $pokedex, EntityManagerInterface $entityManager): Response
    {
        $pokedex->setPokemonStrength($pokedex->getPokemonStrength() + 10);
        $entityManager->flush();

        return $this->redirectToRoute('app_pokedex_show', ['id' => $pokedex->getId()], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}', name: 'app_pokedex_delete', methods: ['POST'])]
    public function delete(Request $request, Pokedex $pokedex, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $pokedex->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($pokedex);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_pokedex_index', [], Response::HTTP_SEE_OTHER);
    }
}
