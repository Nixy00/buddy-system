<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\Signalement;
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
    public function index(SignalementRepository $signalementRepository): Response
    {
        $signalements = $signalementRepository->findBy([], ['dateSignalement' => 'DESC']);

        return $this->render('signalement/index.html.twig', [
            'signalements' => $signalements,
        ]);
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
