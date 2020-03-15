<?php

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
