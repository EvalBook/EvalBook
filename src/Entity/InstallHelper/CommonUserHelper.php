<?php

declare(strict_types=1);

namespace App\Entity\InstallHelper;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
    private $passwordEncoder;


    /**
     * Create a commonly used User with User right role(s).
     * EvalBookCommonUser constructor.
     * @param EntityManagerInterface $em
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->em = $em;
        $this->passwordEncoder = $passwordEncoder;
        $this->userRepository = $this->em->getRepository(User::class);
    }


    /**
     * Create a User that matches Super Admin role.
     * @param string $firstName
     * @param string $lastName
     * @param string $email
     * @param string $password
     * @return User|bool
     */
    public function createSuperAdmin(string $firstName, string $lastName, string $email, string $password)
    {
        return $this->createCommonUser($firstName, $lastName, $email, $password);
    }


    /**
     * Create a User that matches Secretary role.
     * @param string $firstName
     * @param string $lastName
     * @param string $email
     * @param string $password
     * @return User|bool
     */
    public function createSecretary(string $firstName, string $lastName, string $email, string $password)
    {
        return $this->createCommonUser($firstName, $lastName, $email, $password);
    }


    /**
     * Create a User that matches Director role.
     * @param string $firstName
     * @param string $lastName
     * @param string $email
     * @param string password
     * @return User|bool
     */
    public function createDirector(string $firstName, string $lastName, string $email, string $password)
    {
        return $this->createCommonUser($firstName, $lastName, $email, $password);
    }

    /**
     * Create a User that matches Teacher role.
     * @param string $firstName
     * @param string $lastName
     * @param string $email
     * @param string password
     * @return User|bool
     */
    public function createTeacher(string $firstName, string $lastName, string $email, string $password)
    {
        return $this->createCommonUser($firstName, $lastName, $email, $password);
    }


    /**
     * Create a User that matches Special Teacher role.
     * @param string $firstName
     * @param string $lastName
     * @param string $email
     * @param string password
     * @return User|bool
     */
    public function createSpecialTeacher(string $firstName, string $lastName, string $email, string $password)
    {
        return $this->createCommonUser($firstName, $lastName, $email, $password);
    }


    /**
     * Create a common user.
     * @param string $firstName
     * @param string $lastName
     * @param string $email
     * @param string $password
     * @return User|bool
     */
    private function createCommonUser(string $firstName, string $lastName, string $email, string $password)
    {
        if($this->userRepository->userExists($email))
            return false;

        try {
            $user = new User();
            $user->setLastName($lastName);
            $user->setFirstName($firstName);
            $user->setEmail($email);
            $user->setActive(true);

            // Encoding password.
            $user->setPassword($this->passwordEncoder->encodePassword($user, $password));

            $this->em->persist($user);
            $this->em->flush();
        }
        catch(Exception $e) {
            return false;
        }

        return null != $user->getId();
    }
}