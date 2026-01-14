<?php

namespace App\Controller;

use App\Entity\Signalement;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/connexion', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // si utilisateur déjà connecté, alors on redirige vers le dashboard
        if ($this->getUser()) {
            return $this->redirectToRoute('app_dashboard');
        }

        // récuperer l'erreur de connexion s'il y en a une
        $error = $authenticationUtils->getLastAuthenticationError();

        // récuperer le dernier email saisi par l'internaute
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route(path: '/mot-de-passe-oublie', name: 'app_forgot_password', methods: ['GET', 'POST'])]
    public function forgotPassword(Request $request, UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');
            $prenom = $request->request->get('prenom');
            $nom = $request->request->get('nom');

            $user = $userRepository->findOneBy(['email' => $email]);

            if (!$user) {
                $this->addFlash('danger', 'Aucun compte trouvé avec cet email.');

                return $this->redirectToRoute('app_forgot_password');
            } else {
                $signalement = new Signalement();
                $signalement->setUtilisateur($user);
                $signalement->setDateSignalement(new \DateTime());
                $signalement->setStatut('EN_ATTENTE');
                $signalement->setMotif('Autre (Mot de passe oublié)');

                $signalement->setReportedUser($user);

                $entityManager->persist($signalement);
                $entityManager->flush();

                $this->addFlash('success', 'Votre demande a été envoyée aux administrateurs. Ils vous contacteront sur votre e-mail étudiant prochainement.');

                return $this->redirectToRoute('app_login');
            }
        }

        return $this->render('security/forgot_password.html.twig');
    }

    #[Route(path: '/deconnexion', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('Cette méthode peut être vide - elle sera interceptée par la clé de déconnexion.');
    }
}
