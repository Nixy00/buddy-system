<?php

namespace App\Controller;

use App\Entity\Relation;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
#[Route('/relation', name: 'app_relation_')]
class RelationController extends AbstractController
{
    #[Route('/send/{id}', name: 'send_request')]
    public function sendRequest(User $receiver, EntityManagerInterface $em): \Symfony\Component\HttpFoundation\RedirectResponse
    {
        /** @var User $sender */
        $sender = $this->getUser();

        if ($sender === $receiver) {
            $this->addFlash('error', 'Vous ne pouvez pas vous ajouter vous-même.');

            return $this->redirectToRoute('app_profil_show', ['id' => $receiver->getProfil()->getId()]);
        }

        $relation = new Relation();
        $relation->setEnvoyeur($sender);
        $relation->setReceveur($receiver);
        $relation->setStatut('en_attente');
        $relation->setDateDemande(new \DateTime());

        $em->persist($relation);
        $em->flush();

        $this->addFlash('success', 'Votre demande de buddy a bien été envoyée.');

        return $this->redirectToRoute('app_profil_show', ['id' => $receiver->getProfil()->getId()]);
    }

    #[Route('/accept/{id}', name: 'accept_request')]
    public function acceptRequest(Relation $relation, EntityManagerInterface $em): \Symfony\Component\HttpFoundation\RedirectResponse
    {
        if ($this->getUser() !== $relation->getReceveur()) {
            $this->addFlash('error', 'Action non autorisée.');

            return $this->redirectToRoute('app_home');
        }

        $relation->setStatut('acceptee');
        $relation->setDateReponse(new \DateTime());

        $em->flush();

        $this->addFlash('success', 'Vous êtes maintenant buddy avec '.$relation->getEnvoyeur()->getProfil()->getPrenom().' !');

        return $this->redirectToRoute('app_profil_show', ['id' => $relation->getEnvoyeur()->getProfil()->getId()]);
    }

    #[Route('/refuse/{id}', name: 'refuse_request')]
    public function refuseRequest(Relation $relation, EntityManagerInterface $em): \Symfony\Component\HttpFoundation\RedirectResponse
    {
        if ($this->getUser() !== $relation->getReceveur()) {
            $this->addFlash('error', 'Action non autorisée.');

            return $this->redirectToRoute('app_home');
        }

        $senderProfilId = $relation->getEnvoyeur()->getProfil()->getId();
        $em->remove($relation);
        $em->flush();

        $this->addFlash('info', 'Vous avez refusé la demande.');

        return $this->redirectToRoute('app_profil_show', ['id' => $senderProfilId]);
    }

    #[Route('/cancel/{id}', name: 'cancel_request')]
    public function cancelRequest(Relation $relation, EntityManagerInterface $em): \Symfony\Component\HttpFoundation\RedirectResponse
    {
        if ($this->getUser() !== $relation->getEnvoyeur()) {
            $this->addFlash('error', 'Action non autorisée.');

            return $this->redirectToRoute('app_home');
        }

        $receiverProfilId = $relation->getReceveur()->getProfil()->getId();
        $em->remove($relation);
        $em->flush();

        $this->addFlash('info', 'Votre demande a bien été annulée.');

        return $this->redirectToRoute('app_profil_show', ['id' => $receiverProfilId]);
    }
}
