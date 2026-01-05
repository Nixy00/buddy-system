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
        // Récupération des 3 derniers événements pour l'affichage sur la page d'accueil
        // Pour l'instant on utilise des données mockées (en attendant la base de données)
        $featuredEvents = [
            [
                'id' => 1,
                'titre' => 'Soirée d\'intégration Internationale',
                'lieu' => 'Campus Croix-Rouge, Reims',
                'date_debut' => new \DateTime('2024-10-15 20:00:00'),
                'capacite' => 50,
                'category' => 'Intégration'
            ],
            [
                'id' => 2,
                'titre' => 'Visite des Caves Pommery',
                'lieu' => 'Domaine Pommery',
                'date_debut' => new \DateTime('2024-10-20 14:00:00'),
                'capacite' => 20,
                'category' => 'Culture'
            ],
            [
                'id' => 3,
                'titre' => 'Atelier Cuisine du Monde',
                'lieu' => 'Maison des Étudiants',
                'date_debut' => new \DateTime('2024-10-25 18:30:00'),
                'capacite' => 15,
                'category' => 'Gastronomie'
            ]
        ];

        return $this->render('home/index.html.twig', [
            'events' => $featuredEvents
        ]);
    }
}

