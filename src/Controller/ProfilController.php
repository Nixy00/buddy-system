<?php

namespace App\Controller;

use App\Entity\Profil;
use App\Form\ProfilType;
use App\Repository\ProfilRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function edit(Request $request, EntityManagerInterface $entityManager, ProfilRepository $profilRepository): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $profil = $profilRepository->findOneBy(['utilisateur' => $user]);

        if (!$profil) {
            $profil = new Profil();
            $profil->setUtilisateur($user);
            $profil->setDateCrea(new \DateTime());
        }

        $form = $this->createForm(ProfilType::class, $profil);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $profil->setDateModif(new \DateTime());

            $avatarFile = $form->get('avatar')->getData();
            if ($avatarFile) {
                $avatarContent = file_get_contents($avatarFile->getPathname());
                $profil->setAvatar($avatarContent);
            }

            $entityManager->persist($profil);
            $entityManager->flush();

            $this->addFlash('success', 'Votre profil a été mis à jour avec succès.');

            return $this->redirectToRoute('app_profil_show', ['id' => $profil->getId()]);
        }

        return $this->render('profil/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }
}
