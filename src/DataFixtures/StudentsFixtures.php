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

namespace App\DataFixtures;

use App\Entity\Student;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;


class StudentsFixtures extends Fixture implements FixtureInterface, FixtureGroupInterface
{

    public function load(ObjectManager $em)
    {

        $faker = Factory::create('fr_FR');

        // Creating Fake students.
        for($i = 0; $i < 350; $i++) {
            $student = new Student();
            $student
                ->setLastName($faker->lastName)
                ->setFirstName($faker->firstName)
                ->setBirthday($faker->dateTimeBetween('-12 years', 'now'))
            ;
            $em->persist($student);
        }

        // Finally pushing to db.
        $em->flush();
    }

    /**
     * Specify the fixtures groups.
     * @return array
     */
    public static function getGroups(): array
    {
        return ["functional-tests"];
    }
}