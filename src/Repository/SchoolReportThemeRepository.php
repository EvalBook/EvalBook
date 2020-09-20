<?php

namespace App\Repository;

use App\Entity\SchoolReportTheme;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SchoolReportTheme|null find($id, $lockMode = null, $lockVersion = null)
 * @method SchoolReportTheme|null findOneBy(array $criteria, array $orderBy = null)
 * @method SchoolReportTheme[]    findAll()
 * @method SchoolReportTheme[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SchoolReportThemeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SchoolReportTheme::class);
    }

    // /**
    //  * @return SchoolReportTheme[] Returns an array of SchoolReportTheme objects
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
    public function findOneBySomeField($value): ?SchoolReportTheme
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
