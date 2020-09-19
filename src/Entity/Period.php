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

use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PeriodRepository")
 */
class Period
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=45)
     */
    private $name;

    /**
     * @ORM\Column(type="date")
     */
    private $dateStart;

    /**
     * @ORM\Column(type="date")
     */
    private $dateEnd;

    /**
     * @ORM\OneToMany(targetEntity="Activity", mappedBy="period")
     */
    private $activities;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Implantation", inversedBy="periods")
     * @ORM\JoinColumn(nullable=false)
     */
    private $implantation;


    /**
     * Period constructor.
     */
    public function __construct()
    {
        $this->activities = new ArrayCollection();
    }


    /**
     * Return the Period ID.
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * Return the Period name.
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }


    /**
     * Set the Period name.
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }


    /**
     * Return the Period start date.
     * @return DateTimeInterface|null
     */
    public function getDateStart(): ?DateTimeInterface
    {
        return $this->dateStart;
    }


    /**
     * Set the Period start date.
     * @param DateTimeInterface $dateStart
     * @return $this
     */
    public function setDateStart(DateTimeInterface $dateStart): self
    {
        $this->dateStart = $dateStart;
        return $this;
    }

    /**
     * Return the Period end date.
     * @return DateTimeInterface|null
     */
    public function getDateEnd(): ?DateTimeInterface
    {
        return $this->dateEnd;
    }


    /**
     * Set the Period end date.
     * @param DateTimeInterface $dateEnd
     * @return $this
     */
    public function setDateEnd(DateTimeInterface $dateEnd): self
    {
        $this->dateEnd = $dateEnd;
        return $this;
    }


    /**
     * Return a collection of Activite for the Period.
     * @return Collection|Activity[]
     */
    public function getActivities(): Collection
    {
        return $this->activities;
    }


    /**
     * Return the Period implantation.
     * @return Implantation|null
     */
    public function getImplantation(): ?Implantation
    {
        return $this->implantation;
    }


    /**
     * Set the Period implantation.
     * @param Implantation|null $implantation
     * @return $this
     */
    public function setImplantation(?Implantation $implantation): self
    {
        $this->implantation = $implantation;
        return $this;
    }


    /**
     * Return the Periode string representation.
     * @return string
     */
    public function __toString()
    {
        return $this->getName() . " (" . $this->getDateStart()->format('d/m/Y') . " - " . $this->getDateEnd()->format('d/m/Y') . ") ";
    }
}