<?php

namespace App\Repository;

use App\Entity\TypeClasse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method TypeClasse|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeClasse|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeClasse[]    findAll()
 * @method TypeClasse[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeClasseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeClasse::class);
    }

    // /**
    //  * @return TypeClasse[] Returns an array of TypeClasse objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TypeClasse
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
