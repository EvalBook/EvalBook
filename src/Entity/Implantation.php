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
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ImplantationRepository")
 * @UniqueEntity("name")
 */
class Implantation
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
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $address;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Classroom", mappedBy="implantation", cascade="persist")
     */
    private $classrooms;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Period", mappedBy="implantation")
     */
    private $periods;

    /**
     * @ORM\ManyToOne(targetEntity=School::class, inversedBy="implantations")
     */
    private $school;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $logo;


    /**
     * Implantation constructor.
     */
    public function __construct()
    {
        $this->classrooms = new ArrayCollection();
        $this->periods = new ArrayCollection();
    }


    /**
     * Return the Implantation ID.
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * Return the Implantation name.
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }


    /**
     * Set the Implantation name.
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }


    /**
     * Return the implantation address ( street and number ).
     * @return string|null
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }


    /**
     * Set the Implantation address ( street and number ).
     * @param string $address
     * @return $this
     */
    public function setAddress(string $address): self
    {
        $this->address = $address;
        return $this;
    }


    /**
     * Return a collection of Classroom object owned byt the Implantation object.
     * @return Collection|Classroom[]
     */
    public function getClassrooms(): Collection
    {
        return $this->classrooms;
    }


    /**
     * Return the available list of Period objects for the Implantation.
     * @return Collection|Period[]
     */
    public function getPeriods(): Collection
    {
        return $this->periods;
    }


    /**
     * Add a period to the implantation.
     * @param Period $period
     * @return $this
     */
    public function addPeriod(Period $period): self
    {
        if(!$this->periods->contains($period)) {
            $this->periods[] = $period;
            $period->setImplantation($this);
        }
        return $this;
    }


    /**
     * Return the school the implantation is attached to.
     * @return School|null
     */
    public function getSchool(): ?School
    {
        return $this->school;
    }


    /**
     * Set the school the implantation is attached to.
     * @param School|null $school
     * @return $this
     */
    public function setSchool(?School $school): self
    {
        $this->school = $school;

        return $this;
    }


    /**
     * Return the implantation logo path
     * @return string|null
     */
    public function getLogo(): ?string
    {
        return $this->logo;
    }


    /**
     * Set the implantation logo path
     * @param string|null $logo
     * @return $this
     */
    public function setLogo(?string $logo): self
    {
        $this->logo = $logo;

        return $this;
    }


    /**
     * To string to use with FormBuilder.
     * @return string|null
     */
    public function __toString()
    {
        return $this->getName();
    }
}