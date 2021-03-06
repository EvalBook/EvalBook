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
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $minimum;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $maximum;

    /**
     * @ORM\Column(type="array")
     */
    private $intervals;


    /**
     * @ORM\OneToMany(targetEntity=ActivityThemeDomainSkill::class, mappedBy="noteType")
     */
    private $activityThemeDomainSkills;


    /**
     * NoteType constructor.
     */
    public function __construct()
    {
        $this->activityThemeDomainSkills = new ArrayCollection();
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
     * Return the NoteType description.
     * @return string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }


    /**
     * Set the note type description.
     * @param string $description
     * @return $this
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }


    /**
     * Return the NoteType ponderation.
     * @return string|null
     */
    public function getMinimum(): ?string
    {
        return $this->minimum;
    }


    /**
     * Set the NoteType ponderation.
     * @param string $min
     * @return $this
     */
    public function setMinimum(string $min): self
    {
        $this->minimum = $min;
        return $this;
    }


    /**
     * Return the maximum allowable note.
     * @return string|null
     */
    public function getMaximum(): ?string
    {
        return $this->maximum;
    }


    /**
     * Set the NoteType maximum allowable note.
     * @param string $max
     * @return $this
     */
    public function setMaximum(string $max): self
    {
        $this->maximum = $max;
        return $this;
    }


    /**
     * Return the allowed note intervals.
     * @return array|null
     */
    public function getIntervals(): ?array
    {
        return $this->intervals;
    }


    /**
     * Set the NoteType allowable notes interval.
     * @param array $intervals
     * @return $this
     */
    public function setIntervals(array $intervals): self
    {
        $this->intervals = $intervals;

        return $this;
    }


    /**
     * Return all available activity theme domain skills attached to the note type.
     * @return Collection
     */
    public function getActivityThemeDomainSkills(): Collection
    {
        return $this->activityThemeDomainSkills;
    }


    /**
     * Add an activity theme domain skill to this note type.
     * @param ActivityThemeDomainSkill $activityThemeDomainSkill
     * @return $this
     */
    public function addActivityThemeDomainSkill(ActivityThemeDomainSkill $activityThemeDomainSkill): self
    {
        if (!$this->activityThemeDomainSkills->contains($activityThemeDomainSkill)) {
            $this->activityThemeDomainSkills[] = $activityThemeDomainSkill;
            $activityThemeDomainSkill->setNoteType($this);
        }

        return $this;
    }


    /**
     * Remove an activity theme domain skill from this note type.
     * @param ActivityThemeDomainSkill $activityThemeDomainSkill
     * @return $this
     */
    public function removeActivityThemeDomainSkill(ActivityThemeDomainSkill $activityThemeDomainSkill): self
    {
        if ($this->activityThemeDomainSkills->contains($activityThemeDomainSkill)) {
            $this->activityThemeDomainSkills->removeElement($activityThemeDomainSkill);
            // set the owning side to null (unless already changed)
            if ($activityThemeDomainSkill->getNoteType() === $this) {
                $activityThemeDomainSkill->setNoteType(null);
            }
        }

        return $this;
    }


    /**
     * Return true if ALL values ( min, max + intervals ) are numeric.
     * @return bool
     */
    public function isNumeric()
    {
        $filer = function($interval) {
            return is_numeric($interval);
        };

        if(is_numeric($this->getMaximum()) && is_numeric($this->getMinimum())) {
            $numericIntervals = array_filter($this->getIntervals(), $filer);
            if(count($numericIntervals) === count($this->getIntervals())) {
                return true;
            }
        }
        return false;
    }

    /**
     * Return NoteType string representation.
     * @return string
     */
    public function __toString()
    {
        return $this->getDescription();
    }

}
