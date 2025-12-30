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
        ];

        return $this->render('events/index.html.twig', [
            'events' => $events,
        ]);
    }
}
