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

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Implantation;

/**
 * Class ImplantationFixtures
 * @package App\DataFixtures
 */
class ImplantationFixtures extends Fixture implements DependentFixtureInterface
{

    public const implantationsReference = 'implantations-';

    /**
     * Load the fixtures.
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        for($i = 0; $i < 5; $i++) {
            $implantation = new Implantation();
            $implantation
                // Basic object setters.
                ->setName("Implantation name $i")
                ->setAddress("Addresse number $i")
                ->setZipCode("B-6000")
                ->setCountry("Charleroi")
                ->setDefaultImplantation($i === 1)

                // Relation between implantation and school.
                ->setEcole($this->getReference(EcoleFixtures::DEFAULT_SCHOOL));

            // Adding implantation reference for relational fixtures.
            $this->addReference(self::implantationsReference . $i, $implantation);

            $manager->persist($implantation);
        }

        $manager->flush();
    }

    /**
     * @inheritDoc
     */
    public function getDependencies()
    {
        return array(
            EcoleFixtures::class
        );
    }
}
