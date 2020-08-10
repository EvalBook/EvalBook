<?php

namespace App\Repository;

use App\Entity\ActivityType;
use App\Entity\ActivityTypeChild;
use App\Entity\Classroom;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @method ActivityTypeChild|null find($id, $lockMode = null, $lockVersion = null)
 * @method ActivityTypeChild|null findOneBy(array $criteria, array $orderBy = null)
 * @method ActivityTypeChild[]    findAll()
 * @method ActivityTypeChild[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActivityTypeChildRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ActivityTypeChild::class);
    }


    /**
     * Find activity type child(ren) by type ( generic or special classroom and by classroom id ).
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
            ->from(ActivityTypeChild::class, 'a')
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
     * Populate database with default activity types children ( most commonly used ).
     * @param TranslatorInterface $translator
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function populate(TranslatorInterface $translator)
    {
        $em = $this->getEntityManager();
        $activityTypesRepository = $em->getRepository(ActivityType::class);

        // Only if activity types already populated with default values.
        if($activityTypesRepository->count([]) > 0) {
            // Knowledge activity type.
            $french = new ActivityTypeChild();
            $french
                ->setName('french')
                ->setDisplayName($translator->trans('French', [], 'templates'))
                ->setActivityType($activityTypesRepository->findOneBy([
                    // Knowledge = 0
                    'weight' => '0'
                ]))
                ->setType(ActivityTypeChild::TYPE_GENERIC)
            ;
            $em->persist($french);

            $maths = new ActivityTypeChild();
            $maths
                ->setName('mathematics')
                ->setDisplayName($translator->trans('Mathematics', [], 'templates'))
                ->setActivityType($activityTypesRepository->findOneBy([
                    // Knowledge = 0
                    'weight' => '0'
                ]))
                ->setType(ActivityTypeChild::TYPE_GENERIC)
            ;

            $em->persist($maths);

            $eveil = new ActivityTypeChild();
            $eveil
                ->setName('history_geography')
                ->setDisplayName($translator->trans('History / Geography', [], 'templates'))
                ->setActivityType($activityTypesRepository->findOneBy([
                    // Knowledge = 0
                    'weight' => '0'
                ]))
                ->setType(ActivityTypeChild::TYPE_GENERIC)
            ;
            $em->persist($eveil);


            $em->persist($maths);

            $specialClassrooms = new ActivityTypeChild();
            $specialClassrooms
                ->setName('special_classrooms')
                ->setDisplayName($translator->trans('Special classrooms', [], 'templates'))
                ->setActivityType($activityTypesRepository->findOneBy([
                    // Knowledge = 0
                    'weight' => '0'
                ]))
                ->setType(ActivityTypeChild::TYPE_SPECIAL_CLASSROOM)
            ;
            $em->persist($specialClassrooms);


            // Behavior activity type.
            $behaviorWithMaster = new ActivityTypeChild();
            $behaviorWithMaster
                ->setName('behavior_classroom_owner')
                ->setDisplayName($translator->trans('Behavior with classroom owner', [], 'templates'))
                ->setActivityType($activityTypesRepository->findOneBy([
                    // Behavior = 2
                    'weight' => '2'
                ]))
                ->setType(ActivityTypeChild::TYPE_GENERIC)
            ;
            $em->persist($behaviorWithMaster);

            $behaviorWithSpecialMasters = new ActivityTypeChild();
            $behaviorWithSpecialMasters
                ->setName('special_classroom_masters_generic')
                ->setDisplayName($translator->trans('Behavior with special masters', [], 'templates'))
                ->setActivityType($activityTypesRepository->findOneBy([
                    // Behavior = 2
                    'weight' => '2'
                ]))
                ->setType(ActivityTypeChild::TYPE_SPECIAL_CLASSROOM)
            ;
            $em->persist($behaviorWithSpecialMasters);

            $em->flush();
        }
    }
}
