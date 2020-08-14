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

use App\Entity\Activity;
use App\Entity\Note;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\TransactionRequiredException;

/**
 * @method Activity|null find($id, $lockMode = null, $lockVersion = null)
 * @method Activity|null findOneBy(array $criteria, array $orderBy = null)
 * @method Activity[]    findAll()
 * @method Activity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActivityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Activity::class);
    }


    /**
     * Return the last top 5 user activities.
     * @param int $userId
     * @param int $rowsCount
     * @return int|mixed|string
     */
    public function getUserLastActivities(int $userId, int $rowsCount = 5)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $queryBuilder
            ->select('a')
            ->from(Activity::class, 'a')
            ->where('a.user = :user')
            ->orderBy('a.dateAdded', 'DESC')
            ->setMaxResults($rowsCount)
            ->setParameter('user', $userId)
        ;

        return $queryBuilder->getQuery()->getResult(AbstractQuery::HYDRATE_OBJECT);
    }


    /**
     * Return all activities that does not have been noted yet.
     * @param User $user
     * @return int|mixed|string
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws TransactionRequiredException
     */
    public function getUserActivitiesToNote(User $user)
    {
        $activities = $user->getActivities();
        $activities_id = array_map(function(Activity $activity){ return $activity->getId();}, $activities->toArray());

        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $queryBuilder
            ->select('n')
            ->from(Note::class, 'n')
            ->expr()->in('n.activity', $activities_id)
        ;


        $notes = $queryBuilder->getQuery()->getResult(AbstractQuery::HYDRATE_OBJECT);
        foreach($notes as $note) {
            $fetched = $this->getEntityManager()->find(Activity::class, $note->getActivity());
            if(in_array($fetched, $activities->toArray())) {
                $activities->removeElement($fetched);
            }
        }

        return $activities->toArray();

    }
}
