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

use App\Repository\SchoolRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SchoolRepository::class)
 */
class School
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
     * @ORM\OneToMany(targetEntity=Implantation::class, mappedBy="school")
     */
    private $implantations;

    public function __construct()
    {
        $this->implantations = new ArrayCollection();
    }

    /**
     * Return the school id.
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * Return the school name.
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }


    /**
     * Set the school name.
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Implantation[]
     */
    public function getImplantations(): Collection
    {
        return $this->implantations;
    }


    /**
     * Attach an implantation to the school.
     * @param Implantation $implantation
     * @return $this
     */
    public function addImplantation(Implantation $implantation): self
    {
        if (!$this->implantations->contains($implantation)) {
            $this->implantations[] = $implantation;
            $implantation->setSchool($this);
        }

        return $this;
    }


    /**
     * Detach an implantation from the school.
     * @param Implantation $implantation
     * @return $this
     */
    public function removeImplantation(Implantation $implantation): self
    {
        if ($this->implantations->contains($implantation)) {
            $this->implantations->removeElement($implantation);
            // set the owning side to null (unless already changed)
            if ($implantation->getSchool() === $this) {
                $implantation->setSchool(null);
            }
        }

        return $this;
    }


    /**
     * Object to string conversion.
     * @return string|null
     */
    public function __toString()
    {
        return $this->getName();
    }
}
