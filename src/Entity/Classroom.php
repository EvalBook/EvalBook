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


/**
 * @ORM\Entity(repositoryClass="App\Repository\ClassroomRepository")
 */
class Classroom
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=45)
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", mappedBy="classrooms")
     */
    private $users;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Student", mappedBy="classrooms")
     */
    private $students;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="classroomsOwner")
     */
    private $owner;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Implantation", inversedBy="classrooms")
     * @ORM\JoinColumn(nullable=false)
     */
    private $implantation;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Activity", mappedBy="classroom")
     */
    private $activities;

    /**
     * @ORM\OneToMany(targetEntity=ActivityThemeDomain::class, mappedBy="classroom")
     */
    private $activityThemeDomains;


    /**
     * Classe constructor.
     */
    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->students = new ArrayCollection();
        $this->activities = new ArrayCollection();
        $this->activityThemeDomains = new ArrayCollection();
    }


    /**
     * Return the Classroom ID.
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * Return the classroom name.
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }


    /**
     * Set the classroom name.
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }


    /**
     * Return the available Classroom Users, including 'owner'.
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        $users = $this->users;
        if($this->getOwner() && !$users->contains($this->getOwner()))
            $users->add($this->getOwner());

        return $users;
    }


    /**
     * Enable a user to manage this Classroom by adding it to the object users list.
     * @param User $user
     * @return $this
     */
    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addClassroom($this);
        }

        return $this;
    }


    /**
     * Removes a user from allowed users for the Classroom.
     * @param User $user
     * @return $this
     */
    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            $user->removeClassroom($this);
        }

        return $this;
    }


    /**
     * Removes all users in a classroom and add new ones passed.
     * @param array $usersCollection
     * @return $this
     */
    public function setUsers(array $usersCollection)
    {
        foreach($this->getUsers() as $user)
            $this->removeUser($user);

        foreach($usersCollection as $user)
            $this->addUser($user);

        return $this;
    }


    /**
     * Removes all students in a classroom and add new ones passed.
     * @param array $studentsCollection
     * @return $this
     */
    public function setStudents(array $studentsCollection)
    {
        foreach($this->getStudents() as $student)
            $this->removeStudent($student);

        foreach($studentsCollection as $student)
            $this->addStudent($student);

        return $this;
    }


    /**
     * Return a Collection of Student objects registered to the Classroom object.
     * @return Collection|Student[]
     */
    public function getStudents(): Collection
    {
        return $this->students;
    }


    /**
     * Add a student object to the Classroom object.
     * @param Student $student
     * @return $this
     */
    public function addStudent(Student $student): self
    {
        if (!$this->students->contains($student)) {
            $this->students[] = $student;
            $student->addClassroom($this);
        }

        return $this;
    }


    /**
     * Remove a student Object to the Classroom object.
     * @param Student $student
     * @return $this
     */
    public function removeStudent(Student $student): self
    {
        if ($this->students->contains($student)) {
            $this->students->removeElement($student);
            $student->removeClassroom($this);
        }

        return $this;
    }


    /**
     * Return the User object that own the Class ( aka titulaire ).
     * @return User|null
     */
    public function getOwner(): ?User
    {
        return $this->owner;
    }


    /**
     * Set the class object owner ( aka titulaire ).
     * @param User|null $owner
     * @return $this
     */
    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;
        return $this;
    }


    /**
     * Return the Classroom object implantation.
     * @return Implantation|null
     */
    public function getImplantation(): ?Implantation
    {
        return $this->implantation;
    }


    /**
     * Set the Classe Object implantation.
     * @param Implantation|null $implantation
     * @return $this
     */
    public function setImplantation(?Implantation $implantation): self
    {
        $this->implantation = $implantation;
        return $this;
    }


    /**
     * Return the class activities.
     * @return ArrayCollection
     */
    public function getActivities()
    {
        return $this->activities;
    }


    /**
     * Add an activity object to the Classroom object.
     * @param Activity $activity
     * @return $this
     */
    public function addActivity(Activity $activity): self
    {
        if (!$this->activities->contains($activity)) {
            $this->activities[] = $activity;
            $activity->setClassroom($this);
        }

        return $this;
    }


    /**
     * To string magic method used by EleveType.
     * @return string|null
     */
    public function __toString()
    {
        return $this->getName();
    }


    /**
     * @return Collection|ActivityThemeDomain[]
     */
    public function getActivityThemeDomains(): Collection
    {
        return $this->activityThemeDomains;
    }


    /**
     * Add an activity theme domains ( useful for special classrooms ).
     * @param ActivityThemeDomain $activityThemeDomain
     * @return $this
     */
    public function addActivityThemeDomain(ActivityThemeDomain $activityThemeDomain): self
    {
        if (!$this->activityThemeDomains->contains($activityThemeDomain)) {
            $this->activityThemeDomains[] = $activityThemeDomain;
            $activityThemeDomain->setClassroom($this);
        }

        return $this;
    }


    /**
     * Remove an activity theme domain.
     * @param ActivityThemeDomain $activityThemeDomain
     * @return $this
     */
    public function removeActivityThemeDomain(ActivityThemeDomain $activityThemeDomain): self
    {
        if ($this->activityThemeDomains->contains($activityThemeDomain)) {
            $this->activityThemeDomains->removeElement($activityThemeDomain);
            // set the owning side to null (unless already changed)
            if ($activityThemeDomain->getClassroom() === $this) {
                $activityThemeDomain->setClassroom(null);
            }
        }

        return $this;
    }
}