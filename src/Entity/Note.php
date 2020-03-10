<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\NoteRepository")
 */
class Note
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Activite", inversedBy="notes")
     */
    private $idActivite;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Eleve", inversedBy="notes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idEleve;

    /**
     * @ORM\Column(type="string", length=45)
     */
    private $note;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment;

    public function __construct()
    {
        $this->idActivite = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Activite[]
     */
    public function getIdActivite(): Collection
    {
        return $this->idActivite;
    }

    public function addIdActivite(Activite $idActivite): self
    {
        if (!$this->idActivite->contains($idActivite)) {
            $this->idActivite[] = $idActivite;
        }

        return $this;
    }

    public function removeIdActivite(Activite $idActivite): self
    {
        if ($this->idActivite->contains($idActivite)) {
            $this->idActivite->removeElement($idActivite);
        }

        return $this;
    }

    public function getIdEleve(): ?Eleve
    {
        return $this->idEleve;
    }

    public function setIdEleve(?Eleve $idEleve): self
    {
        $this->idEleve = $idEleve;

        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(string $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }
}
