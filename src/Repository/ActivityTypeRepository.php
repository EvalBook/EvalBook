<?php

namespace App\Repository;

use App\Entity\ActivityType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ActivityType|null find($id, $lockMode = null, $lockVersion = null)
 * @method ActivityType|null findOneBy(array $criteria, array $orderBy = null)
 * @method ActivityType[]    findAll()
 * @method ActivityType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActivityTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ActivityType::class);
    }

    /**
    * @return ActivityType[] Returns an array of ActivityType objects ordered byè school report display order.
    */
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.weight', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }


    /*
    public function findOneBySomeField($value): ?ActivityType
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
