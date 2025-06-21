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
     * Find all saints query
     * 
     * @return Query The query object
     */
    public function findAllQuery(): Query
    {
        return $this->createQueryBuilder('s')
            ->getQuery();
    }

    /**
     * Find saints created by a specific user
     * 
     * @param object $user The user who created the saints
     * @return Query The query object
     */
    public function findByCreatorQuery($user): Query
    {
        return $this->createQueryBuilder('s')
            ->where('s.creator = :user')
            ->setParameter('user', $user)
            ->getQuery();
    }
}
