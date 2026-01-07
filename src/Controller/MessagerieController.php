<?php

namespace App\Controller;

use App\Entity\Conversation;
use App\Entity\Message;
use App\Form\MessageType;
use App\Repository\ConversationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/messagerie')]
class MessagerieController extends AbstractController
{
    #[Route('/', name: 'app_messagerie')]
    public function index(ConversationRepository $conversationRepository): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        // récupération des conversations où l'utilisateur est impliqué
        $conversations = $conversationRepository->findConversationsByUser($user);

        return $this->render('messagerie/index.html.twig', [
            'conversations' => $conversations,
        ]);
    }

    #[Route('/{id}', name: 'app_messagerie_show', requirements: ['id' => '\d+'])]
    public function show(Conversation $conversation, Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        // on vérifie que l'utilisateur fait partie de la conversation (sécurité)
        if ($conversation->getInitiateur() !== $user && $conversation->getInterlocuteur() !== $user) {
            throw $this->createAccessDeniedException('Vous n\'avez pas accès à cette conversation.');
        }

        // création du formulaire
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);

        // traitement de la requête
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // automatisme du lien User-Conversation
            $message->setUtilisateur($user);
            $message->setConversation($conversation);
            $message->setDateEnvoi(new \DateTime());

            $entityManager->persist($message);
            $entityManager->flush();

            return $this->redirectToRoute('app_messagerie_show', ['id' => $conversation->getId()]);
        }

        // on détermine qui est la personne en face
        $contact = ($conversation->getInitiateur() === $user) ? $conversation->getInterlocuteur() : $conversation->getInitiateur();

        return $this->render('messagerie/show.html.twig', [
            'conversation' => $conversation,
            'contact' => $contact,
            'form' => $form->createView(), // passer le formulaire à la vue
        ]);
    }
}
