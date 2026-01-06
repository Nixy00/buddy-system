<?php

namespace App\Controller;

use App\Repository\ProfilRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;

final class ProfilController extends AbstractController
{
    #[Route('/profil/{id}', name: 'app_profil_show', requirements: ['id' => '\d+'])]
    public function show(int $id, ProfilRepository $profilRepository): Response
    {
        $profil = $profilRepository->find($id);

        if (!$profil) {
            throw new NotFoundHttpException('Profil non trouvé');
        }

        return $this->render('profil/show.html.twig', [
            'profil' => $profil,
        ]);
    }

    #[Route('/profil/edit', name: 'app_profil_edit')]
    public function edit(): Response
    {
        // Mode TEST comme Dashboard (en attendant système de connexion)
        $user = $this->getUser();

        if (!$user) {
            // Simulation d'un utilisateur pour tester le frontend
            $user = new \stdClass();
            $user->id = 1;
            // TODO BACKEND: Ajouter vraie connexion utilisateur
        }

        // TODO BACKEND: Récupérer le profil et l'utilisateur depuis la BDD
        // $profil = $profilRepository->findOneBy(['utilisateur' => $user]);
        // $email = $user->getEmail();

        return $this->render('profil/edit.html.twig', [
            'user' => $user,
            // TODO BACKEND: Passer les vraies données
            // 'profil' => $profil,
            // 'email' => $email
        ]);
    }
}
