<?php

namespace App\Controller;

use App\Entity\Profil;
use App\Form\ProfilType;
use App\Repository\ProfilRepository;
use App\Repository\RelationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;

final class ProfilController extends AbstractController
{
    #[Route('/profil/{id}', name: 'app_profil_show', requirements: ['id' => '\d+'])]
    public function show(int $id, ProfilRepository $profilRepository, RelationRepository $relationRepository): Response
    {
        $profil = $profilRepository->find($id);

        if (!$profil) {
            throw new NotFoundHttpException('Profil non trouvé');
        }

        $relation = null;
        $user = $this->getUser();
        if ($user) {
            $relation = $relationRepository->findOneBy([
                'envoyeur' => $user,
                'receveur' => $profil->getUtilisateur(),
            ]);
            if (!$relation) {
                $relation = $relationRepository->findOneBy([
                    'envoyeur' => $profil->getUtilisateur(),
                    'receveur' => $user,
                ]);
            }
        }

        $buddyCount = $relationRepository->countAcceptedForUser($profil->getUtilisateur());

        return $this->render('profil/show.html.twig', [
            'profil' => $profil,
            'relation' => $relation,
            'buddyCount' => $buddyCount,
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

        $originalProfil = clone $profil;

        $form = $this->createForm(ProfilType::class, $profil);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hasChanges = false;

            if ($originalProfil->getNom() !== $profil->getNom()) {
                $hasChanges = true;
            }
            if ($originalProfil->getPrenom() !== $profil->getPrenom()) {
                $hasChanges = true;
            }
            if ($originalProfil->getGenre() !== $profil->getGenre()) {
                $hasChanges = true;
            }
            if ($originalProfil->getDateNaissance() != $profil->getDateNaissance()) {
                $hasChanges = true;
            }
            if ($originalProfil->getTelephone() !== $profil->getTelephone()) {
                $hasChanges = true;
            }
            if ($originalProfil->getVille() !== $profil->getVille()) {
                $hasChanges = true;
            }
            if ($originalProfil->getPays() !== $profil->getPays()) {
                $hasChanges = true;
            }
            if ($originalProfil->getBiographie() !== $profil->getBiographie()) {
                $hasChanges = true;
            }
            if ($originalProfil->getLangues() !== $profil->getLangues()) {
                $hasChanges = true;
            }
            if ($originalProfil->getDisponibilites() !== $profil->getDisponibilites()) {
                $hasChanges = true;
            }

            $avatarFile = $form->get('avatar')->getData();
            if ($avatarFile) {
                $hasChanges = true;
                $avatarContent = file_get_contents($avatarFile->getPathname());
                $profil->setAvatar($avatarContent);
            }

            if (!$hasChanges) {
                $this->addFlash('warning', 'Le profil n\'a pas été modifié car aucune information n\'a changé.');

                return $this->redirectToRoute('app_dashboard');
            }

            $profil->setDateModif(new \DateTime());
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
