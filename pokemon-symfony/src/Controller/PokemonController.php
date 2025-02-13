<?php

namespace App\Controller;

use App\Entity\Pokemon;
use App\Form\PokemonType;
use App\Repository\PokemonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/pokemon')]
final class PokemonController extends AbstractController
{
    #[Route(name: 'app_pokemon_index', methods: ['GET'])]
    public function index(PokemonRepository $pokemonRepository): Response
    {
        return $this->render('pokemon/index.html.twig', [
            'pokemon' => $pokemonRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_pokemon_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $pokemon = new Pokemon();
        $form = $this->createForm(PokemonType::class, $pokemon);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Obtener el archivo subido
            $imageFile = $form->get('image')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                // Generar un nombre seguro para el archivo
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                // Directorio donde se guardarán las imágenes
                $brochuresDirectory = $this->getParameter('kernel.project_dir') . '/public/uploads/images';

                try {
                    // Mover el archivo al directorio de destino
                    $imageFile->move($brochuresDirectory, $newFilename);
                } catch (FileException $e) {
                    // Manejar posibles errores durante el movimiento del archivo
                    $this->addFlash('error', 'Ocurrió un error al cargar la imagen.');
                }

                // Guardar el nombre del archivo en la entidad
                $pokemon->setImage($newFilename);
            }

            // Persistir y guardar el Pokémon en la base de datos
            $entityManager->persist($pokemon);
            $entityManager->flush();

            return $this->redirectToRoute('app_pokemon_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pokemon/new.html.twig', [
            'pokemon' => $pokemon,
            'form' => $form->createView(),
        ]);
    }

    // Método que busca un pokemon al azar de la base de datos y lo devuelve a la vista
    #[Route('/showRandomPokemon', name: 'app_show_random_pokemon', methods: ['GET'])]
    public function random(PokemonRepository $pokemonRepository): Response
    {

        // Obtener todos los Pokémon de la base de datos
        $listaPokemon = $pokemonRepository->findAll();

        // Indice random para sacar pokemon random
        $randomIndex = random_int(0, count($listaPokemon) - 1);
        $pokemon = $listaPokemon[$randomIndex];

        return $this->render('main/capture.html.twig', [
            'pokemon' => $pokemon,
        ]);

        // return $this->redirectToRoute('app_capture', [
        //     'pokemon' => $pokemon,
        // ]);
    }

    #[Route('/{id}', name: 'app_pokemon_show', methods: ['GET'])]
    public function show(Pokemon $pokemon): Response
    {
        return $this->render('pokemon/show.html.twig', [
            'pokemon' => $pokemon,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_pokemon_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Pokemon $pokemon, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(PokemonType::class, $pokemon);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Manejar el archivo si se actualiza
            $imageFile = $form->get('imageFile')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                $brochuresDirectory = $this->getParameter('kernel.project_dir') . '/public/uploads/images';

                try {
                    $imageFile->move($brochuresDirectory, $newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Ocurrió un error al cargar la imagen.');
                }

                $pokemon->setImage($newFilename);
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_pokemon_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pokemon/edit.html.twig', [
            'pokemon' => $pokemon,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_pokemon_delete', methods: ['POST'])]
    public function delete(Request $request, Pokemon $pokemon, EntityManagerInterface $entityManager): Response
    {
        $token = $request->request->get('_token');
        if ($this->isCsrfTokenValid('delete' . $pokemon->getId(), $token)) {
            $entityManager->remove($pokemon);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_pokemon_index', [], Response::HTTP_SEE_OTHER);
    }
}
