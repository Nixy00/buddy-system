<?php

namespace App\Controller;

use App\Repository\ProfilRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class BuddyController extends AbstractController
{
    #[Route('/recherche', name: 'app_recherche_buddy')]
    public function index(Request $request, ProfilRepository $profilRepository): Response
    {
        $search = (string) $request->query->get('search', '');

        $profils = $profilRepository->search($search);

        return $this->render('buddy/index.html.twig', [
            'profils' => $profils,
        ]);
    }
}
