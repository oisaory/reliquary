<?php

namespace App\Repository;

use App\Entity\Saint;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Saint>
 */
class SaintRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Saint::class);
    }

    //    /**
    //     * @return Saint[] Returns an array of Saint objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Saint
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    /**
     * Find all saints query with optional canonical status filter
     * 
     * @param string|null $canonicalStatus The canonical status to filter by
     * @param bool $includeIncomplete Whether to include incomplete saints
     * @return Query The query object
     */
    public function findAllQuery(?string $canonicalStatus = null, bool $includeIncomplete = false): Query
    {
        $queryBuilder = $this->createQueryBuilder('s');

        if (!$includeIncomplete) {
            $queryBuilder
                ->andWhere('s.is_incomplete = :incomplete')
                ->setParameter('incomplete', false);
        }

        if ($canonicalStatus) {
            $queryBuilder
                ->andWhere('s.canonical_status = :canonicalStatus')
                ->setParameter('canonicalStatus', $canonicalStatus);
        }

        return $queryBuilder->getQuery();
    }

    /**
     * Find saints created by a specific user with optional canonical status filter
     * 
     * @param object $user The user who created the saints
     * @param string|null $canonicalStatus The canonical status to filter by
     * @param bool $includeIncomplete Whether to include incomplete saints
     * @return Query The query object
     */
    public function findByCreatorQuery($user, ?string $canonicalStatus = null, bool $includeIncomplete = false): Query
    {
        $queryBuilder = $this->createQueryBuilder('s')
            ->where('s.creator = :user')
            ->setParameter('user', $user);

        if (!$includeIncomplete) {
            $queryBuilder
                ->andWhere('s.is_incomplete = :incomplete')
                ->setParameter('incomplete', false);
        }

        if ($canonicalStatus) {
            $queryBuilder
                ->andWhere('s.canonical_status = :canonicalStatus')
                ->setParameter('canonicalStatus', $canonicalStatus);
        }

        return $queryBuilder->getQuery();
    }

    /**
     * Find all incomplete saints query
     * 
     * @return Query The query object
     */
    public function findIncompleteQuery(): Query
    {
        $queryBuilder = $this->createQueryBuilder('s')
            ->andWhere('s.is_incomplete = :incomplete')
            ->setParameter('incomplete', true)
            ->orderBy('s.id', 'DESC');

        return $queryBuilder->getQuery();
    }
}
