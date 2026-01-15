<?php

namespace App\Controller;

use App\Repository\ParticipationRepository;
use App\Repository\RelationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(ParticipationRepository $participationRepository, RelationRepository $relationRepository): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $mesParticipations = $participationRepository->findUpcomingEventsByUser($user);
        $buddyCount = $relationRepository->countAcceptedForUser($user);

        return $this->render('dashboard/index.html.twig', [
            'user' => $user,
            'mesParticipations' => $mesParticipations,
            'buddyCount' => $buddyCount,
        ]);
    }

    #[Route('/dashboard/mes-buddies', name: 'app_dashboard_mes_buddies')]
    public function mesBuddies(RelationRepository $relationRepository): Response
    {
        $user = $this->getUser();
        if (!$user) return $this->redirectToRoute('app_login');

        $mesRelations = $relationRepository->findAcceptedRelationsByUser($user);

        return $this->render('dashboard/mes_buddies.html.twig', [
            'mesRelations' => $mesRelations,
        ]);
    }

    #[Route('/dashboard/mes-evenements', name: 'app_dashboard_mes_evenements')]
    public function mesEvenements(ParticipationRepository $participationRepository): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $mesParticipations = $participationRepository->findBy(['utilisateur' => $user]);

        return $this->render('dashboard/mes_evenements.html.twig', [
            'mesParticipations' => $mesParticipations,
        ]);
    }

    #[Route('/dashboard/confirm-delete', name: 'app_account_confirm_delete', methods: ['GET'])]
    public function confirmDelete(): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('dashboard/confirm_delete.html.twig');
    }

    #[Route('/dashboard/delete-account', name: 'app_account_delete', methods: ['POST'])]
    public function deleteAccount(Request $request, EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage): Response
    {
        $user = $this->getUser();

        if (!$user || !$this->isCsrfTokenValid('delete-account', $request->request->get('_token'))) {
            $this->addFlash('danger', 'La suppression du compte a échoué.');

            return $this->redirectToRoute('app_dashboard');
        }

        $entityManager->remove($user);
        $entityManager->flush();

        $tokenStorage->setToken(null);
        $request->getSession()->invalidate();

        $this->addFlash('success', 'Votre compte a été supprimé avec succès.');

        return $this->redirectToRoute('app_home');
    }
}
