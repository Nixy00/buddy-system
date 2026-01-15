<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Entity\Participation;
use App\Form\EvenementType;
use App\Repository\EvenementRepository;
use App\Repository\ParticipationRepository;
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
        Request $request,
        EvenementRepository $evenementRepository,
        #[MapQueryParameter] string $statut = '',
        #[MapQueryParameter] string $category = '',
        #[MapQueryParameter] string $search = '',
    ): Response {
        // Récupération depuis la BDD
        $evenementsEntities = $evenementRepository->findPublished();

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

        // Pagination : 15 événements par page
        $itemsPerPage = 15;
        $currentPage = max(1, $request->query->getInt('page', 1));
        $totalItems = count($events);
        $totalPages = ceil($totalItems / $itemsPerPage);

        // Vérifier que la page demandée est valide
        if ($currentPage > $totalPages && $totalPages > 0) {
            $currentPage = $totalPages;
        }

        // Extraire les événements de la page actuelle
        $startIndex = ($currentPage - 1) * $itemsPerPage;
        $pagedEvents = array_slice($events, $startIndex, $itemsPerPage);

        return $this->render('events/index.html.twig', [
            'events' => $pagedEvents,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'totalItems' => $totalItems,
            'statut_filtre' => $statut,
            'category_filtre' => $category,
            'search_filtre' => $search,
        ]);
    }

    #[Route('/evenements/nouveau', name: 'app_event_create')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $evenement = new Evenement();
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $evenement->setStatut('OUVERT');
            $evenement->setDateCrea(new \DateTime());
            $evenement->setDateModif(new \DateTime());

            $evenement->setCreateur($user);

            $entityManager->persist($evenement);
            $entityManager->flush();

            $this->addFlash('success', 'L\'événement a été créé avec succès !');

            return $this->redirectToRoute('app_events');
        }

        return $this->render('events/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/evenements/{id}/modifier', name: 'app_event_edit', requirements: ['id' => '\d+'])]
    public function edit(Request $request, Evenement $evenement, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted('ROLE_ADMIN') && $evenement->getCreateur() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Vous ne pouvez modifier que vos propres événements.');
        }

        $originalData = clone $evenement;

        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hasChanges = false;

            $areDifferent = function ($val1, $val2) {
                if ($val1 instanceof \DateTimeInterface && $val2 instanceof \DateTimeInterface) {
                    return $val1->format('Y-m-d H:i:s') !== $val2->format('Y-m-d H:i:s');
                }
                if (($val1 instanceof \DateTimeInterface && !$val2) || (!$val1 && $val2 instanceof \DateTimeInterface)) {
                    return true;
                }

                $str1 = str_replace("\r\n", "\n", trim((string) $val1));
                $str2 = str_replace("\r\n", "\n", trim((string) $val2));

                return $str1 !== $str2;
            };

            if ($areDifferent($evenement->getTitre(), $originalData->getTitre())) {
                $hasChanges = true;
            }
            if ($areDifferent($evenement->getCategorie(), $originalData->getCategorie())) {
                $hasChanges = true;
            }
            if ($areDifferent($evenement->getDescription(), $originalData->getDescription())) {
                $hasChanges = true;
            }
            if ($areDifferent($evenement->getLieu(), $originalData->getLieu())) {
                $hasChanges = true;
            }
            if ($areDifferent($evenement->getCapacite(), $originalData->getCapacite())) {
                $hasChanges = true;
            }
            if ($areDifferent($evenement->getDateDebut(), $originalData->getDateDebut())) {
                $hasChanges = true;
            }
            if ($areDifferent($evenement->getDateFin(), $originalData->getDateFin())) {
                $hasChanges = true;
            }
            if ($areDifferent($evenement->getDateLimiteInscr(), $originalData->getDateLimiteInscr())) {
                $hasChanges = true;
            }

            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $hasChanges = true;
            }

            if (!$hasChanges) {
                $this->addFlash('warning', 'L\'événement n\'a pas été modifié car aucune information n\'a été modifiée.');

                return $this->redirectToRoute('app_event_show', ['id' => $evenement->getId()]);
            }

            $evenement->setDateModif(new \DateTime());

            $nbInscrits = count($evenement->getParticipations());
            $nouvelleCapacite = $evenement->getCapacite();

            if ($nouvelleCapacite > $nbInscrits) {
                $evenement->setStatut('OUVERT');
            } elseif ($nouvelleCapacite <= $nbInscrits) {
                $evenement->setStatut('COMPLET');
            }

            $entityManager->flush();

            $this->addFlash('success', 'L\'événement a été modifié avec succès !');

            return $this->redirectToRoute('app_event_show', ['id' => $evenement->getId()]);
        }

        return $this->render('events/edit.html.twig', [
            'form' => $form->createView(),
            'evenement' => $evenement,
        ]);
    }

    #[Route('/evenements/{id}', name: 'app_event_show', requirements: ['id' => '\d+'])]
    public function show(int $id, EvenementRepository $evenementRepository, ParticipationRepository $participationRepository): Response
    {
        // Récupération depuis la BDD
        $evenement = $evenementRepository->find($id);

        if (!$evenement) {
            throw $this->createNotFoundException('Événement non trouvé');
        }

        $user = $this->getUser();
        $isUserRegistered = false;
        if ($user) {
            $participation = $participationRepository->findOneBy([
                'evenement' => $evenement,
                'utilisateur' => $user,
            ]);
            if ($participation) {
                $isUserRegistered = true;
            }
        }

        $createur = $evenement->getCreateur();
        $nomOrga = $createur && $createur->getProfil() ? $createur->getProfil()->getPrenom().' '.$createur->getProfil()->getNom() : 'Utilisateur #'.$createur?->getId();

        $event = [
            'id' => $evenement->getId(),
            'titre' => $evenement->getTitre(),
            'description' => $evenement->getDescription(),
            'lieu' => $evenement->getLieu(),
            'date_debut' => $evenement->getDateDebut(),
            'date_fin' => $evenement->getDateFin(),
            'date_limite_inscr' => $evenement->getDateLimiteInscr(),
            'capacite' => $evenement->getCapacite(),
            'inscrits' => count($evenement->getParticipations()),
            'statut' => $evenement->getStatut(),
            'category' => $evenement->getCategorie(),
            'organisateur' => $nomOrga,
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
            'event_entity' => $evenement,
            'isUserRegistered' => $isUserRegistered,
        ]);
    }

    #[Route('/evenements/{id}/participer', name: 'app_event_participate', methods: ['POST'])]
    public function participate(Evenement $evenement, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        if ('OUVERT' !== $evenement->getStatut()) {
            return $this->redirectToRoute('app_event_show', ['id' => $evenement->getId()]);
        }

        $participation = new Participation();
        $participation->setUtilisateur($user);
        $participation->setEvenement($evenement);
        $participation->setDateInscription(new \DateTime());
        $participation->setStatut('INSCRIT');

        $entityManager->persist($participation);
        $entityManager->flush();

        if (count($evenement->getParticipations()) >= $evenement->getCapacite()) {
            $evenement->setStatut('COMPLET');
            $entityManager->flush();
        }

        $this->addFlash('success', 'Vous êtes maintenant inscrit à l\'événement !');

        return $this->redirectToRoute('app_event_show', ['id' => $evenement->getId()]);
    }

    #[Route('/evenements/{id}/se-desinscrire', name: 'app_event_unsubscribe', methods: ['POST'])]
    public function unsubscribe(Evenement $evenement, EntityManagerInterface $entityManager, ParticipationRepository $participationRepository): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $participation = $participationRepository->findOneBy(['evenement' => $evenement, 'utilisateur' => $user]);

        if ($participation) {
            $entityManager->remove($participation);
            $entityManager->flush();

            if ('COMPLET' === $evenement->getStatut()) {
                $evenement->setStatut('OUVERT');
                $entityManager->flush();
            }

            $this->addFlash('success', 'Vous avez bien été désinscrit de l\'événement.');
        } else {
            $this->addFlash('danger', 'Vous n\'étiez pas inscrit à cet événement.');
        }

        return $this->redirectToRoute('app_event_show', ['id' => $evenement->getId()]);
    }

    #[Route('/evenements/{id}/annuler', name: 'app_event_cancel', methods: ['POST', 'GET'])]
    public function cancel(Evenement $evenement, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted('ROLE_ADMIN') && $evenement->getCreateur() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Vous ne pouvez annuler que vos propres événements.');
        }

        $evenement->setStatut('ANNULE');
        $entityManager->flush();

        $this->addFlash('success', 'L\'événement a été annulé avec succès.');

        return $this->redirectToRoute('app_event_show', ['id' => $evenement->getId()]);
    }
}
