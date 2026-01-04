<?php

namespace App\Controller;

use App\Repository\ProfilRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class ProfilAvatarController extends AbstractController
{
    #[Route('/profil/{id}/avatar', name: 'app_profil_avatar', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function avatar(int $id, ProfilRepository $profilRepository): Response
    {
        $profil = $profilRepository->find($id);

        if (!$profil) {
            throw new NotFoundHttpException('Profil introuvable.');
        }

        $avatar = $profil->getAvatar();

        if (empty($avatar)) {
            throw new NotFoundHttpException('Avatar non disponible.');
        }

        if (\is_resource($avatar)) {
            $avatar = stream_get_contents($avatar);
        }

        $decoded = base64_decode((string) $avatar, true);
        if (false !== $decoded) {
            $avatar = $decoded;
        }

        return new Response($avatar, 200, [
            'Content-Type' => 'image/jpg',
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }
}
