<?php

namespace App\Controller;

use App\Repository\EvenementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(EvenementRepository $evenementRepository): Response
    {
        // Récupération des événements à venir depuis la base de données
        $featuredEvents = $evenementRepository->findUpcoming(3);

        return $this->render('home/index.html.twig', [
            'events' => $featuredEvents
        ]);
    }
}

