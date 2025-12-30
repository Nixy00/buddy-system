<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EventController extends AbstractController
{
    #[Route('/evenements', name: 'app_events')]
    public function index(): Response
    {
        $events = [
            [
                'id' => 1,
                'title' => 'Soirée d\'intégration Internationale',
                'date' => new \DateTime('2024-10-15 20:00:00'),
                'location' => 'Campus Croix-Rouge, Reims',
                'description' => 'Venez rencontrer les étudiants internationaux autour d\'un buffet !',
                'places' => 50,
                'category' => 'Intégration',
                'image' => 'event_integration.jpg'
            ],
            [
                'id' => 2,
                'title' => 'Visite des Caves Pommery',
                'date' => new \DateTime('2024-10-20 14:00:00'),
                'location' => 'Domaine Pommery',
                'description' => 'Découverte du patrimoine champenois avec dégustation.',
                'places' => 20,
                'category' => 'Culture',
                'image' => 'event_pommery.jpg'
            ],
            [
                'id' => 3,
                'title' => 'Atelier Cuisine du Monde',
                'date' => new \DateTime('2024-10-25 18:30:00'),
                'location' => 'Maison des Étudiants',
                'description' => 'Apprenez à préparer des plats typiques de différents pays.',
                'places' => 15,
                'category' => 'Loisirs',
                'image' => 'event_cuisine.jpg'
            ],
            [
                'id' => 4,
                'title' => 'Tournoi de Futsal Inter-promo',
                'date' => new \DateTime('2024-11-05 19:00:00'),
                'location' => 'Gymnase Universitaire',
                'description' => 'Formez vos équipes et venez affronter les autres départements !',
                'places' => 40,
                'category' => 'Sport',
                'image' => 'event_futsal.jpg'
            ],
        ];

        return $this->render('events/index.html.twig', [
            'events' => $events,
        ]);
    }
}
