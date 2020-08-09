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
use Doctrine\Common\Persistence\ManagerRegistry;

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
     * Populate the database with the default note types values to cover a maximum of use cases.
     */
    public function populate()
    {
        $em = $this->getEntityManager();

        for($i = 1; $i < 3; $i++) {
            // 0..5
            $nt05 = new NoteType();
            $nt05
                ->setName("De 0 à 5, coefficient $i")
                ->setCoefficient($i)
                ->setDescription("De 0 à 5, ordre naturel, coefficient $i.")
                ->setMaximum('5')
                ->setMinimum('0')
                ->setIntervals(array_reverse(range(1, 4)))
            ;
            $em->persist($nt05);

            // 0..10
            $nt10 = new NoteType();
            $nt10
                ->setName("De 0 à 10, coefficient $i")
                ->setCoefficient($i)
                ->setDescription("De 0 à 10, ordre naturel, coefficient $i.")
                ->setMaximum('10')
                ->setMinimum('0')
                ->setIntervals(array_reverse(range(1, 9)))
            ;
            $em->persist($nt10);

            // 0..20
            $nt20 = new NoteType();
            $nt20
                ->setName("De 0 à 20, coefficient $i")
                ->setCoefficient($i)
                ->setDescription("De 0 à 20, ordre naturel, coefficient $i.")
                ->setMaximum('20')
                ->setMinimum('0')
                ->setIntervals(array_reverse(range(1, 19)))
            ;
            $em->persist($nt20);


            // 0..30
            $nt30 = new NoteType();
            $nt30
                ->setName("De 0 à 30, coefficient $i")
                ->setCoefficient($i)
                ->setDescription("De 0 à 30, ordre naturel, coefficient $i.")
                ->setMaximum('20')
                ->setMinimum('0')
                ->setIntervals(array_reverse(range(1, 29)))
            ;
            $em->persist($nt30);

            // 0..100
            $nt100 = new NoteType();
            $nt100
                ->setName("De 0 à 100, coefficient $i")
                ->setCoefficient($i)
                ->setDescription("De 0 à 100, ordre naturel, coefficient $i.")
                ->setMaximum('100')
                ->setMinimum('0')
                ->setIntervals(array_reverse(range(1, 99)))
            ;
            $em->persist($nt100);
        }

        // A..F
        $ntAF = new NoteType();
        $ntAF
            ->setName("De A à F")
            ->setCoefficient($i)
            ->setDescription("De A à F, ordre naturel.")
            ->setMaximum('A')
            ->setMinimum('F')
            ->setIntervals(range('B', 'E'))
        ;
        $em->persist($ntAF);

        // A..NA
        $ntANA = new NoteType();
        $ntANA
            ->setName("Acquis - En cours d'acquisition - A revoir - Non acquis")
            ->setCoefficient($i)
            ->setDescription("Acquis, En cours d'acquisition, A revoir, Non acquis")
            ->setMaximum('A')
            ->setMinimum('NA')
            ->setIntervals(['ECA', 'AR'])
        ;
        $em->persist($ntANA);

        // TB...I
        $ntTBI = new NoteType();
        $ntTBI
            ->setName("Très bien - Bien - Moyen - Suffisant - Médiocre - Insuffisant")
            ->setCoefficient($i)
            ->setDescription("Très bien, Bien, Moyen, Suffisant, Médiocre, Insuffisant")
            ->setMaximum('TB')
            ->setMinimum('I')
            ->setIntervals(['Bien', 'Moyen', 'Suffisant', 'Médiocre', 'Insuffisant'])
        ;
        $em->persist($ntTBI);

        $em->flush();
    }
}
