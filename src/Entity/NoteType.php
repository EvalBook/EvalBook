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
     * @ORM\OneToMany(targetEntity="App\Entity\Activite", mappedBy="noteType")
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


    /**
     * Return the minimum allowed assignable note.
     */
    public function getMin()
    {
        return strtolower(substr($this->getPonderation(), 0, strpos($this->getPonderation(), '.')));
    }


    /**
     * Retuyrn the maximum allowed assignable note.
     */
    public function getMax()
    {
       return strtolower(substr($this->getPonderation(), 1 + strrpos($this->getPonderation(), '.')));
    }


    /**
     * Return NoteType string representation.
     * @return string
     */
    public function __toString()
    {
        return "Range - " . $this->getName();
    }
}
