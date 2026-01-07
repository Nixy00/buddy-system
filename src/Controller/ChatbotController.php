<?php

namespace App\Controller;

use App\Service\ChatbotService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class ChatbotController extends AbstractController
{
    #[Route('/api/chatbot', name: 'api_chatbot', methods: ['POST'])]
    public function chat(Request $request, ChatbotService $chatbotService): JsonResponse
    {
        // on récupère le JSON envoyé par le js
        $data = json_decode($request->getContent(), true);
        $message = $data['message'] ?? '';

        if (empty($message)) {
            return $this->json([
                'response' => 'Bonjour ! Je suis là pour t\'aider.',
                'suggestions' => ['Bonjour', 'Voir les événements'],
            ]);
        }

        $result = $chatbotService->resolve($message);

        // envoi de la réponse en JSON
        return $this->json($result);
    }
}
