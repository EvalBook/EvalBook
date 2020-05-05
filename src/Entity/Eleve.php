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
     * @ORM\ManyToMany(targetEntity="App\Entity\Classe", inversedBy="eleves")
     */
    private $classes;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Contact", inversedBy="eleves")
     */
    private $contacts;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Note", mappedBy="eleve")
     */
    private $notes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\EleveComment", mappedBy="eleve")
     */
    private $eleveComments;


    /**
     * Eleve constructor.
     */
    public function __construct()
    {
        $this->classes = new ArrayCollection();
        $this->contacts = new ArrayCollection();
        $this->notes = new ArrayCollection();
        $this->eleveComments = new ArrayCollection();
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
     * Return true if Eleve is active, false otherwise.
     * @return bool|null
     */
    public function getActive(): ?bool
    {
        return $this->active;
    }


    /**
     * Set the Eleve active
     * @param bool $active
     * @return $this
     */
    public function setActive(bool $active): self
    {
        $this->active = $active;
        return $this;
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
    public function addClass(Classe $classe): self
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
    public function removeClass(Classe $classe): self
    {
        if ($this->classes->contains($classe)) {
            $this->classes->removeElement($classe);
        }

        return $this;
    }


    /**
     * Return a collection of Contact object of Eleve.
     * @return Collection|Contact[]
     */
    public function getContacts(): Collection
    {
        return $this->contacts;
    }


    /**
     * Add a contact to the Eleve object.
     * @param Contact $contact
     * @return $this
     */
    public function addContact(Contact $contact): self
    {
        if (!$this->contacts->contains($contact)) {
            $this->contacts[] = $contact;
        }

        return $this;
    }


    /**
     * Remove a contact of Eleve objcet.
     * @param Contact $contact
     * @return $this
     */
    public function removeContact(Contact $contact): self
    {
        if ($this->contacts->contains($contact)) {
            $this->contacts->removeElement($contact);
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


    /**
     * Return a collection of EleveComment Eleve has.
     * @return Collection|EleveComment[]
     */
    public function getEleveComments(): Collection
    {
        return $this->eleveComments;
    }


    /**
     * Add e EleveComment for the Eleve.
     * @param EleveComment $eleveComment
     * @return $this
     */
    public function addEleveComment(EleveComment $eleveComment): self
    {
        if (!$this->eleveComments->contains($eleveComment)) {
            $this->eleveComments[] = $eleveComment;
            $eleveComment->setEleve($this);
        }

        return $this;
    }


    /**
     * Remove a EleveComment for the Eleve.
     * @param EleveComment $eleveComment
     * @return $this
     */
    public function removeEleveComment(EleveComment $eleveComment): self
    {
        if ($this->eleveComments->contains($eleveComment)) {
            $this->eleveComments->removeElement($eleveComment);
            // set the owning side to null (unless already changed)
            if ($eleveComment->getEleve() === $this) {
                $eleveComment->setEleve(null);
            }
        }

        return $this;
    }
}