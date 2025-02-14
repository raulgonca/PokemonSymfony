<?php

namespace App\Controller;

use App\Repository\FightRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MainController extends AbstractController
{

    #[Route('/', name: 'app_main')]
    public function index(FightRepository $fightRepository): Response
    {
        $openFights = $fightRepository->findBy(['pokedex_player_two' => null]);

        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
            'openFights' => $openFights
        ]);
    }

    // Ir a la vista capture.html.twig
    #[Route('/capture', name: 'app_capture')]
    public function capture(): Response
    {
        return $this->render('main/capture.html.twig');
    }
}
