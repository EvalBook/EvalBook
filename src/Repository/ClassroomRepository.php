<?php

/**
 * Copyleft (c) 2020 EvalBook
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the European Union Public Licence (EUPL V 1.2),
 * version 1.2 (or any later version).
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * European Union Public Licence for more details.
 *
 * You should have received a copy of the European Union Public Licence
 * along with this program. If not, see
 * https://joinup.ec.europa.eu/collection/eupl/eupl-text-eupl-12
 **/

namespace App\Repository;

use App\Entity\Classroom;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

/**
 * @method Classroom|null find($id, $lockMode = null, $lockVersion = null)
 * @method Classroom|null findOneBy(array $criteria, array $orderBy = null)
 * @method Classroom[]    findAll()
 * @method Classroom[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClassroomRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Classroom::class);
    }

    /**
     * Return true if entity already exists.
     * @param Classroom $classroom
     * @return int|mixed|string
     */
    public function classroomNameAlreadyTaken(Classroom $classroom)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $queryBuilder
            ->select('count(c.id)')
            ->from(Classroom::class, 'c')
            ->where('c.id != :id')
            ->andWhere('c.name = :name')
            ->setParameter('id', $classroom->getId())
            ->setParameter('name', $classroom->getName())
        ;

        if(null !== $classroom->getImplantation()) {
            $queryBuilder
                ->andWhere('c.implantation = :id_implantation')
                ->setParameter('id_implantation', $classroom->getImplantation()->getId())
            ;
        }

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
