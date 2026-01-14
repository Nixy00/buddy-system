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
        $langue = (string) $request->query->get('langues', '');
        $disponibilite = (string) $request->query->get('disponibilites', '');

        $allLangues = ['Français', 'Anglais', 'Espagnol', 'Allemand', 'Italien', 'Arabe'];
        $allDisponibilites = [
            'Flexible (selon planning)',
            'Soirées principalement',
            'Week-end : après-midi',
            'Semaine : 18h-22h',
            'En semaine : midi',
        ];

        $langues = !empty($langue) ? [$langue] : [];
        $disponibilites = !empty($disponibilite) ? [$disponibilite] : [];

        $profils = $profilRepository->searchWithFilters($search, $langues, $disponibilites);

        return $this->render('buddy/index.html.twig', [
            'profils' => $profils,
            'search_filtre' => $search,
            'langues_filtre' => $langues,
            'disponibilites_filtre' => $disponibilites,
            'all_langues' => $allLangues,
            'all_disponibilites' => $allDisponibilites,
        ]);
    }
}
