<?php

namespace App\Repository;

use App\Entity\ActivityType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Contracts\Translation\TranslatorInterface;

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
            ->orderBy('a.weight', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }


    /**
     * Populate database with restricted activity types.
     * @param TranslatorInterface $translator
     */
    public function populate(TranslatorInterface $translator)
    {
        $knowledge = new ActivityType();
        $knowledge
            ->setName($translator->trans('Knowledge', [], 'templates'))
            ->setIsNumericNotes(true)
            ->setWeight(0)
        ;

        $behavior = new ActivityType();
        $behavior
            ->setName($translator->trans('Behavior', [], 'templates'))
            ->setIsNumericNotes(false)
            ->setWeight(1)
        ;


        $transversalKnowledge = new ActivityType();
        $transversalKnowledge
            ->setName($translator->trans('Transversal knowledge', [], 'templates'))
            ->setIsNumericNotes(true)
            ->setWeight(2)
        ;
    }


    /**
     * Return available activity types count.
     * @return int
     */
    public function activityTypesCount()
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $queryBuilder
            ->select('count(a.id)')
            ->from(ActivityType::class, 'a')
        ;

        try {
            $count = $queryBuilder->getQuery()->getSingleScalarResult();
        } catch (NoResultException $e) {
            return 0;
        } catch (NonUniqueResultException $e) {
            return 1;
        }

        return intval($count);

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
