<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\NoteTypeRepository")
 */
class NoteType
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ponderation;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $coefficient;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Activite", mappedBy="idNoteType")
     */
    private $activites;


    /**
     * NoteType constructor.
     */
    public function __construct()
    {
        $this->activites = new ArrayCollection();
    }


    /**
     * Return the NoteType ID.
     **/
    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * Return the NoteType name.
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }


    /**
     * Set the NoteType name.
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }


    /**
     * Return the NoteType ponderation.
     * @return string|null
     */
    public function getPonderation(): ?string
    {
        return $this->ponderation;
    }


    /**
     * Set the NoteType ponderation.
     * @param string $ponderation
     * @return $this
     */
    public function setPonderation(string $ponderation): self
    {
        $this->ponderation = $ponderation;
        return $this;
    }


    /**
     * Return the NoteType coefficient.
     * @return int|null
     */
    public function getCoefficient(): ?int
    {
        return $this->coefficient;
    }


    /**
     * Set the NoteType coefficient.
     * @param int|null $coefficient
     * @return $this
     */
    public function setCoefficient(?int $coefficient): self
    {
        $this->coefficient = $coefficient;
        return $this;
    }


    /**
     * Return a collection of activities based on the NoteType.
     * @return Collection|Activite[]
     */
    public function getActivites(): Collection
    {
        return $this->activites;
    }


    /**
     * Add an Activite based on the NoteType.
     * @param Activite $activite
     * @return $this
     */
    public function addActivite(Activite $activite): self
    {
        if (!$this->activites->contains($activite)) {
            $this->activites[] = $activite;
            $activite->setNoteType($this);
        }

        return $this;
    }


    /**
     * Removes an Activite based on the NoteType.
     * @param Activite $activite
     * @return $this
     */
    public function removeActivite(Activite $activite): self
    {
        if ($this->activites->contains($activite)) {
            $this->activites->removeElement($activite);
            // set the owning side to null (unless already changed)
            if ($activite->getNoteType() === $this) {
                $activite->setNoteType(null);
            }
        }

        return $this;
    }
}
