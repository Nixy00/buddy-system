<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\Signalement;
use App\Form\SignalementTraiteType;
use App\Form\SignalementType;
use App\Repository\SignalementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SignalementController extends AbstractController
{
    #[Route('/signalements', name: 'app_signalement_index', methods: ['GET'])]
    public function index(Request $request, SignalementRepository $signalementRepository): Response
    {
        $statut = $request->query->get('statut');
        $motif = $request->query->get('motif');

        $criteria = [];
        if ($statut) {
            $criteria['statut'] = $statut;
        }
        if ($motif) {

            $signalements = $signalementRepository->createQueryBuilder('s')
                ->where('s.motif LIKE :motif')
                ->setParameter('motif', $motif.'%')
                ->orderBy('s.dateSignalement', 'DESC')
                ->getQuery()
                ->getResult();
        } else {
            $signalements = $signalementRepository->findBy($criteria, ['dateSignalement' => 'DESC']);
        }

        $allMotifs = $signalementRepository->createQueryBuilder('s')
            ->select('s.motif')
            ->distinct(true)
            ->getQuery()
            ->getResult();

        $motifsUniques = [];
        foreach ($allMotifs as $item) {
            $m = $item['motif'];
            if (preg_match('/^(.*) \(.*\)$/', $m, $matches)) {
                $motifsUniques[] = $matches[1];
            } else {
                $motifsUniques[] = $m;
            }
        }
        $motifsUniques = array_unique($motifsUniques);

        return $this->render('signalement/index.html.twig', [
            'signalements' => $signalements,
            'statuts' => ['EN_ATTENTE', 'EN_COURS', 'TRAITE', 'REJETE'],
            'motifs' => $motifsUniques,
            'statut_filtre' => $statut,
            'motif_filtre' => $motif,
        ]);
    }

    #[Route('/signalement/{id}', name: 'app_signalement_show', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function show(Request $request, Signalement $signalement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SignalementTraiteType::class, $signalement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Le signalement a été mis à jour.');

            return $this->redirectToRoute('app_signalement_index');
        }

        return $this->render('signalement/show.html.twig', [
            'signalement' => $signalement,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/signalement/{id}/modifier', name: 'app_signalement_edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function edit(Request $request, Signalement $signalement, EntityManagerInterface $entityManager): Response
    {
        $originalMotif = $signalement->getMotif();
        $initialDetails = '';

        if (preg_match('/^(.*) \((.*)\)$/', $originalMotif, $matches)) {
            $mainMotif = $matches[1];
            $details = $matches[2];

            $signalement->setMotif($mainMotif);
            $initialDetails = $details;
        }

        $form = $this->createForm(SignalementType::class, $signalement);
        $form->get('details')->setData($initialDetails);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $detailsFromForm = $form->get('details')->getData();
            $motifFromEntity = $signalement->getMotif();

            $finalMotif = $motifFromEntity;
            if (!empty(trim((string) $detailsFromForm))) {
                $finalMotif = $motifFromEntity.' ('.$detailsFromForm.')';
            }

            if ($finalMotif === $originalMotif) {
                $this->addFlash('warning', 'Le signalement n\'a pas été modifié car aucune information n\'a été modifiée.');

                return $this->redirectToRoute('app_signalement_show', ['id' => $signalement->getId()]);
            }

            if ('Autre' === $motifFromEntity && empty(trim((string) $detailsFromForm))) {
                $form->get('details')->addError(new FormError('Veuillez préciser le motif du signalement.'));
            } else {
                $signalement->setMotif($finalMotif);

                $entityManager->flush();
                $this->addFlash('success', 'Votre signalement a été modifié avec succès.');

                return $this->redirectToRoute('app_signalement_show', ['id' => $signalement->getId()]);
            }
        }

        return $this->render('signalement/edit.html.twig', [
            'form' => $form->createView(),
            'signalement' => $signalement,
        ]);
    }

    #[Route('/signalement/{id}/fermer', name: 'app_signalement_close', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function closeTicket(Signalement $signalement, EntityManagerInterface $entityManager): Response
    {
        $signalement->setStatut('TRAITE');
        $entityManager->flush();

        $this->addFlash('success', 'Le ticket a été fermé.');

        return $this->redirectToRoute('app_signalement_index');
    }

    #[Route('/signalement/{id}/rouvrir', name: 'app_signalement_reopen', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function reopenTicket(Signalement $signalement, EntityManagerInterface $entityManager): Response
    {
        $signalement->setStatut('EN_COURS');
        $entityManager->flush();

        $this->addFlash('success', 'Le ticket a été rouvert.');

        return $this->redirectToRoute('app_signalement_show', ['id' => $signalement->getId()]);
    }

    #[Route('/signalement/nouveau/{id}', name: 'app_signalement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, Message $message, EntityManagerInterface $entityManager, SignalementRepository $signalementRepository): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $existingSignalement = $signalementRepository->findOneBy([
            'message' => $message,
            'utilisateur' => $user,
        ]);

        if ($existingSignalement) {
            $this->addFlash('warning', 'Vous avez déjà signalé ce message.');

            return $this->redirectToRoute('app_messagerie_show', ['id' => $message->getConversation()->getId()]);
        }

        $signalement = new Signalement();
        $signalement->setMessage($message);
        $signalement->setUtilisateur($user);
        $signalement->setDateSignalement(new \DateTime());
        $signalement->setStatut('EN_ATTENTE');

        $form = $this->createForm(SignalementType::class, $signalement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $details = $form->get('details')->getData();

            if ('Autre' === $signalement->getMotif() && empty(trim((string) $details))) {
                $this->addFlash('danger', 'Veuillez préciser le motif du signalement dans le champ détails.');

                $form->get('details')->addError(new FormError('Veuillez préciser le motif.'));

                $form->addError(new FormError('Le formulaire contient des erreurs.'));
            } else {
                if ($details) {
                    $motifActuel = $signalement->getMotif();
                    $signalement->setMotif($motifActuel.' ('.$details.')');
                }

                $entityManager->persist($signalement);
                $entityManager->flush();

                $this->addFlash('success', 'Le signalement a bien été pris en compte.');

                return $this->redirectToRoute('app_messagerie_show', ['id' => $message->getConversation()->getId()]);
            }
        }

        return $this->render('signalement/new.html.twig', [
            'signalement' => $signalement,
            'form' => $form->createView(),
            'message_signale' => $message,
        ]);
    }
}
