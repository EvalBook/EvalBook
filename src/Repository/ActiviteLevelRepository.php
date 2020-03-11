<?php

namespace App\Repository;

use App\Entity\ActiviteLevel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ActiviteLevel|null find($id, $lockMode = null, $lockVersion = null)
 * @method ActiviteLevel|null findOneBy(array $criteria, array $orderBy = null)
 * @method ActiviteLevel[]    findAll()
 * @method ActiviteLevel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActiviteLevelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ActiviteLevel::class);
    }

    // /**
    //  * @return ActiviteLevel[] Returns an array of ActiviteLevel objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ActiviteLevel
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
