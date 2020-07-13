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

use App\Entity\StudentContact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method StudentContact|null find($id, $lockMode = null, $lockVersion = null)
 * @method StudentContact|null findOneBy(array $criteria, array $orderBy = null)
 * @method StudentContact[]    findAll()
 * @method StudentContact[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StudentContactRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StudentContact::class);
    }

    /**
     * Return true if entity already exists.
     * @param StudentContact $contact
     * @return int|mixed|string
     */
    public function contactExists(StudentContact $contact)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $queryBuilder
            ->select('count(sc.email)')
            ->from(StudentContact::class, 'sc')
            ->where('sc.email = :email')
        ;

        if($contact->getId() !== null) {
            $queryBuilder
                ->andWhere('sc.id != :id')
                ->setParameter('id', $contact->getId())
            ;
        }

        $queryBuilder->setParameter('email', $contact->getEmail());

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
