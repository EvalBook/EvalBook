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
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Eleve;

class EleveFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for($i = 0; $i < 300; $i++) {
            $eleve = new Eleve();
            $eleve->setLastName("EleveLn $i")
                  ->setFirstName("EleveFn $i")
                  ->setActive(true);

            $manager->persist($eleve);
        }

        $manager->flush();
    }
}
