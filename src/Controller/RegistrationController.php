<?php

namespace App\Controller;

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

        }

        return $this->render('registration/register.html.twig');
    }
}
