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

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $firstName;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Classroom", inversedBy="users")
     */
    private $classrooms;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Activity", mappedBy="user")
     */
    private $activities;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Classroom", mappedBy="owner", cascade={"persist", "remove"})
     */
    private $classroomsOwner;

    /**
     * @ORM\OneToOne(targetEntity=UserConfiguration::class, mappedBy="user", cascade={"persist", "remove"})
     */
    private $userConfiguration;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $last_login;


    /**
     * Check haveibeenspown.
     * @param ClassMetadata $metadata
     */
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('password', new Assert\NotCompromisedPassword());
    }


    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->classrooms = new ArrayCollection();
        $this->activities = new ArrayCollection();
    }


    /**
     * Return the User ID.
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * Return the User mail.
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }


    /**
     * Set the User mail.
     * @param string $email
     * @return $this
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }


    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->getLastName() . " " . $this->getFirstName();
    }


    /**
     * Return User roles.
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }


    /**
     * Set the User roles.
     * @param array $roles
     * @return $this
     */
    public function setRoles(array $roles): self
    {
        // Make sure at least ROLE_USER exists in database.
        if(empty($roles))
            $roles[] = 'ROLE_USER';
        $this->roles = $roles;
        return $this;
    }


    /**
     * Return true if the user has at least one of the provided role in array.
     * @param array $roles
     * @return boolean
     */
    public function firstRoleMatch(array $roles)
    {
        foreach($this->getRoles() as $role) {
            if(in_array($role, $roles)) {
                return true;
            }
        }
        return in_array("ROLE_ADMIN", $this->getRoles());
    }


    /**
     * Return the User password.
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }


    /**
     * Set the user Password.
     * @param string $password
     * @return $this
     */
    public function setPassword(?string $password): self
    {
        if(!is_null($password))
            $this->password = $password;
        return $this;
    }


    /**
     * Erase credentials.
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }


    /**
     * Return the last name.
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }


    /**
     * Set the last name.
     * @param string $lastName
     * @return $this
     */
    public function setLastName(string $lastName): self
    {
        $this->lastName = ucfirst(strtolower($lastName));
        return $this;
    }


    /**
     * Return the first name.
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }


    /**
     * Set the user first name.
     * @param string $firstName
     * @return $this
     */
    public function setFirstName(string $firstName): self
    {
        $this->firstName = ucfirst(strtolower($firstName));
        return $this;
    }


    /**
     * Return a collection of Classes the user own.
     * @return Collection|Classroom[]
     */
    public function getClassrooms(): Collection
    {
        $classrooms = $this->classrooms;
        foreach($this->getClassroomsOwner() as $classroomOwner)
        {
            if(!$classrooms->contains($classroomOwner)) {
                $classrooms[] = $classroomOwner;
            }
        }

        return $classrooms;
    }


    /**
     * Add a class to the user.
     * @param Classroom $classroom
     * @return $this
     */
    public function addClassroom(Classroom $classroom): self
    {
        if (!$this->classrooms->contains($classroom)) {
            $this->classrooms[] = $classroom;
        }

        return $this;
    }


    /**
     * Remove a class a user own.
     * @param Classroom $classroom
     * @return $this
     */
    public function removeClassroom(Classroom $classroom): self
    {
        if ($this->classrooms->contains($classroom)) {
            $this->classrooms->removeElement($classroom);
            $classroom->removeUser($this);
        }

        return $this;
    }


    /**
     * Return a collection of Activite User created.
     * @return Collection|Activity[]
     */
    public function getActivities(): Collection
    {
        return $this->activities;
    }


    /**
     * Remove an Activite from the User Activite collection.
     * @param Activity $activity
     * @return $this
     */
    public function removeActivity(Activity $activity): self
    {
        if ($this->activities->contains($activity)) {
            $this->activities->removeElement($activity);
            // set the owning side to null (unless already changed)
            if ($activity->getUser() === $this) {
                $activity->setUser(null);
            }
        }

        return $this;
    }


    /**
     * Return the Classe the User is 'titulaire'.
     * @return Collection|null
     */
    public function getClassroomsOwner(): ?Collection
    {
        return $this->classroomsOwner;
    }


    /**
     * Return students that are attached to at least one of this user class.
     * @return array
     */
    public function getStudents()
    {
        $students = array();
        foreach($this->getClassrooms() as $classroom) {
            foreach ($classroom->getStudents() as $student) {
                $students[] = $student;
            }
        }

        return array_unique($students);
    }

    /**
     * Return the User string representation.
     * @return string
     */
    public function __toString()
    {
        return $this->getFirstName() . " " . $this->getLastName();
    }


    /**
     * Unused -> interface related method.
     * @return string|void|null
     */
    public function getSalt()
    {
        // Implement getSalt() method.
    }


    /**
     * Get the current user configuration.
     * @return UserConfiguration|null
     */
    public function getUserConfiguration(): ?UserConfiguration
    {
        return $this->userConfiguration;
    }


    /**
     * Set the current user configuration.
     * @param UserConfiguration|null $userConfiguration
     * @return $this
     */
    public function setUserConfiguration(?UserConfiguration $userConfiguration): self
    {
        $this->userConfiguration = $userConfiguration;

        // set (or unset) the owning side of the relation if necessary
        $newUser = null === $userConfiguration ? null : $this;
        if ($userConfiguration->getUser() !== $newUser) {
            $userConfiguration->setUser($newUser);
        }

        return $this;
    }


    /**
     * Return the user last login date.
     * @return \DateTimeInterface|null
     */
    public function getLastLogin(): ?\DateTimeInterface
    {
        return $this->last_login;
    }


    /**
     * Set the user last login date.
     * @param \DateTimeInterface|null $last_login
     * @return $this
     */
    public function setLastLogin(?\DateTimeInterface $last_login): self
    {
        $this->last_login = $last_login;

        return $this;
    }


    /**
     * Return a list of all available roles.
     */
    public static function getAssignableRoles(): array
    {
        return array(
            // Users related.
            'ROLE_USER_LIST_ALL',
            'ROLE_USER_CREATE',
            'ROLE_USER_EDIT',
            'ROLE_USER_DELETE',
            // Students related.
            'ROLE_STUDENT_LIST_ALL',
            'ROLE_STUDENT_CREATE',
            'ROLE_STUDENT_EDIT',
            'ROLE_STUDENT_DELETE',
            // Classes related.
            'ROLE_CLASS_LIST_ALL',
            'ROLE_CLASS_CREATE',
            'ROLE_CLASS_EDIT',
            'ROLE_CLASS_DELETE',
            'ROLE_CLASS_EDIT_STUDENTS',
            'ROLE_CLASS_EDIT_USERS',
            // Implantations related.
            'ROLE_IMPLANTATION_LIST_ALL',
            'ROLE_IMPLANTATION_EDIT',
            'ROLE_IMPLANTATION_CREATE',
            'ROLE_IMPLANTATION_DELETE',
            // Schools related.
            'ROLE_SCHOOL_LIST_ALL',
            'ROLE_SCHOOL_EDIT',
            'ROLE_SCHOOL_CREATE',
            'ROLE_SCHOOL_DELETE',
            'ROLE_SCHOOL_REPORT_PRINT',
        );
    }


    /**
     * Return a list of pre-defined users types ( roles ).
     * @return array
     */
    public static function getPredefinedRoleSet()
    {
        return [
            'Director' => [],
            'Secretary' => [],
            'Master' => [],
            'Special master' => [],
            'Substitute master' => [],
            'Implantation manager' => [],
        ];
    }
}