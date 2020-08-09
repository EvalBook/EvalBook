<?php

namespace App\Repository;

use App\Entity\KnowledgeType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method KnowledgeType|null find($id, $lockMode = null, $lockVersion = null)
 * @method KnowledgeType|null findOneBy(array $criteria, array $orderBy = null)
 * @method KnowledgeType[]    findAll()
 * @method KnowledgeType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class KnowledgeTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, KnowledgeType::class);
    }

}
