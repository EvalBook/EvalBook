<?php

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
    private $idNoteType;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Knowledge", inversedBy="activites")
     */
    private $idKnowledge;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Matiere", inversedBy="activites")
     */
    private $idMatiere;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="activites")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idUser;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Periode", inversedBy="activites")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idPeriode;

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
     * @ORM\ManyToMany(targetEntity="App\Entity\Note", mappedBy="idActivite")
     */
    private $notes;

    public function __construct()
    {
        $this->notes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdNoteType(): ?NoteType
    {
        return $this->idNoteType;
    }

    public function setIdNoteType(?NoteType $idNoteType): self
    {
        $this->idNoteType = $idNoteType;

        return $this;
    }

    public function getIdKnowledge(): ?Knowledge
    {
        return $this->idKnowledge;
    }

    public function setIdKnowledge(?Knowledge $idKnowledge): self
    {
        $this->idKnowledge = $idKnowledge;

        return $this;
    }

    public function getIdMatiere(): ?Matiere
    {
        return $this->idMatiere;
    }

    public function setIdMatiere(?Matiere $idMatiere): self
    {
        $this->idMatiere = $idMatiere;

        return $this;
    }

    public function getIdUser(): ?User
    {
        return $this->idUser;
    }

    public function setIdUser(?User $idUser): self
    {
        $this->idUser = $idUser;

        return $this;
    }

    public function getIdPeriode(): ?Periode
    {
        return $this->idPeriode;
    }

    public function setIdPeriode(?Periode $idPeriode): self
    {
        $this->idPeriode = $idPeriode;

        return $this;
    }

    public function getActiveInPeriod(): ?bool
    {
        return $this->activeInPeriod;
    }

    public function setActiveInPeriod(bool $activeInPeriod): self
    {
        $this->activeInPeriod = $activeInPeriod;

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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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
            $note->addIdActivite($this);
        }

        return $this;
    }

    public function removeNote(Note $note): self
    {
        if ($this->notes->contains($note)) {
            $this->notes->removeElement($note);
            $note->removeIdActivite($this);
        }

        return $this;
    }
}
