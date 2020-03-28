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
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


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
                 ->setRoles(array(
                     'ROLE_USER_LIST_ALL',
                     'ROLE_USER_CREATE',
                     'ROLE_USER_EDIT',
                     'ROLE_USER_DELETE',
                     // Students related.
                     'ROLE_STUDENT_LIST_ALL',
                     'ROLE_STUDENT_CREATE',
                     'ROLE_STUDENT_EDIT',
                     'ROLE_STUDENT_DELETE',
                     // Periods related.
                     'ROLE_PERIOD_LIST_ALL',
                     'ROLE_PERIOD_CREATE',
                     'ROLE_PERIOD_EDIT',
                     'ROLE_PERIOD_DELETE',
                     // Classes related.
                     'ROLE_CLASS_LIST_ALL',
                     'ROLE_CLASS_CREATE',
                     'ROLE_CLASS_EDIT',
                     'ROLE_CLASS_DELETE',
                     'ROLE_CLASS_PARAMETERS',
                     'ROLE_CLASS_VIEW',
                     'ROLE_CLASS_ASSIGN_STUDENT',
                     // Activities related.
                     'ROLE_ACTIVITY_LIST_ALL',
                     'ROLE_ACTIVITY_CREATE',
                     'ROLE_ACTIVITY_EDIT',
                     'ROLE_ACTIVITY_DELETE',
                     // Notebook related.
                     'ROLE_NOTEBOOK_VIEW',
                     // Bulletins related.
                     'ROLE_BULLETIN_LIST_ALL',
                     'ROLE_BULLETIN_PRINT_ALL',
                     'ROLE_BULLETIN_VALIDATE',
                     'ROLE_BULLETIN_ADD_COMMENT',
                     'ROLE_BULLETIN_STYLE_EDIT',
                 ));

            $manager->persist($user);
        }

        $manager->persist($admin);
        $manager->flush();
    }

}
