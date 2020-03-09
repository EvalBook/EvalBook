<?php

namespace App\Entity;

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
     * @ORM\Column(type="boolean")
     */
    private $active_in_period;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment;

    /**
     * @ORM\Column(type="string", length=250)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\NoteType", inversedBy="activites")
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_note_type_fk;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Knowledge", inversedBy="activites")
     */
    private $id_knowledge_fk;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Matiere", inversedBy="activites")
     */
    private $id_matiere_fk;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Periode", inversedBy="activites")
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_periode_fk;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="activites")
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_user_fk;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getActiveInPeriod(): ?bool
    {
        return $this->active_in_period;
    }

    public function setActiveInPeriod(bool $active_in_period): self
    {
        $this->active_in_period = $active_in_period;

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

    public function getIdNoteTypeFk(): ?NoteType
    {
        return $this->id_note_type_fk;
    }

    public function setIdNoteTypeFk(?NoteType $id_note_type_fk): self
    {
        $this->id_note_type_fk = $id_note_type_fk;

        return $this;
    }

    public function getIdKnowledgeFk(): ?Knowledge
    {
        return $this->id_knowledge_fk;
    }

    public function setIdKnowledgeFk(?Knowledge $id_knowledge_fk): self
    {
        $this->id_knowledge_fk = $id_knowledge_fk;

        return $this;
    }

    public function getIdMatiereFk(): ?Matiere
    {
        return $this->id_matiere_fk;
    }

    public function setIdMatiereFk(?Matiere $id_matiere_fk): self
    {
        $this->id_matiere_fk = $id_matiere_fk;

        return $this;
    }

    public function getIdPeriodeFk(): ?Periode
    {
        return $this->id_periode_fk;
    }

    public function setIdPeriodeFk(?Periode $id_periode_fk): self
    {
        $this->id_periode_fk = $id_periode_fk;

        return $this;
    }

    public function getIdUserFk(): ?User
    {
        return $this->id_user_fk;
    }

    public function setIdUserFk(?User $id_user_fk): self
    {
        $this->id_user_fk = $id_user_fk;

        return $this;
    }
}
