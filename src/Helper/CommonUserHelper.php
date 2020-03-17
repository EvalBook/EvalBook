<?php

declare(strict_types=1);

namespace App\Helper;

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
 * @package App\Entity\Helper
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
        return $this->createCommonUser($firstName, $lastName, $email, $password, [
            "ROLE_ADMIN",
            'ROLE_USERS_LIST',
            'ROLE_USER_CREATE',
            'ROLE_USER_EDIT',
            'ROLE_USER_DELETE',
            // Students related.
            'ROLE_STUDENTS_LIST',
            'ROLE_STUDENT_CREATE',
            'ROLE_STUDENT_EDIT',
            'ROLE_STUDENT_DELETE',
            // Periods related.
            'ROLE_PERIODS_LIST',
            'ROLE_PERIOD_CREATE',
            'ROLE_PERIOD_EDIT',
            'ROLE_PERIOD_DELETE',
            // Classes related.
            'ROLE_CLASSES_LIST',
            'ROLE_CLASS_CREATE',
            'ROLE_CLASS_EDIT',
            'ROLE_CLASS_DELETE',
            'ROLE_CLASS_PARAMETERS',
            'ROLE_CLASS_VIEW',
            'ROLE_CLASS_ASSIGN_STUDENT',
            // Activities related.
            'ROLE_ACTIVITIES_LIST',
            'ROLE_ACTIVITY_CREATE',
            'ROLE_ACTIVITY_EDIT',
            'ROLE_ACTIVITY_DELETE',
            // Notebook related.
            'ROLE_NOTEBOOK_VIEW',
            // Bulletins related.
            'ROLE_BULLETINS_LIST',
            'ROLE_BULLETINS_PRINT',
            'ROLE_BULLETIN_VALIDATE',
            'ROLE_BULLETIN_ADD_COMMENT',
            'ROLE_BULLETIN_STYLE_EDIT'
        ]);
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
        return $this->createCommonUser($firstName, $lastName, $email, $password, [
            'ROLE_CLASSES_LIST',
            'ROLE_CLASS_VIEW',
        ]);
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
        return $this->createCommonUser($firstName, $lastName, $email, $password, [
            'ROLE_USERS_LIST',
            'ROLE_USER_CREATE',
            'ROLE_USER_EDIT',
            'ROLE_STUDENTS_LIST',
            'ROLE_STUDENT_CREATE',
            'ROLE_STUDENT_EDIT',
            'ROLE_PERIODS_LIST',
            'ROLE_PERIOD_CREATE',
            'ROLE_PERIOD_EDIT',
            'ROLE_CLASSES_LIST',
            'ROLE_CLASS_VIEW',
            'ROLE_NOTEBOOK_VIEW',
        ]);
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
        return $this->createCommonUser($firstName, $lastName, $email, $password, [
            'ROLE_CLASS_CREATE',
            'ROLE_CLASS_EDIT',
            'ROLE_CLASS_PARAMETERS',
            'ROLE_CLASS_VIEW',
            'ROLE_CLASS_ASSIGN_STUDENT',
            'ROLE_ACTIVITIES_LIST',
            'ROLE_ACTIVITY_CREATE',
            'ROLE_ACTIVITY_EDIT',
            'ROLE_ACTIVITY_DELETE',
            'ROLE_NOTEBOOK_VIEW',
            'ROLE_BULLETINS_LIST',
            'ROLE_BULLETINS_PRINT',
            'ROLE_BULLETIN_VALIDATE',
            'ROLE_BULLETIN_ADD_COMMENT',
            'ROLE_BULLETIN_STYLE_EDIT',
        ]);
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
        return $this->createTeacher($firstName, $lastName, $email, $password);
    }


    /**
     * Create a common user.
     * @param string $firstName
     * @param string $lastName
     * @param string $email
     * @param string $password
     * @param array $roles
     * @return User|bool
     */
    private function createCommonUser(string $firstName, string $lastName, string $email, string $password, array $roles)
    {
        if($this->userRepository->userExists($email))
            return false;

        try {
            $user = new User();
            $user->setLastName($lastName);
            $user->setFirstName($firstName);
            $user->setEmail($email);
            $user->setActive(true);

            $user->setRoles($roles);

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