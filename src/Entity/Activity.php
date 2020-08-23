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
 * @ORM\Entity(repositoryClass="App\Repository\ActivityRepository")
 */
class Activity
{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $dateAdded;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\NoteType", inversedBy="activities")
     * @ORM\JoinColumn(nullable=false)
     */
    private $noteType;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="activities")
     * @ORM\JoinColumn(nullable=true)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Period", inversedBy="activities")
     * @ORM\JoinColumn(nullable=true)
     */
    private $period;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Note", mappedBy="activity", cascade={"persist"})
     */
    private $notes;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Classroom", inversedBy="activities")
     * @ORM\JoinColumn(nullable=true)
     */
    private $classroom;

    /**
     * @ORM\ManyToOne(targetEntity=ActivityThemeDomainSkill::class, inversedBy="activities")
     * @ORM\JoinColumn(nullable=false)
     */
    private $activityThemeDomainSkill;


    /**
     * Activite constructor.
     */
    public function __construct()
    {
        $this->dateAdded = new DateTime();
        $this->notes = new ArrayCollection();
    }


    /**
     * Return the Activity Id.
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * Return the activity NoteType.
     * @return NoteType|null
     */
    public function getNoteType(): ?NoteType
    {
        return $this->noteType;
    }


    /**
     * Set the activity NoteType
     * @param NoteType|null $noteType
     * @return $this
     */
    public function setNoteType(?NoteType $noteType): self
    {
        $this->noteType = $noteType;
        return $this;
    }



    /**
     * Return the User who created the activity.
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }


    /**
     * Set the user who created the activity.
     * @param User|null $user
     * @return $this
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }


    /**
     * Return the Period attached to the activity.
     * @return Period|null
     */
    public function getPeriod(): ?Period
    {
        return $this->period;
    }


    /**
     * Set the period attache to the activity.
     * @param Period|null $period
     * @return $this
     */
    public function setPeriod(?Period $period): self
    {
        $this->period = $period;
        return $this;
    }


    /**
     * Return the activity global comment or description.
     * @return string|null
     */
    public function getComment(): ?string
    {
        return $this->comment;
    }


    /**
     * Set the activity global comment or description.
     * @param string|null $comment
     * @return $this
     */
    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }


    /**
     * Return the activity name / title.
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }


    /**
     * Set the activity name / title.
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Return the activity date added.
     * @return DateTime
     */
    public function getDateAdded()
    {
        return $this->dateAdded;
    }


    /**
     * Return the Note collection attributed to Activite
     * @return Collection|Note[]
     */
    public function getNotes(): Collection
    {
        return $this->notes;
    }


    /**
     * Return the activity classroom.
     */
    public function getClassroom()
    {
        return $this->classroom;
    }


    /**
     * Set the activity classroom.
     * @param Classroom $classroom
     */
    public function setClassroom(?Classroom $classroom)
    {
        if(!is_null($classroom))
            $this->classroom = $classroom;
    }


    /**
     * Add a note to the activity.
     * @param Note $note
     * @return $this
     */
    public function addNote(Note $note): self
    {
        if (!$this->notes->contains($note)) {
            $this->notes[] = $note;
            $note->setActivity($this);
        }

        return $this;
    }


    /**
     * Remove a note from the activity.
     * @param Note $note
     * @return $this
     */
    public function removeNote(Note $note): self
    {
        if ($this->notes->contains($note)) {
            $this->notes->removeElement($note);
            $note->setActivity(null);
        }

        return $this;
    }


    /**
     * Detach notes preserving all students notes.
     * @return $this
     */
    public function detachNotes(): self {
        foreach($this->getNotes() as $note) {
            $note->setActivity(null);
        }
        $this->notes = [];

        return $this;
    }


    /**
     * Detach Classroom.
     * @return $this
     */
    public function detachClassroom(): self {
        $this->classroom = null;
        return $this;
    }


    /**
     * Return the attached activity theme domain skill.
     */
    public function getActivityThemeDomainSkill(): ?ActivityThemeDomainSkill
    {
        return $this->activityThemeDomainSkill;
    }


    /**
     * Set the attached activity theme domain skill.
     * @param ActivityThemeDomainSkill|null $activityThemeDomainSkill
     * @return Activity
     */
    public function setActivityThemeDomainSkill(?ActivityThemeDomainSkill $activityThemeDomainSkill): self
    {
        $this->activityThemeDomainSkill = $activityThemeDomainSkill;

        return $this;
    }


    /**
     * Return the Activity string representation.
     * @return string
     */
    public function __toString()
    {
        return $this->getPeriod() . " - " . $this->getName();
    }


    /**
     * Clone current object.
     */
    public function __clone()
    {
        $this->id = null;
        $this->setName($this->getName() . " ( clone )");
        $this->dateAdded = new DateTime();
        if(!is_null($this->getComment())) {
            $this->setComment($this->getComment() . " ( clone )");
        }
    }
}