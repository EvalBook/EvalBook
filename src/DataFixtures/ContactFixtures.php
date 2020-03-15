<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Contact;

/**
 * Class ContactFixtures
 * @package App\DataFixtures
 */
class ContactFixtures extends Fixture
{
    /**
     * Load the fixtures.
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        for($i = 0; $i < 50; $i++) {
            $contact = new Contact();
            $contact->setFirstName("First name $i")
                ->setLastName("Last name $i")
                ->setAddress("Address from street $i")
                ->setZipCode("B-7000")
                ->setCountry("Mons");
            $manager->persist($contact);
        }
        $manager->flush();
    }
}
