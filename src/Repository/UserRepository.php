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

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }


    /**
     * Return a user list based on owned roles.
     * @param $role
     *
     * The query direction false: not contains, true: contains.
     * @param $direction
     * @return mixed
     */
    public function findByRole(string $role, bool $direction = true)
    {
        $clause = ($direction ? "LIKE" : "NOT LIKE");

        $qBuilder = $this->_em->createQueryBuilder();
        $qBuilder->select('u')
                  ->from($this->_entityName, 'u')
                  ->where("u.roles $clause :roles")
                  ->setParameter('roles', '%"' . $role . '"%');
        return $qBuilder->getQuery()->getResult();
    }


    /**
     * Return true if entity already exists.
     * @param User $user
     * @return int|mixed|string
     */
    public function userExists(User $user)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $queryBuilder
            ->select('count(u.email)')
            ->from(User::class, 'u')
            ->where('u.email = :email')
        ;

        if($user->getId() !== null) {
            $queryBuilder
                ->andWhere('u.id != :id')
                ->setParameter('id', $user->getId())
            ;
        }

        $queryBuilder->setParameter('email', $user->getEmail());

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


    /**
     * Return true if users exists in database.
     * @return bool
     */
    public function hasUsers()
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder
            ->select('COUNT(u)')
            ->from($this->_entityName, 'u')
        ;

        try {
            return $queryBuilder->getQuery()->getSingleScalarResult() > 0;
        }
        catch (NoResultException $e) {}
        catch (NonUniqueResultException $e) {}
        return true;
    }

}
