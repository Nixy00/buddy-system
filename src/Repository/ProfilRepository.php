<?php

namespace App\Repository;

use App\Entity\Profil;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Profil>
 */
class ProfilRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Profil::class);
    }

    //    /**
    //     * @return Profil[] Returns an array of Profil objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Profil
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function search(string $text): array
    {
        if (empty($text)) {
            return $this->findAll();
        }

        $qb = $this->createQueryBuilder('p');

        return $qb
            ->leftJoin('p.utilisateur', 'u')
            ->where('p.nom LIKE :search OR p.prenom LIKE :search OR p.biographie LIKE :search')
            ->setParameter('search', '%' . $text . '%')
            ->orderBy('p.nom', 'ASC')
            ->addOrderBy('p.prenom', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function searchWithFilters(string $text = '', array $langues = [], array $disponibilites = []): array
    {
        $profils = $this->findAll();

        if (!empty($text)) {
            $profils = array_filter($profils, function ($profil) use ($text) {
                return false !== stripos($profil->getNom(), $text)
                    || false !== stripos($profil->getPrenom(), $text)
                    || false !== stripos($profil->getBiographie() ?? '', $text);
            });
        }

        if (!empty($langues)) {
            $profils = array_filter($profils, function ($profil) use ($langues) {
                $profilLangues = array_map('trim', explode(',', $profil->getLangues() ?? ''));
                foreach ($langues as $langue) {
                    if (in_array(trim($langue), $profilLangues)) {
                        return true;
                    }
                }
                return false;
            });
        }

        if (!empty($disponibilites)) {
            $profils = array_filter($profils, function ($profil) use ($disponibilites) {
                $profilDisponibilites = array_map('trim', explode(',', $profil->getDisponibilites() ?? ''));
                foreach ($disponibilites as $disponibilite) {
                    if (in_array(trim($disponibilite), $profilDisponibilites)) {
                        return true;
                    }
                }
                return false;
            });
        }

        usort($profils, function ($a, $b) {
            $cmp = strcmp($a->getNom(), $b->getNom());
            if ($cmp !== 0) {
                return $cmp;
            }
            return strcmp($a->getPrenom(), $b->getPrenom());
        });

        return $profils;
    }
}
