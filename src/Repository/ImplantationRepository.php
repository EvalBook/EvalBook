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

use App\Entity\Implantation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

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


    /**
     * Return true if entity already exists.
     * @param Implantation $implantation
     * @return int|mixed|string
     */
    public function implantationNameAlreadyTaken(Implantation $implantation)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $queryBuilder
            ->select('count(i.id)')
            ->from(Implantation::class, 'i')
            ->where('i.id != :id')
            ->andWhere('i.name = :implantation_name')
            ->andWhere('i.school = :id_school')
            ->setParameter('id', $implantation->getId())
            ->setParameter('implantation_name', $implantation->getName())
            ->setParameter('id_school', $implantation->getSchool()->getId());

        try {
            $count = $queryBuilder->getQuery()->getSingleScalarResult();
        } catch (NoResultException $e) {
            return false;
        } catch (NonUniqueResultException $e) {
            return true;
        }

        return intval($count) !== 0;
    }

}