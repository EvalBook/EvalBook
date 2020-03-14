<?php

declare(strict_types=1);

namespace App\Entity\InstallHelper;

use App\Entity\Role;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

/**
 * An helper class to help creating a simple user that match EvalBook common roles.
 * This object is intended to be used at installer level, not in the logic system.
 *
 * Class Secretary
 *
 * @package App\Entity\InstallHelper
 */
class CommonUserHelper
{

    private $em;
    private $userRepository;


    /**
     * Create a commonly used User with User right role(s).
     * EvalBookCommonUser constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->userRepository = $this->em->getRepository(User::class);
    }


    /**
     * Create a User that matches Secretary role.
     * @param string $firstName
     * @param string $lastName
     * @param string $email
     * @param string $password
     * @return bool
     */
    public function createSecretary(string $firstName, string $lastName, string $email, string $password)
    {
        $roles = array();
        return $this->createCommonUser($firstName, $lastName, $email, $password, $roles);
    }


    /**
     * Create a User that matches Director role.
     * @param string $firstName
     * @param string $lastName
     * @param string $email
     * @param string $password
     * @return bool
     */
    public function createDirector(string $firstName, string $lastName, string $email, string $password)
    {
        $roles = array();
        return $this->createCommonUser($firstName, $lastName, $email, $password, $roles);
    }

    /**
     * Create a User that matches Director role.
     * @param string $firstName
     * @param string $lastName
     * @param string $email
     * @param string $password
     * @return bool
     */
    public function createTeacher(string $firstName, string $lastName, string $email, string $password)
    {
        $roles = array();
        return $this->createCommonUser($firstName, $lastName, $email, $password, $roles);
    }


    /**
     * Create a common user.
     * @param string $firstName
     * @param string $lastName
     * @param string $email
     * @param string $password
     * @param Role[] $roles
     * @return User|bool
     */
    private function createCommonUser(string $firstName, string $lastName, string $email, string $password, array $roles)
    {
        if($this->userRepository->userExists($email))
            return false;

        try {
            $user = new User();
            $user->setPassword($password);
            $user->setLastName($lastName);
            $user->setFirstName($firstName);
            $user->setEmail($email);
            $user->setActive(true);
            //$user->setRoles($roles);

            $this->em->persist($user);
            $this->em->flush();
        }
        catch(Exception $e) {
            return false;
        }

        return null != $user->getId();
    }
}

// TODO retirer toute trace de l'ancienne classe Role.
// TODO remettre les fonctions d'accès rapide telles que getActivites(), ... getClasses()... qui sont liées par relation avec les autres tables.