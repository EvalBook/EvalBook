<?php

namespace App\Repository;

use App\Entity\UserConfiguration;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserConfiguration|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserConfiguration|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserConfiguration[]    findAll()
 * @method UserConfiguration[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserConfigurationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserConfiguration::class);
    }

    // /**
    //  * @return UserConfiguration[] Returns an array of UserConfiguration objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UserConfiguration
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
