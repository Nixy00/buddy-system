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
}
