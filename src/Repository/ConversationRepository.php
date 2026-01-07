<?php

namespace App\Repository;

use App\Entity\Conversation;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Conversation>
 */
class ConversationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Conversation::class);
    }

    /**
     * Récupère les conversations d'un utilisateur triées par date de création récente.
     * @return Conversation[]
     */
    public function findConversationsByUser(User $user): array
    {
        return $this->createQueryBuilder('c')
            ->where('c.initiateur = :user')
            ->orWhere('c.interlocuteur = :user')
            ->setParameter('user', $user)
            ->orderBy('c.dateCrea', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
