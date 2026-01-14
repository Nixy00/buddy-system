<?php

namespace App\Repository;

use App\Entity\Conversation;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
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
            ->where('c.initiateur = :user OR c.interlocuteur = :user')
            ->andWhere(':user NOT MEMBER OF c.supprimePar')
            ->andWhere(':user NOT MEMBER OF c.archivePar')
            ->setParameter('user', $user)
            ->orderBy('c.dateCrea', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve une conversation existante entre deux utilisateurs.
     * @throws NonUniqueResultException
     */
    public function findConversationBetween(User $user1, User $user2): ?Conversation
    {
        return $this->createQueryBuilder('c')
            ->where('(c.initiateur = :user1 AND c.interlocuteur = :user2)')
            ->orWhere('(c.initiateur = :user2 AND c.interlocuteur = :user1)')
            ->setParameter('user1', $user1)
            ->setParameter('user2', $user2)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
