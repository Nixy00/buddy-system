<?php

namespace App\Repository;

use App\Entity\Relation;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Relation>
 */
class RelationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Relation::class);
    }

    public function countAcceptedForUser(User $user): int
    {
        return (int) $this->createQueryBuilder('r')
            ->select('count(r.id)')
            ->where('r.statut = :statut')
            ->andWhere('r.envoyeur = :userId OR r.receveur = :userId')
            ->setParameter('statut', 'acceptee')
            ->setParameter('userId', $user->getId())
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Compte les demandes reçues en attente
     */
    public function countDemandReceivedByUser(User $user): int
    {
        return (int) $this->createQueryBuilder('r')
            ->select('count(r.id)')
            ->where('r.statut = :statut')
            ->andWhere('r.receveur = :user') // On ne compte que celles qu'on REÇOIT
            ->setParameter('statut', 'en_attente')
            ->setParameter('user', $user)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
