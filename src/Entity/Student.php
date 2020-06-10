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

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StudentRepository")
 */
class Student
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $firstName;

    /**
     * @ORM\Column(type="date")
     */
    private $birthday;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Classroom", inversedBy="students")
     */
    private $classrooms;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Note", mappedBy="student")
     */
    private $notes;


    /**
     * @ORM\OneToMany(targetEntity=StudentContactRelation::class, mappedBy="student", orphanRemoval=true)
     */
    private $studentContactRelations;


    /**
     * Eleve constructor.
     */
    public function __construct()
    {
        $this->classrooms = new ArrayCollection();
        $this->notes = new ArrayCollection();
        $this->studentContactRelations = new ArrayCollection();
    }


    /**
     * Return the Student ID
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * Return the Student object last name.
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }


    /**
     * Set the Student object last name.
     * @param string $lastName
     * @return $this
     */
    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;
        return $this;
    }


    /**
     * Return the Student object first name.
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }


    /**
     * Set the Student object first name.
     * @param string $firstName
     * @return $this
     */
    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;
        return $this;
    }


    /**
     * Return the user birthdate.
     */
    public function getBirthday() {
        return $this->birthday;
    }


    /**
     * Set the student birthday.
     * @param DateTime $birthday
     */
    public function setBirthday(DateTime $birthday) {
        $this->birthday = $birthday;
    }


    /**
     * Return a collection of Classrooms objects Student is registered to.
     * @return Collection|Classroom[]
     */
    public function getClassrooms(): Collection
    {
        return $this->classrooms;
    }


    /**
     * Register a Classroom object to the Student.
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
     * Remlove a Classroom object from the Student.
     * @param Classroom $classroom
     * @return $this
     */
    public function removeClassroom(Classroom $classroom): self
    {
        if ($this->classrooms->contains($classroom)) {
            $this->classrooms->removeElement($classroom);
        }

        return $this;
    }


    /**
     * Return a collection of notes owned by Eleve object.
     * @return Collection|Note[]
     */
    public function getNotes(): Collection
    {
        return $this->notes;
    }


    /**
     * @param Activity $activity
     * @return string|null
     */
    public function getNote(Activity $activity)
    {
        foreach($this->getNotes() as $note) {
            if($note->getActivity() === $activity) {
                return $note->getNote();
            }
        }

        return null;
    }


    /**
     * Add a note to the Student object.
     * @param Note $note
     * @return $this
     */
    public function addNote(Note $note): self
    {
        if (!$this->notes->contains($note)) {
            $this->notes[] = $note;
            $note->setStudent($this);
        }

        return $this;
    }


    /**
     * Return true if the student has provided activity.
     *
     * @param Activity $activity
     * @return bool
     */
    public function hasNoteFor(Activity $activity)
    {
        foreach($this->getNotes() as $note) {
            if($note->getActivity() === $activity)
                return true;
        }

        return false;
    }


    /**
     * Remove a note to the Student object.
     * @param Note $note
     * @return $this
     */
    public function removeNote(Note $note): self
    {
        if ($this->notes->contains($note)) {
            $this->notes->removeElement($note);
            // set the owning side to null (unless already changed)
            if ($note->getStudent() === $this) {
                $note->setStudent(null);
            }
        }

        return $this;
    }


    /**
     * Return the student relations with available StudentContact.
     * @return Collection|StudentContactRelation[]
     */
    public function getStudentContactRelations(): Collection
    {
        return $this->studentContactRelations;
    }


    /**
     * Add a new relation with StudentContact through StudentContactRelation.
     * @param StudentContactRelation $studentContactRelation
     * @return $this
     */
    public function addStudentContactRelation(StudentContactRelation $studentContactRelation): self
    {
        if (!$this->studentContactRelations->contains($studentContactRelation)) {
            $this->studentContactRelations[] = $studentContactRelation;
            $studentContactRelation->setStudent($this);
        }

        return $this;
    }


    /**
     * Remove a student contact relation.
     * @param StudentContactRelation $studentContactRelation
     * @return $this
     */
    public function removeStudentContactRelation(StudentContactRelation $studentContactRelation): self
    {
        if ($this->studentContactRelations->contains($studentContactRelation)) {
            $this->studentContactRelations->removeElement($studentContactRelation);
            // set the owning side to null (unless already changed)
            if ($studentContactRelation->getStudent() === $this) {
                $studentContactRelation->setStudent(null);
            }
        }

        return $this;
    }


    public function getMedicalContacts()
    {

    }


    public function getNonMedicalContacts()
    {

    }


    /**
     * Return the Eleve string representation.
     * @return string
     */
    public function __toString()
    {
        return $this->getLastName() . " " . $this->getFirstName();
    }
}