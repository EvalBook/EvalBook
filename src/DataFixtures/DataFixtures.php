<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use App\Entity\Implantation;
use Doctrine\Persistence\ObjectManager;

class DataFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        $implantation1 = $this->generateImplantations('Implantation de Bruxelles', 'rue du centre 3','1000', 'Bruxelles');
        $implantation2 = $this->generateImplantations('Implantation de Namur','rue de la place 2','5000', 'Namur');
        $implantation3 = $this->generateImplantations('Implantation de Mons', 'rue du marché 4','7000', 'Mons');
        $implantation4 = $this->generateImplantations('Implantation de Liège', 'rue de la gloire 60','4000', 'Liège');



        $manager->flush();
    }


    /**
     * Generate fake implantations
     *
     * @param ObjectManager $manager
     * @param string $name
     * @param string $address
     * @param string $zipCode
     * @param string $country
     * @return Implantation
     */
    public function generateImplantations(ObjectManager $manager, string $name, string $address, string $zipCode, string $country)
    {
        $implantation = new Implantation();
        $implantation
            ->setName($name)
            ->setAddress($address)
            ->setZipCode($zipCode)
            ->setCountry($country)
        ;
        $manager->persist($implantation);
        return $implantation;
    }
}