<?php

namespace App\Repository;

use App\Entity\ActivityTheme;
use App\Entity\ActivityThemeDomain;
use App\Entity\Classroom;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @method ActivityThemeDomain|null find($id, $lockMode = null, $lockVersion = null)
 * @method ActivityThemeDomain|null findOneBy(array $criteria, array $orderBy = null)
 * @method ActivityThemeDomain[]    findAll()
 * @method ActivityThemeDomain[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActivityThemeDomainRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ActivityThemeDomain::class);
    }


    /**
     * Find activity theme domain by type ( generic or special classroom and by classroom id ).
     * @param String $type
     * @param Classroom $classroom
     * @param bool $includeDefaults
     * @return int|mixed|string
     */
    public function findByTypeAndClassroom(String $type, Classroom $classroom, bool $includeDefaults = false)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $queryBuilder
            ->select('a')
            ->from(ActivityThemeDomain::class, 'a')
            ->where('a.type = :type')
        ;
        if(!$includeDefaults) {
            $queryBuilder
                ->andWhere('a.classroom = :classroom')
                ->setParameter('classroom', $classroom->getId())
            ;
        }
        else {
            $queryBuilder->expr()->in('classroom', [null, $classroom->getId()]);
        }

        $queryBuilder
            ->setParameter('type', $type)
        ;

        return $queryBuilder->getQuery()->getResult(AbstractQuery::HYDRATE_OBJECT);
    }


    /**
     * Populate database with default activity theme domains ( most commonly used ).
     * @param TranslatorInterface $translator
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function populate(TranslatorInterface $translator)
    {
        $em = $this->getEntityManager();
        $activityThemesRepository = $em->getRepository(ActivityTheme::class);

        // Only if activity themes not already populated with default values.
        if($activityThemesRepository->count([]) > 0) {
            // Skill.
            $french = new ActivityThemeDomain();
            $french
                ->setName('french')
                ->setDisplayName($translator->trans('French', [], 'templates'))
                ->setActivityTheme($activityThemesRepository->findOneBy([
                    // Skill = 0
                    'weight' => '0'
                ]))
                ->setType(ActivityThemeDomain::TYPE_GENERIC)
            ;
            $em->persist($french);

            $maths = new ActivityThemeDomain();
            $maths
                ->setName('mathematics')
                ->setDisplayName($translator->trans('Mathematics', [], 'templates'))
                ->setActivityTheme($activityThemesRepository->findOneBy([
                    // Skill = 0
                    'weight' => '0'
                ]))
                ->setType(ActivityThemeDomain::TYPE_GENERIC)
            ;

            $em->persist($maths);

            $eveil = new ActivityThemeDomain();
            $eveil
                ->setName('history_geography')
                ->setDisplayName($translator->trans('History / Geography', [], 'templates'))
                ->setActivityTheme($activityThemesRepository->findOneBy([
                    // Skill = 0
                    'weight' => '0'
                ]))
                ->setType(ActivityThemeDomain::TYPE_GENERIC)
            ;
            $em->persist($eveil);


            $em->persist($maths);

            $specialClassrooms = new ActivityThemeDomain();
            $specialClassrooms
                ->setName('special_classrooms')
                ->setDisplayName($translator->trans('Special classrooms', [], 'templates'))
                ->setActivityTheme($activityThemesRepository->findOneBy([
                    // Skill = 0
                    'weight' => '0'
                ]))
                ->setType(ActivityThemeDomain::TYPE_SPECIAL_CLASSROOM)
            ;
            $em->persist($specialClassrooms);


            // Behavior activity type.
            $behaviorWithMaster = new ActivityThemeDomain();
            $behaviorWithMaster
                ->setName('behavior_classroom_owner')
                ->setDisplayName($translator->trans('Behavior with classroom owner', [], 'templates'))
                ->setActivityTheme($activityThemesRepository->findOneBy([
                    // Behavior = 2
                    'weight' => '2'
                ]))
                ->setType(ActivityThemeDomain::TYPE_GENERIC)
            ;
            $em->persist($behaviorWithMaster);

            $behaviorWithSpecialMasters = new ActivityThemeDomain();
            $behaviorWithSpecialMasters
                ->setName('special_classroom_masters_generic')
                ->setDisplayName($translator->trans('Behavior with special masters', [], 'templates'))
                ->setActivityTheme($activityThemesRepository->findOneBy([
                    // Behavior = 2
                    'weight' => '2'
                ]))
                ->setType(ActivityThemeDomain::TYPE_SPECIAL_CLASSROOM)
            ;
            $em->persist($behaviorWithSpecialMasters);

            $em->flush();
        }
    }
}
