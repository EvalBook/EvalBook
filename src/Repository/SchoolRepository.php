<?php

namespace App\Repository;

use App\Entity\School;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method School|null find($id, $lockMode = null, $lockVersion = null)
 * @method School|null findOneBy(array $criteria, array $orderBy = null)
 * @method School[]    findAll()
 * @method School[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SchoolRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, School::class);
    }


    /**
     * Return true if entity already exists.
     * @param School $school
     * @return int|mixed|string
     */
    public function schoolNameAlreadyTaken(School $school)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $queryBuilder
            ->select('count(s.id)')
            ->from(School::class, 's')
            ->where('s.id != :id')
            ->andWhere('s.name = :school_name')
            ->setParameter('id', $school->getId())
            ->setParameter('school_name', $school->getName())
        ;

        try {
            $count = $queryBuilder->getQuery()->getSingleScalarResult();
        }
        catch (NoResultException $e) {
            return false;
        }
        catch (NonUniqueResultException $e) {
            return true;
        }

        return intval($count) !== 0;
    }
}
