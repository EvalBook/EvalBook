<?php

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
