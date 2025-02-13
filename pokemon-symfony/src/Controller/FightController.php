<?php

namespace App\Controller;

use App\Entity\Fight;
use App\Form\Fight1Type;
use App\Form\FightType;
use App\Repository\FightRepository;
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
        if ($this->isCsrfTokenValid('delete'.$fight->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($fight);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_fight_index', [], Response::HTTP_SEE_OTHER);
    }
}