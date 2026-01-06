<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Form\EvenementType;
use App\Repository\EvenementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
        #[MapQueryParameter] string $search = '',
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
                return false !== stripos($event['titre'], $search)
                    || false !== stripos($event['description'], $search)
                    || false !== stripos($event['lieu'], $search);
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
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $evenement = new Evenement();
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $evenement->setStatut('OUVERT');
            $evenement->setDateCrea(new \DateTime());
            $evenement->setDateModif(new \DateTime());

            $entityManager->persist($evenement);
            $entityManager->flush();

            $this->addFlash('success', 'L\'événement a été créé avec succès !');

            return $this->redirectToRoute('app_events');
        }

        return $this->render('events/create.html.twig', [
            'form' => $form->createView(),
        ]);
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
            'participants' => [],
        ];

        // Récupération des participants
        foreach ($evenement->getParticipations() as $participation) {
            $profil = $participation->getUtilisateur()->getProfil();
            if ($profil) {
                $event['participants'][] = [
                    'id' => $profil->getId(),
                    'nom' => $profil->getNom().' '.$profil->getPrenom(),
                ];
            }
        }

        return $this->render('events/show.html.twig', [
            'event' => $event,
        ]);
    }
}
