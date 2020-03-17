<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;


class UserFixtures extends Fixture
{
    private $encoder;

    /**
     * UserFixtures constructor.
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * Load fixtures.
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $admin = new User();
        $admin->setLastName("Admin")
              ->setFirstName("Admin")
              ->setEmail("admin@evalbook.dev")
              ->setPassword($this->encoder->encodePassword($admin, "Dev007!!"))
              ->setActive(true)
              ->setRoles(array("ROLE_ADMIN"));

        for($i = 0; $i < 15; $i++) {
            $user = new User();
            $user->setLastName("LnUser $i")
                 ->setFirstName("FnUser $i")
                 ->setEmail("user-$i@evalbook.dev")
                 ->setPassword($this->encoder->encodePassword($admin, "Dev007!!"))
                 ->setActive(true)
                 ->setRoles(array("ROLE_ADMIN"));

            $manager->persist($user);
        }

        $manager->persist($admin);
        $manager->flush();
    }

}
