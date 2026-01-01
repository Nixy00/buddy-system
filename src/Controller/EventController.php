<?php

namespace App\Controller;

use App\Repository\EvenementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

class EventController extends AbstractController
{
    #[Route('/evenements', name: 'app_events')]
    public function index(
        EvenementRepository $evenementRepository,
        #[MapQueryParameter] string $statut = '',
        #[MapQueryParameter] string $category = '',
        #[MapQueryParameter] string $search = ''
    ): Response {
        // Récupération depuis la BDD
        $evenementsEntities = $evenementRepository->findAll();

        // Conversion en tableaux pour compatibilité template
        $events = [];
        foreach ($evenementsEntities as $evenement) {
            $events[] = [
                'id' => $evenement->getId(),
                'titre' => $evenement->getTitre(),
                'description' => $evenement->getDescription(),
                'lieu' => $evenement->getLieu(),
                'date_debut' => $evenement->getDateDebut(),
                'date_fin' => $evenement->getDateFin(),
                'capacite' => $evenement->getCapacite(),
                'inscrits' => count($evenement->getParticipations()),
                'statut' => $evenement->getStatut(),
                'category' => $evenement->getCategorie(),
            ];
        }

        // Anciens mocks (supprimés)
        $oldMocks = [
            [
                'id' => 1,
                'titre' => 'Soirée d\'intégration Internationale',
                'description' => 'Venez rencontrer les étudiants internationaux autour d\'un buffet !',
                'lieu' => 'Campus Croix-Rouge, Reims',
                'date_debut' => new \DateTime('2024-10-15 20:00:00'),
                'date_fin' => new \DateTime('2024-10-15 23:59:00'),
                'capacite' => 50,
                'inscrits' => 50,
                'statut' => 'COMPLET',
                'category' => 'Intégration',
                'image' => 'event_integration.jpg'
            ],
            [
                'id' => 2,
                'titre' => 'Visite des Caves Pommery',
                'description' => 'Découverte du patrimoine champenois avec dégustation.',
                'lieu' => 'Domaine Pommery',
                'date_debut' => new \DateTime('2024-10-20 14:00:00'),
                'date_fin' => new \DateTime('2024-10-20 17:00:00'),
                'capacite' => 20,
                'inscrits' => 12,
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
                'capacite' => 15,
                'inscrits' => 8,
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
                'capacite' => 40,
                'inscrits' => 25,
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
                'capacite' => 100,
                'inscrits' => 0,
                'statut' => 'OUVERT',
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
                'capacite' => 30,
                'inscrits' => 18,
                'statut' => 'OUVERT',
                'category' => 'Loisirs',
                'image' => 'event_jeux.jpg'
            ],
        ];

        // Filtre par statut si demandé
        if (!empty($statut)) {
            $events = array_filter($events, function ($event) use ($statut) {
                return $event['statut'] === $statut;
            });
        }

        // Filtre par catégorie si demandé
        if (!empty($category)) {
            $events = array_filter($events, function ($event) use ($category) {
                return $event['category'] === $category;
            });
        }

        // Filtre par recherche textuelle si demandé
        if (!empty($search)) {
            $events = array_filter($events, function ($event) use ($search) {
                return stripos($event['titre'], $search) !== false
                    || stripos($event['description'], $search) !== false
                    || stripos($event['lieu'], $search) !== false;
            });
        }

        // Tri par date (prochains événements en premier)
        usort($events, function ($a, $b) {
            return $a['date_debut'] <=> $b['date_debut'];
        });

        return $this->render('events/index.html.twig', [
            'events' => $events,
            'statut_filtre' => $statut,
            'category_filtre' => $category,
            'search_filtre' => $search,
        ]);
    }
    #[Route('/evenements/nouveau', name: 'app_event_create')]
    public function create(): Response
    {
        // TODO: Implémenter le formulaire de création d'événement
        return $this->render('events/create.html.twig');
    }

    #[Route('/evenements/{id}', name: 'app_event_show', requirements: ['id' => '\d+'])]
    public function show(int $id, EvenementRepository $evenementRepository): Response
    {
        // Récupération depuis la BDD
        $evenement = $evenementRepository->find($id);

        if (!$evenement) {
            throw $this->createNotFoundException('Événement non trouvé');
        }

        // Conversion pour compatibilité template
        $event = [
            'id' => $evenement->getId(),
            'titre' => $evenement->getTitre(),
            'description' => $evenement->getDescription(),
            'lieu' => $evenement->getLieu(),
            'date_debut' => $evenement->getDateDebut(),
            'date_fin' => $evenement->getDateFin(),
            'capacite' => $evenement->getCapacite(),
            'inscrits' => count($evenement->getParticipations()),
            'statut' => $evenement->getStatut(),
            'category' => $evenement->getCategorie(),
            'organisateur' => 'À définir', // TODO: Récupérer l'organisateur via la relation
            'participants' => []
        ];

        // Récupération des participants
        foreach ($evenement->getParticipations() as $participation) {
            $profil = $participation->getUtilisateur()->getProfil();
            if ($profil) {
                $event['participants'][] = [
                    'nom' => $profil->getNom() . ' ' . $profil->getPrenom()
                ];
            }
        }

        return $this->render('events/show.html.twig', [
            'event' => $event,
        ]);
    }
}

