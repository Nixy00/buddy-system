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
                'titre' => 'Soirée d\'intégration Internationale',
                'description' => 'Venez rencontrer les étudiants internationaux autour d\'un buffet !',
                'lieu' => 'Campus Croix-Rouge, Reims',
                'date_debut' => new \DateTime('2024-10-15 20:00:00'),
                'date_fin' => new \DateTime('2024-10-15 23:59:00'),
                'date_limite_inscr' => new \DateTime('2024-10-14 23:59:00'),
                'capacite' => 50,
                'statut' => 'COMPLET',
                'category' => 'Intégration', // Champ Mock pour visuel
                'image' => 'event_integration.jpg' // Champ Mock pour visuel
            ],
            [
                'id' => 2,
                'titre' => 'Visite des Caves Pommery',
                'description' => 'Découverte du patrimoine champenois avec dégustation.',
                'lieu' => 'Domaine Pommery',
                'date_debut' => new \DateTime('2024-10-20 14:00:00'),
                'date_fin' => new \DateTime('2024-10-20 17:00:00'),
                'date_limite_inscr' => new \DateTime('2024-10-18 12:00:00'),
                'capacite' => 20,
                'statut' => 'OUVERT',
                'category' => 'Culture',
                'image' => 'event_pommery.jpg'
            ],
            [
                'id' => 3,
                'titre' => 'Atelier Cuisine du Monde',
                'description' => 'Apprenez à préparer des plats typiques de différents pays.',
                'lieu' => 'Maison des Étudiants',
                'date_debut' => new \DateTime('2024-10-25 18:30:00'),
                'date_fin' => new \DateTime('2024-10-25 21:30:00'),
                'date_limite_inscr' => new \DateTime('2024-10-23 23:59:00'),
                'capacite' => 15,
                'statut' => 'OUVERT',
                'category' => 'Loisirs',
                'image' => 'event_cuisine.jpg'
            ],
            [
                'id' => 4,
                'titre' => 'Tournoi de Futsal Inter-promo',
                'description' => 'Formez vos équipes et venez affronter les autres départements !',
                'lieu' => 'Gymnase Universitaire',
                'date_debut' => new \DateTime('2024-11-05 19:00:00'),
                'date_fin' => new \DateTime('2024-11-05 22:00:00'),
                'date_limite_inscr' => new \DateTime('2024-11-04 18:00:00'),
                'capacite' => 40,
                'statut' => 'OUVERT',
                'category' => 'Sport',
                'image' => 'event_futsal.jpg'
            ],
            [
                'id' => 5,
                'titre' => 'Conférence : L\'IA et l\'Éducation',
                'description' => 'Débat sur l\'impact de l\'intelligence artificielle dans le milieu universitaire.',
                'lieu' => 'Amphi 10, UFR Sciences',
                'date_debut' => new \DateTime('2024-11-12 17:30:00'),
                'date_fin' => new \DateTime('2024-11-12 19:30:00'),
                'date_limite_inscr' => new \DateTime('2024-11-11 23:59:00'),
                'capacite' => 100,
                'statut' => 'A_VENIR',
                'category' => 'Conférence',
                'image' => 'event_conference.jpg'
            ],
            [
                'id' => 6,
                'titre' => 'Soirée Jeux de Société',
                'description' => 'Venez défier vos amis autour d\'un verre !',
                'lieu' => 'Bar Le K',
                'date_debut' => new \DateTime('2024-11-18 20:00:00'),
                'date_fin' => new \DateTime('2024-11-18 23:00:00'),
                'date_limite_inscr' => new \DateTime('2024-11-17 18:00:00'),
                'capacite' => 30,
                'statut' => 'OUVERT',
                'category' => 'Loisirs',
                'image' => 'event_jeux.jpg'
            ],
        ];

        return $this->render('events/index.html.twig', [
            'events' => $events,
        ]);
    }
}
