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
 * @ORM\Entity(repositoryClass="App\Repository\ActiviteRepository")
 */
class Activite
{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\NoteType", inversedBy="activites")
     * @ORM\JoinColumn(nullable=false)
     */
    private $noteType;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="activites")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Periode", inversedBy="activites")
     * @ORM\JoinColumn(nullable=false)
     */
    private $periode;

    /**
     * @ORM\Column(type="boolean")
     */
    private $activeInPeriod;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Note", mappedBy="activite")
     */
    private $notes;


    /**
     * Activite constructor.
     */
    public function __construct()
    {
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
     * @return Periode|null
     */
    public function getPeriode(): ?Periode
    {
        return $this->periode;
    }


    /**
     * Set the period attache to the activity.
     * @param Periode|null $periode
     * @return $this
     */
    public function setPeriode(?Periode $periode): self
    {
        $this->periode = $periode;
        return $this;
    }


    /**
     * Return true if this actifity MUST be included in period calculations, false otherwise.
     * @return bool|null
     */
    public function getActiveInPeriod(): ?bool
    {
        return $this->activeInPeriod;
    }


    /**
     * Set to true in order to use activity score in period, false otherwise.
     * @param bool $activeInPeriod
     * @return $this
     */
    public function setActiveInPeriod(bool $activeInPeriod): self
    {
        $this->activeInPeriod = $activeInPeriod;
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
     * Return the Note collection attributed to Activite
     * @return Collection|Note[]
     */
    public function getNotes(): Collection
    {
        return $this->notes;
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
            $note->addActivite($this);
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
            $note->removeActivite($this);
        }

        return $this;
    }
}