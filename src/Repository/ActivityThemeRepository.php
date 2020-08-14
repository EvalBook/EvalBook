<?php

namespace App\Repository;

use App\Entity\ActivityTheme;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @method ActivityTheme|null find($id, $lockMode = null, $lockVersion = null)
 * @method ActivityTheme|null findOneBy(array $criteria, array $orderBy = null)
 * @method ActivityTheme[]    findAll()
 * @method ActivityTheme[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActivityThemeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ActivityTheme::class);
    }

    /**
    * @return ActivityTheme[] Returns an array of ActivityTheme objects ordered by school report display order.
    */
    public function findByWeight()
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
        $em = $this->getEntityManager();

        $themeSubject = new ActivityTheme();
        $themeSubject
            ->setName('subject')
            ->setDisplayName($translator->trans('Theme subject', [], 'templates'))
            ->setIsNumericNotes(true)
            ->setWeight(0)
        ;
        $em->persist($themeSubject);

        $transversalSkill = new ActivityTheme();
        $transversalSkill
            ->setName('transversal_skill')
            ->setDisplayName($translator->trans('Transversal skill', [], 'templates'))
            ->setIsNumericNotes(true)
            ->setWeight(1)
        ;
        $em->persist($transversalSkill);

        $behavior = new ActivityTheme();
        $behavior
            ->setName('behavior')
            ->setDisplayName($translator->trans('Behavior', [], 'templates'))
            ->setIsNumericNotes(false)
            ->setWeight(2)
        ;
        $em->persist($behavior);


        $em->flush();
    }

}
