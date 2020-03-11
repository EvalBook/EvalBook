<?php

namespace App\Repository;

use App\Entity\EleveComment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method EleveComment|null find($id, $lockMode = null, $lockVersion = null)
 * @method EleveComment|null findOneBy(array $criteria, array $orderBy = null)
 * @method EleveComment[]    findAll()
 * @method EleveComment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EleveCommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EleveComment::class);
    }

    // /**
    //  * @return EleveComment[] Returns an array of EleveComment objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EleveComment
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
