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
     * Récupère toutes les relations validées pour un utilisateur.
     * @return Relation[]
     */
    public function findAcceptedRelationsByUser(User $user): array
    {
        return $this->createQueryBuilder('r')
            ->where('r.statut = :statut')
            ->andWhere('r.envoyeur = :user OR r.receveur = :user')
            ->setParameter('statut', 'acceptee')
            ->setParameter('user', $user)
            ->orderBy('r.dateReponse', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
