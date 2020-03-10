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

    public function __construct()
    {
        $this->idClasses = new ArrayCollection();
        $this->idContact = new ArrayCollection();
        $this->notes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    /**
     * @return Collection|Classe[]
     */
    public function getIdClasses(): Collection
    {
        return $this->idClasses;
    }

    public function addIdClass(Classe $idClass): self
    {
        if (!$this->idClasses->contains($idClass)) {
            $this->idClasses[] = $idClass;
        }

        return $this;
    }

    public function removeIdClass(Classe $idClass): self
    {
        if ($this->idClasses->contains($idClass)) {
            $this->idClasses->removeElement($idClass);
        }

        return $this;
    }

    /**
     * @return Collection|Contact[]
     */
    public function getIdContact(): Collection
    {
        return $this->idContact;
    }

    public function addIdContact(Contact $idContact): self
    {
        if (!$this->idContact->contains($idContact)) {
            $this->idContact[] = $idContact;
        }

        return $this;
    }

    public function removeIdContact(Contact $idContact): self
    {
        if ($this->idContact->contains($idContact)) {
            $this->idContact->removeElement($idContact);
        }

        return $this;
    }

    /**
     * @return Collection|Note[]
     */
    public function getNotes(): Collection
    {
        return $this->notes;
    }

    public function addNote(Note $note): self
    {
        if (!$this->notes->contains($note)) {
            $this->notes[] = $note;
            $note->setIdEleve($this);
        }

        return $this;
    }

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
