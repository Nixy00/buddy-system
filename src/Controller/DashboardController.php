<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(): Response
    {
        // On récupère l'utilisateur connecté
        $user = $this->getUser();

        // Si pas connecté, on redirige vers la page de connexion
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        // On affiche le dashboard avec les informations de l'utilisateur
        return $this->render('dashboard/index.html.twig', [
            'user' => $user,
        ]);
    }
}
