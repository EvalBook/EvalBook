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

use App\Entity\NoteType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method NoteType|null find($id, $lockMode = null, $lockVersion = null)
 * @method NoteType|null findOneBy(array $criteria, array $orderBy = null)
 * @method NoteType[]    findAll()
 * @method NoteType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NoteTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NoteType::class);
    }


    /**
     * Return available note types by numeric - non numeric....
     * @param bool $isNumeric
     * @param int|null $coefficient
     * @return array
     */
    public function findByType(bool $isNumeric)
    {
        $numericNotesTypes = [];
        $mixedNoteTypes = [];

        $noteTypes = $this->getEntityManager()->getRepository($this->getClassName())->findAll();

        if(count($noteTypes) === 0)
            return [];

        // Iterate over note types to check which ones are numeric.
        /* @var $noteType NoteType */
        foreach($noteTypes as $noteType) {
            if(is_numeric($noteType->getMaximum()) && is_numeric($noteType->getMinimum())) {
                $numericIntervals = array_filter(
                    $noteType->getIntervals(),
                    function($interval){
                        return is_numeric($interval);
                    });
                if(count($numericIntervals) === count($noteType->getIntervals())) {
                    $numericNotesTypes[] = $noteType;
                }
                else {
                    $mixedNoteTypes[] = $noteType;
                }
            }
            else {
                $mixedNoteTypes[] = $noteType;
            }
        }

        return $isNumeric ? $numericNotesTypes : $mixedNoteTypes;
    }


    /**
     * Populate the database with the default note types values to cover a maximum of use cases.
     */
    public function populate()
    {
        $em = $this->getEntityManager();

        // 0..5
        $nt05 = new NoteType();
        $nt05
            ->setName("0 -> 5")
            ->setDescription("0 -> 5")
            ->setMaximum('5')
            ->setMinimum('0')
            ->setIntervals(range(1, 4))
        ;
        $em->persist($nt05);

        // 0..10
        $nt10 = new NoteType();
        $nt10
            ->setName("0 -> 10")
            ->setDescription("0 -> 10")
            ->setMaximum('10')
            ->setMinimum('0')
            ->setIntervals(range(1, 9))
        ;
        $em->persist($nt10);

        // 0..20
        $nt20 = new NoteType();
        $nt20
            ->setName("0 -> 20")
            ->setDescription("0 -> 20")
            ->setMaximum('20')
            ->setMinimum('0')
            ->setIntervals(range(1, 19))
        ;
        $em->persist($nt20);


        // 0..25
        $nt25 = new NoteType();
        $nt25
            ->setName("0 -> 25")
            ->setDescription("0 -> 25")
            ->setMaximum('25')
            ->setMinimum('0')
            ->setIntervals(range(1, 24))
        ;
        $em->persist($nt25);


        // 0..30
        $nt30 = new NoteType();
        $nt30
            ->setName("0 -> 30")
            ->setDescription("0 -> 30")
            ->setMaximum('30')
            ->setMinimum('0')
            ->setIntervals(range(1, 29))
        ;
        $em->persist($nt30);


        // 0..50
        $nt50 = new NoteType();
        $nt50
            ->setName("0 -> 50")
            ->setDescription("0 -> 50")
            ->setMaximum('50')
            ->setMinimum('0')
            ->setIntervals(range(1, 49))
        ;
        $em->persist($nt50);


        // 0..60
        $nt60 = new NoteType();
        $nt60
            ->setName("0 -> 60")
            ->setDescription("0 -> 60")
            ->setMaximum('60')
            ->setMinimum('0')
            ->setIntervals(range(1, 59))
        ;
        $em->persist($nt60);


        // 0..100
        $nt100 = new NoteType();
        $nt100
            ->setName("0 -> 100 coeff")
            ->setDescription("0 -> 100")
            ->setMaximum('100')
            ->setMinimum('0')
            ->setIntervals(range(1, 99))
        ;
        $em->persist($nt100);

        // A..F
        $ntAF = new NoteType();
        $ntAF
            ->setName("A -> F")
            ->setDescription("A -> F, ordre naturel.")
            ->setMaximum('A')
            ->setMinimum('F')
            ->setIntervals(range('E', 'B'))
        ;
        $em->persist($ntAF);

        // A..NA
        $ntANA = new NoteType();
        $ntANA
            ->setName("Acquis - En cours d'acquisition - A revoir - Non acquis")
            ->setDescription("Acquis, En cours d'acquisition, A revoir, Non acquis")
            ->setMaximum('A')
            ->setMinimum('NA')
            ->setIntervals(['AR', 'ECA'])
        ;
        $em->persist($ntANA);

        // TB...I
        $ntTBI = new NoteType();
        $ntTBI
            ->setName("Très bien - Bien - Moyen - Suffisant - Médiocre - Insuffisant")
            ->setDescription("Très bien, Bien, Moyen, Suffisant, Médiocre, Insuffisant")
            ->setMaximum('TB')
            ->setMinimum('I')
            ->setIntervals(['Médiocre', 'Suffisant', 'Moyen', 'Bien'])
        ;
        $em->persist($ntTBI);

        $em->flush();
    }
}
