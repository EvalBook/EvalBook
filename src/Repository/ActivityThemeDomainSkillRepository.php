<?php

namespace App\Repository;

use App\Entity\ActivityThemeDomainSkill;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ActivityThemeDomainSkill|null find($id, $lockMode = null, $lockVersion = null)
 * @method ActivityThemeDomainSkill|null findOneBy(array $criteria, array $orderBy = null)
 * @method ActivityThemeDomainSkill[]    findAll()
 * @method ActivityThemeDomainSkill[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActivityThemeDomainSkillRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ActivityThemeDomainSkill::class);
    }

}
