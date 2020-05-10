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
 * @ORM\Entity(repositoryClass="App\Repository\EleveRepository")
 */
class Eleve
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
     * @ORM\ManyToMany(targetEntity="App\Entity\Classe", inversedBy="eleves")
     */
    private $classes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Note", mappedBy="eleve")
     */
    private $notes;


    /**
     * Eleve constructor.
     */
    public function __construct()
    {
        $this->classes = new ArrayCollection();
        $this->notes = new ArrayCollection();
    }


    /**
     * Return the Eleve ID
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * Return the Eleve object last name.
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }


    /**
     * Set the Eleve object last name.
     * @param string $lastName
     * @return $this
     */
    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;
        return $this;
    }


    /**
     * Return the Eleve object first name.
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }


    /**
     * Set the Eleve object first name.
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
     * @param DateTime $birthday
     */
    public function setBirthday(DateTime $birthday) {
        $this->birthday = $birthday;
    }


    /**
     * Return a collection of Classe objects Eleve is registered to.
     * @return Collection|Classe[]
     */
    public function getClasses(): Collection
    {
        return $this->classes;
    }


    /**
     * Register a Classe object to the Eleve.
     * @param Classe $classe
     * @return $this
     */
    public function addClasse(Classe $classe): self
    {
        if (!$this->classes->contains($classe)) {
            $this->classes[] = $classe;
        }

        return $this;
    }


    /**
     * Remlove a Classe object from the Eleve.
     * @param Classe $classe
     * @return $this
     */
    public function removeClasse(Classe $classe): self
    {
        if ($this->classes->contains($classe)) {
            $this->classes->removeElement($classe);
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
     * Add a note to the Eleve object.
     * @param Note $note
     * @return $this
     */
    public function addNote(Note $note): self
    {
        if (!$this->notes->contains($note)) {
            $this->notes[] = $note;
            $note->setEleve($this);
        }

        return $this;
    }


    /**
     * Return true if the student has provided activity.
     *
     * @param Activite $activite
     * @return bool
     */
    public function hasNoteFor(Activite $activite)
    {
        foreach($this->getNotes() as $note) {
            if($note->getActivite() === $activite)
                return true;
        }

        return false;
    }


    /**
     * Remove a note to the Eleve object.
     * @param Note $note
     * @return $this
     */
    public function removeNote(Note $note): self
    {
        if ($this->notes->contains($note)) {
            $this->notes->removeElement($note);
            // set the owning side to null (unless already changed)
            if ($note->getEleve() === $this) {
                $note->setEleve(null);
            }
        }

        return $this;
    }



    public function __toString()
    {
        return $this->getLastName() . " " . $this->getFirstName();
    }
}