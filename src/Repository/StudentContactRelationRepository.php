<?php

namespace App\Repository;

use App\Entity\StudentContactRelation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method StudentContactRelation|null find($id, $lockMode = null, $lockVersion = null)
 * @method StudentContactRelation|null findOneBy(array $criteria, array $orderBy = null)
 * @method StudentContactRelation[]    findAll()
 * @method StudentContactRelation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StudentContactRelationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StudentContactRelation::class);
    }

    // /**
    //  * @return StudentContactRelation[] Returns an array of StudentContactRelation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?StudentContactRelation
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
