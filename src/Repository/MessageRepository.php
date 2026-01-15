<?php

namespace App\Repository;

use App\Entity\Message;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Message>
 */
class MessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Message::class);
    }

    /**
     * Compte les messages non lus pour un utilisateur.
     */
    public function countUnreadMessagesByUser(User $user): int
    {
        return (int) $this->createQueryBuilder('m')
            ->select('count(m.id)')
            ->join('m.conversation', 'c')
            ->where('c.initiateur = :user OR c.interlocuteur = :user') // On est dans la conversation
            ->andWhere('m.utilisateur != :user') // Ce n'est pas moi qui ai écrit le message
            ->andWhere('m.dateLecture IS NULL') // Il n'est pas lu
            ->andWhere('m.estSupprime = false') // Il n'est pas supprimé
            ->setParameter('user', $user)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
