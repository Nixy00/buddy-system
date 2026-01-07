<?php

namespace App\Service;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ChatbotService
{
    private UrlGeneratorInterface $router;

    public function __construct(UrlGeneratorInterface $router)
    {
        $this->router = $router;
    }

    public function resolve(string $message): array
    {
        // nettoyage
        $message = strtolower(trim($message));

        // cas 1 : Salutations
        if (str_contains($message, 'bonjour') || str_contains($message, 'salut') || str_contains($message, 'hello')) {
            return [
                'response' => "Bonjour ! 👋 Je suis le BuddyBot. Je peux t'aider à trouver un parrain ou un événement. Que veux-tu faire ?",
                'suggestions' => [
                    'Chercher un Buddy',
                    'Voir les événements',
                    'Problème de connexion',
                ],
            ];
        }

        // cas 2 : Événements
        if (str_contains($message, 'event') || str_contains($message, 'événement') || str_contains($message, 'sortie')) {
            // on génère le lien vers la liste des events
            $url = $this->router->generate('app_events');

            return [
                'response' => "Les événements sont le meilleur moyen de s'intégrer ! 🎉 Tu peux voir la liste complète des sorties prévues.",
                'suggestions' => [
                    'Voir le calendrier',
                    'Créer un événement',
                    'Retour au début',
                ],
                'action_url' => $url,
            ];
        }

        // cas 3 : Recherche de Parrain
        if (str_contains($message, 'parrain') || str_contains($message, 'buddy') || str_contains($message, 'chercher')) {
            return [
                'response' => "C'est le cœur du site ! 🤝 Tu peux consulter la liste des étudiants disponibles pour devenir ton Buddy.",
                'suggestions' => [
                    'Trouver un binôme',
                    'Comment ça marche ?',
                    'Retour au début',
                ],
            ];
        }

        // cas 4 : Aide / Au revoir
        if (str_contains($message, 'merci') || str_contains($message, 'au revoir')) {
            return [
                'response' => "Avec plaisir ! N'hésite pas si tu as d'autres questions. À bientôt ! 🎓",
                'suggestions' => [],
            ];
        }

        // cas par défaut
        return [
            'response' => "Désolé, je ne suis qu'un robot 🤖 et je n'ai pas compris. Essaie d'utiliser des mots-clés simples.",
            'suggestions' => [
                'Voir les événements',
                'Chercher un Buddy',
                'Aide',
            ],
        ];
    }
}
