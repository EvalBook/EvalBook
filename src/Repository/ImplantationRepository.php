<?php

namespace App\Repository;

use App\Entity\Implantation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Implantation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Implantation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Implantation[]    findAll()
 * @method Implantation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImplantationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Implantation::class);
    }

    // /**
    //  * @return Implantation[] Returns an array of Implantation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Implantation
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
