<?php

namespace App\Controller;

use App\Entity\Possession;
use App\Entity\Profil;
use App\Entity\User;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{
    #[Route('/inscription', name: 'app_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        EntityManagerInterface $entityManager,
        RoleRepository $roleRepository,
        UserRepository $userRepository,
    ): Response {
        // traitement du formulaire soumis
        if ($request->isMethod('POST')) {
            // récupération des champs bruts
            $email = $request->request->get('email');
            $plainPassword = $request->request->get('password');
            $passwordConfirm = $request->request->get('password_confirm');

            // validations de base
            if ($plainPassword !== $passwordConfirm) {
                $this->addFlash('danger', 'Les mots de passe ne correspondent pas.');

                return $this->render('registration/register.html.twig');
            }

            if ($userRepository->findOneBy(['email' => $email])) {
                $this->addFlash('danger', 'Cet email est déjà utilisé.');

                return $this->render('registration/register.html.twig');
            }

            // création de l'utilisateur (Compte technique)
            $user = new User();
            $user->setEmail($email);
            $login = explode('@', $email)[0];
            $user->setLogin($login);
            $user->setDateCrea(new \DateTime());

            // hachage du mot de passe
            $hashedPassword = $userPasswordHasher->hashPassword($user, $plainPassword);
            $user->setMdpHache($hashedPassword);

            // création du profil
            $profil = new Profil();
            $profil->setUtilisateur($user);
            $profil->setPrenom($request->request->get('prenom'));
            $profil->setNom($request->request->get('nom'));
            $profil->setGenre($request->request->get('genre'));
            $profil->setTypeCompte($request->request->get('type_compte'));
            $profil->setDateNaissance(new \DateTime($request->request->get('date_naissance')));
            $profil->setDateCrea(new \DateTime());
            $profil->setDateModif(new \DateTime());

            // attribution du rôle par défaut via Possession
            $roleUser = $roleRepository->findOneBy(['code' => 'ROLE_USER']);
            if ($roleUser) {
                $possession = new Possession();
                $possession->setUtilisateur($user);
                $possession->setRole($roleUser);
                $entityManager->persist($possession);
            }

            // sauvegarde en BDD
            $entityManager->persist($user);
            $entityManager->persist($profil);
            $entityManager->flush();

            // succès et redirection
            $this->addFlash('success', 'Votre compte a bien été créé ! Vous pouvez maintenant vous connecter.');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig');
    }
}
