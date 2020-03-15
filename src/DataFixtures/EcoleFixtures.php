<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Ecole;

/**
 * Class EcoleFixtures
 * @package App\DataFixtures
 */
class EcoleFixtures extends Fixture
{
    public const DEFAULT_SCHOOL = 'default-school';

    /**
     * Load the fixtures.
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $school = new Ecole();
        $school->setName("Groupe Charlemagne");

        $this->addReference(self::DEFAULT_SCHOOL, $school);

        $manager->persist($school);
        $manager->flush();
    }

}