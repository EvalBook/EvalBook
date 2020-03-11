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

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ActiviteLevel", inversedBy="activites")
     */
    private $idLevel;


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
        return $this->idNoteType;
    }


    /**
     * Set the activity NoteType
     * @param NoteType|null $noteType
     * @return $this
     */
    public function setNoteType(?NoteType $noteType): self
    {
        $this->idNoteType = $noteType;
        return $this;
    }


    /**
     * Return the knowledge associated to the activity OR null if no matiere assigned.
     * @return Knowledge|null
     */
    public function getKnowledge(): ?Knowledge
    {
        return $this->idKnowledge;
    }


    /**
     * Set the knowledge if applicable ( knowledge or matiere ).
     * @param Knowledge|null $knowledge
     * @return $this
     */
    public function setKnowledge(?Knowledge $knowledge): self
    {
        $this->idKnowledge = $knowledge;
        return $this;
    }


    /**
     * Return the matiere if applicable OR null if knowledge assigned.
     * @return Matiere|null
     */
    public function getMatiere(): ?Matiere
    {
        return $this->idMatiere;
    }


    /**
     * Set the matiere if applicable ( knowledge or matiere ).
     * @param Matiere|null $matiere
     * @return $this
     */
    public function setMatiere(?Matiere $matiere): self
    {
        $this->idMatiere = $matiere;
        return $this;
    }


    /**
     * Return the User who created the activity.
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->idUser;
    }


    /**
     * Set the user who created the activity.
     * @param User|null $user
     * @return $this
     */
    public function setUser(?User $user): self
    {
        $this->idUser = $user;
        return $this;
    }


    /**
     * Return the Period attached to the activity.
     * @return Periode|null
     */
    public function getPeriode(): ?Periode
    {
        return $this->idPeriode;
    }


    /**
     * Set the period attache to the activity.
     * @param Periode|null $periode
     * @return $this
     */
    public function setPeriode(?Periode $periode): self
    {
        $this->idPeriode = $periode;
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
     * Return the Note collection attributed to User
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
            $note->addIdActivite($this);
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
            $note->removeIdActivite($this);
        }

        return $this;
    }


    /**
     * Return the current activity Level.
     * @return ActiviteLevel|null
     */
    public function getLevel(): ?ActiviteLevel
    {
        return $this->idLevel;
    }


    /**
     * Set the current activity level.
     * @param ActiviteLevel|null $idLevel
     * @return $this
     */
    public function setLevel(?ActiviteLevel $idLevel): self
    {
        $this->idLevel = $idLevel;
        return $this;
    }
}