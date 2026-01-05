<?php

namespace App\Repository;

use App\Entity\Evenement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Evenement>
 */
class EvenementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Evenement::class);
    }

    /**
     * Récupère les événements à venir (ouverts et futurs)
     */
    public function findUpcoming(int $limit = 3): array
    {
        return $this->createQueryBuilder('e')
            ->where('e.statut = :statut')
            ->andWhere('e.dateDebut > :now')
            ->setParameter('statut', 'OUVERT')
            ->setParameter('now', new \DateTime())
            ->orderBy('e.dateDebut', 'ASC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
