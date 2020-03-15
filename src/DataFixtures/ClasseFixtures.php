<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Classe;

/**
 * Class ClasseFixtures
 * @package App\DataFixtures
 */
class ClasseFixtures extends Fixture implements DependentFixtureInterface
{

    /**
     * Load fixtures.
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        // Adding some classes for each implantation.
        for($reference = 0; $reference < 5; $reference++) {

            for ($i = 0; $i < 40; $i++) {

                $classe = new Classe();
                $classeTypeReference = $i % 2 === 0 ? TypeClasseFixtures::NORMAL_CLASS : TypeClasseFixtures::SPECIAL_CLASS;

                $classe->setName("Classe n°$i")
                    ->setImplantation($this->getReference(ImplantationFixtures::implantationsReference . $reference))
                    ->setTitulaire(null)
                    ->setTypeClasse($this->getReference($classeTypeReference));

                $manager->persist($classe);
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
            TypeClasseFixtures::class,
            ImplantationFixtures::class
        );
    }
}
