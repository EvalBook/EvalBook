<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\TypeClasse;


/**
 * Class TypeClasseFixtures
 * @package App\DataFixtures
 */
class TypeClasseFixtures extends Fixture
{

    /**
     * Load fixtures.
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $typeClasse1 = new TypeClasse();
        $typeClasse2 = new TypeClasse();

        $typeClasse1->setName("Classe normale");
        $typeClasse2->setName("Classe maître spécial");

        $manager->persist($typeClasse1);
        $manager->persist($typeClasse2);
        $manager->flush();
    }
}
