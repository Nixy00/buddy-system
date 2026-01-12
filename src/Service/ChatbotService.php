<?php

namespace App\Service;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ChatbotService
{
    private UrlGeneratorInterface $router;
    private Security $security;

    // On injecte le Router (pour les liens) et la Sécurité (pour savoir qui est connecté)
    public function __construct(UrlGeneratorInterface $router, Security $security)
    {
        $this->router = $router;
        $this->security = $security;
    }

    public function resolve(string $message): array
    {
        $message = strtolower(trim($message));

        $response = "Je n'ai pas compris. Tu peux utiliser les boutons pour me guider ! 👇";
        $suggestions = ['Accueil', 'Chercher un Buddy', 'Événements'];
        $actionUrl = null;


        // 1. SALUTATIONS
        if (preg_match('/(bonjour|salut|hello|start|commencer|aide)/', $message)) {
            $response = "Bonjour ! 👋 Je suis l'assistant Buddy System. Que veux-tu faire ?";
            $suggestions = ['Trouver un binôme 🤝', 'Sortir aux événements 🎉', 'Gérer mon compte 👤'];
        }

        // 2. BUDDY (Recherche)
        elseif (str_contains($message, 'lancer la recherche')) {
            $response = "C'est parti ! Je t'emmène sur la page de recherche...";
            $actionUrl = $this->router->generate('app_recherche_buddy', [], UrlGeneratorInterface::ABSOLUTE_URL);
            $suggestions = [];
        }
        elseif (str_contains($message, 'comment ça marche')) {
            $response = "Tu remplis tes critères, on te propose des profils compatibles, et tu envoies une demande !";
            $suggestions = ['Lancer la recherche 🚀', 'Retour'];
        }
        elseif (str_contains($message, 'binôme') || str_contains($message, 'buddy') || str_contains($message, 'cherche')) {
            $response = "Le cœur du système ! Tu peux chercher un étudiant local ou international.";
            $suggestions = ['Lancer la recherche 🚀', 'Comment ça marche ?', 'Retour'];
        }

        // 3. ÉVÉNEMENTS

        elseif (str_contains($message, 'voir le calendrier')) {
            $response = "Chargement des événements à venir...";
            $actionUrl = $this->router->generate('app_events', [], UrlGeneratorInterface::ABSOLUTE_URL);
            $suggestions = [];
        }
        elseif (str_contains($message, 'organiser')) {
            if ($this->security->getUser()) {
                $response = "Super initiative ! Direction le formulaire de création.";
                $actionUrl = $this->router->generate('app_event_create', [], UrlGeneratorInterface::ABSOLUTE_URL);
                $suggestions = [];
            } else {
                $response = "Tu dois être connecté pour créer un événement.";
                $suggestions = ['Connexion 🔐', 'Retour'];
            }
        }
        elseif (str_contains($message, 'événement') || str_contains($message, 'sortir') || str_contains($message, 'fête')) {
            $response = "Il y a toujours quelque chose à faire à l'URCA !";
            $suggestions = ['Voir le calendrier 📅', 'Organiser un event ➕', 'Retour'];
        }

        // 4. COMPTE & AUTHENTIFICATIONS
        elseif (str_contains($message, 'mon profil')) {
            $user = $this->security->getUser();
            if ($user && $user->getProfil()) {
                $actionUrl = $this->router->generate('app_profil_show', ['id' => $user->getProfil()->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
                $response = "Accès à ton profil...";
            } else {
                $actionUrl = $this->router->generate('app_dashboard', [], UrlGeneratorInterface::ABSOLUTE_URL);
                $response = "Je te redirige vers ton tableau de bord.";
            }
            $suggestions = [];
        }
        elseif (str_contains($message, 'connexion')) {
            $actionUrl = $this->router->generate('app_login', [], UrlGeneratorInterface::ABSOLUTE_URL);
            $response = "Redirection vers la connexion...";
            $suggestions = [];
        }
        elseif (str_contains($message, 'inscription')) {
            $actionUrl = $this->router->generate('app_register', [], UrlGeneratorInterface::ABSOLUTE_URL);
            $response = "Rejoins-nous !";
            $suggestions = [];
        }
        elseif (str_contains($message, 'déconnexion')) {
            $actionUrl = $this->router->generate('app_logout', [], UrlGeneratorInterface::ABSOLUTE_URL);
            $response = "À bientôt !";
            $suggestions = [];
        }
        elseif (str_contains($message, 'compte') || str_contains($message, 'profil')) {
            if ($this->security->getUser()) {
                // On récupère le prénom via l'entité User -> Profil
                $profil = $this->security->getUser()->getProfil();
                $prenom = $profil ? $profil->getPrenom() : 'l\'ami';

                $response = "Tout va bien, $prenom ? Que veux-tu vérifier ?";
                $suggestions = ['Mon Profil 👀', 'Déconnexion ❌', 'Retour'];
            } else {
                $response = "Tu n'es pas connecté.";
                $suggestions = ['Connexion 🔐', 'Inscription ✨', 'Retour'];
            }
        }

        // 5. RETOUR / AUTRE
        elseif (str_contains($message, 'retour') || str_contains($message, 'accueil')) {
            $response = "Retour au menu principal.";
            $suggestions = ['Trouver un binôme 🤝', 'Sortir aux événements 🎉', 'Gérer mon compte 👤'];
        }

        return [
            'response' => $response,
            'suggestions' => $suggestions,
            'action_url' => $actionUrl
        ];
    }
}
