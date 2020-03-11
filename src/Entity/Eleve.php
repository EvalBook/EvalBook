<?php

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
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Classe", inversedBy="eleves")
     */
    private $idClasses;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Contact", inversedBy="eleves")
     */
    private $idContact;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Note", mappedBy="idEleve")
     */
    private $notes;


    /**
     * Eleve constructor.
     */
    public function __construct()
    {
        $this->idClasses = new ArrayCollection();
        $this->idContact = new ArrayCollection();
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
        return $this->idClasses;
    }


    /**
     * Register a Classe object to the Eleve.
     * @param Classe $classe
     * @return $this
     */
    public function addClass(Classe $classe): self
    {
        if (!$this->idClasses->contains($classe)) {
            $this->idClasses[] = $classe;
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
        if ($this->idClasses->contains($classe)) {
            $this->idClasses->removeElement($classe);
        }

        return $this;
    }


    /**
     * Return a collection of Contact object of Eleve.
     * @return Collection|Contact[]
     */
    public function getContact(): Collection
    {
        return $this->idContact;
    }


    /**
     * Add a contact to the Eleve object.
     * @param Contact $contact
     * @return $this
     */
    public function addContact(Contact $contact): self
    {
        if (!$this->idContact->contains($contact)) {
            $this->idContact[] = $contact;
        }

        return $this;
    }


    /**
     * Remove a contact of Eleve objcet.
     * @param Contact $contact
     * @return $this
     */
    public function removeIdContact(Contact $contact): self
    {
        if ($this->idContact->contains($contact)) {
            $this->idContact->removeElement($contact);
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
            $note->setIdEleve($this);
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
            if ($note->getIdEleve() === $this) {
                $note->setIdEleve(null);
            }
        }

        return $this;
    }
}