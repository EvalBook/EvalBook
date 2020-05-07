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
use App\Entity\Periode;


class PeriodeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for($reference = 0; $reference < 5; $reference++) {

            // Checking reference existance, and then, adding implantations.
            if($this->hasReference(ImplantationFixtures::implantationsReference . $reference)) {

                // Each implantation has 4 periods at least.
                for ($i = 0; $i < 4; $i++) {
                    $periode = new Periode();
                    $periode->setName("Periode $i")
                        ->setDateStart(new \DateTime('2019-09-01'))
                        ->setDateEnd(new \DateTime('2020-06-30'))
                        ->setImplantation($this->getReference(ImplantationFixtures::implantationsReference . $reference));

                    $manager->persist($periode);
                }
            }
        }

        $manager->flush();
    }


    /**
     * @inheritDoc
     */
    public function getDependencies()
    {
        return array(
            ImplantationFixtures::class
        );
    }
}
