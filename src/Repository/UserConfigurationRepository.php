<?php

namespace App\Repository;

use App\Entity\UserConfiguration;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserConfiguration|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserConfiguration|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserConfiguration[]    findAll()
 * @method UserConfiguration[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserConfigurationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserConfiguration::class);
    }
}
