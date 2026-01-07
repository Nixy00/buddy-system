<?php

namespace App\Controller;

use App\Entity\Conversation;
use App\Entity\Message;
use App\Entity\User;
use App\Form\MessageType;
use App\Repository\ConversationRepository;
use App\Repository\RelationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/messagerie')]
class MessagerieController extends AbstractController
{
    #[Route('/', name: 'app_messagerie')]
    public function index(ConversationRepository $conversationRepository, RelationRepository $relationRepository): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        // On récupère aussi les demandes pour afficher une notification
        $buddyRequests = $relationRepository->findBy([
            'receveur' => $user->getId(),
            'statut' => 'en_attente'
        ]);

        $conversations = $conversationRepository->findConversationsByUser($user);

        return $this->render('messagerie/index.html.twig', [
            'conversations' => $conversations,
            'buddyRequestCount' => count($buddyRequests)
        ]);
    }

    #[Route('/requests', name: 'app_messagerie_requests')]
    public function requests(RelationRepository $relationRepository): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $buddyRequests = $relationRepository->findBy(
            ['receveur' => $user->getId(), 'statut' => 'en_attente'],
            ['dateDemande' => 'DESC']
        );

        return $this->render('messagerie/requests.html.twig', [
            'buddyRequests' => $buddyRequests,
        ]);
    }

    #[Route('/{id}', name: 'app_messagerie_show', requirements: ['id' => '\d+'])]
    public function show(Conversation $conversation, Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        if ($conversation->getInitiateur() !== $user && $conversation->getInterlocuteur() !== $user) {
            throw $this->createAccessDeniedException('Vous n\'avez pas accès à cette conversation.');
        }

        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message->setUtilisateur($user);
            $message->setConversation($conversation);
            $message->setDateEnvoi(new \DateTime());

            $entityManager->persist($message);
            $entityManager->flush();

            return $this->redirectToRoute('app_messagerie_show', ['id' => $conversation->getId()]);
        }

        $contact = ($conversation->getInitiateur() === $user) ? $conversation->getInterlocuteur() : $conversation->getInitiateur();

        return $this->render('messagerie/show.html.twig', [
            'conversation' => $conversation,
            'contact' => $contact,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @throws NonUniqueResultException
     */
    #[Route('/start/{id}', name: 'app_messagerie_start')]
    public function start(User $target, ConversationRepository $repository, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        if ($user === $target) {
            $this->addFlash('warning', 'Vous ne pouvez pas lancer une conversation avec vous-même.');
            return $this->redirectToRoute('app_home');
        }

        $conversation = $repository->findConversationBetween($user, $target);

        if (!$conversation) {
            $conversation = new Conversation();
            $conversation->setInitiateur($user);
            $conversation->setInterlocuteur($target);
            $conversation->setDateCrea(new \DateTime());
            $conversation->setStatut('active');

            $entityManager->persist($conversation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_messagerie_show', ['id' => $conversation->getId()]);
    }
}
