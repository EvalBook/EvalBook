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
     * Populate the database with the defult note types values.
     */
    public function populate()
    {
        $em = $this->getEntityManager();

        // A..F interval
        $noteType1 = new NoteType();
        $noteType1->setName("A..F");
        $noteType1->setPonderation("A..F");

        $noteType2 = new NoteType();
        $noteType2->setName("A..Z");
        $noteType2->setPonderation("A..Z");

        $em->persist($noteType1);
        $em->persist($noteType2);


        for($i = 5; $i <= 100; $i += 5) {
            $nt = new NoteType();
            $nt->setName("0..$i");
            $nt->setPonderation("0..$i");
            $em->persist($nt);
        }

        $em->flush();
        $em->flush();
    }
}
