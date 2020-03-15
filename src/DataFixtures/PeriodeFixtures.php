<?php

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
                        ->setActive(true)
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
