<?php

namespace App\Entity;

use App\Repository\ActivityThemeDomainRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ActivityThemeDomainRepository::class)
 */
class ActivityThemeDomain
{
    const TYPE_GENERIC_DEFAULT = 'generic_default';
    const TYPE_GENERIC = 'generic';
    const TYPE_SPECIAL_CLASSROOM = 'special_classroom';

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
     * @ORM\ManyToOne(targetEntity=ActivityTheme::class, inversedBy="activityThemeDomains")
     * @ORM\JoinColumn(nullable=false)
     */
    private $activityTheme;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $displayName;

    /**
     * @ORM\ManyToOne(targetEntity=Classroom::class, inversedBy="activityThemeDomains")
     */
    private $classroom;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $type;

    /**
     * @ORM\OneToMany(targetEntity=ActivityThemeDomainSkill::class, mappedBy="activityThemeDomain", orphanRemoval=true)
     */
    private $activityThemeDomainSkills;


    /**
     * ActivityThemeDomain constructor.
     */
    public function __construct()
    {
        $this->activityThemeDomainSkills = new ArrayCollection();
    }


    /**
     * Return the activity theme domain id.
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * Return the activity theme domain name.
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }


    /**
     * Set the activity theme domain name.
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }


    /**
     * Return related activity theme.
     * @return ActivityTheme|null
     */
    public function getActivityTheme(): ?ActivityTheme
    {
        return $this->activityTheme;
    }


    /**
     * Set related activity theme.
     * @param ActivityTheme|null $activityTheme
     * @return $this
     */
    public function setActivityTheme(?ActivityTheme $activityTheme): self
    {
        $this->activityTheme = $activityTheme;

        return $this;
    }


    /**
     * Return display name ( displayed on school report ).
     * @return string|null
     */
    public function getDisplayName(): ?string
    {
        return $this->displayName;
    }


    /**
     * Set the activity theme domain display name.
     * @param string $displayName
     * @return $this
     */
    public function setDisplayName(string $displayName): self
    {
        $this->displayName = $displayName;

        return $this;
    }

    /**
     * Return attached classroom or null for a generic one.
     * @return Classroom|null
     */
    public function getClassroom(): ?Classroom
    {
        return $this->classroom;
    }


    /**
     * Set the attached classroom.
     * @param Classroom|null $classroom
     * @return $this
     */
    public function setClassroom(?Classroom $classroom): self
    {
        $this->classroom = $classroom;

        return $this;
    }


    /**
     * Return the activity theme domain type ( generic / special_classroom )
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }


    /**
     * Set the activity theme domain type ( generic / special_classroom )
     * @param string $type
     * @return $this
     */
    public function setType(string $type): self
    {
        // Ensure type is allowed before applying.
        if(in_array($type, [self::TYPE_GENERIC_DEFAULT, self::TYPE_GENERIC, self::TYPE_SPECIAL_CLASSROOM])) {
            $this->type = $type;
        }

        return $this;
    }

    /**
     * @return Collection|ActivityThemeDomainSkill[]
     */
    public function getActivityThemeDomainSkills(): Collection
    {
        return $this->activityThemeDomainSkills;
    }


    /**
     * Add an activity theme domain skill to this activity theme domain.
     * @param ActivityThemeDomainSkill $activityThemeDomainSkill
     * @return $this
     */
    public function addActivityThemeDomainSkill(ActivityThemeDomainSkill $activityThemeDomainSkill): self
    {
        if (!$this->activityThemeDomainSkills->contains($activityThemeDomainSkill)) {
            $this->activityThemeDomainSkills[] = $activityThemeDomainSkill;
            $activityThemeDomainSkill->setActivityThemeDomain($this);
        }

        return $this;
    }


    /**
     * Remove an activity theme domain skill to this activity theme domain.
     * @param ActivityThemeDomainSkill $activityThemeDomainSkill
     * @return $this
     */
    public function removeActivityThemeDomainSkill(ActivityThemeDomainSkill $activityThemeDomainSkill): self
    {
        if ($this->activityThemeDomainSkills->contains($activityThemeDomainSkill)) {
            $this->activityThemeDomainSkills->removeElement($activityThemeDomainSkill);
            // set the owning side to null (unless already changed)
            if ($activityThemeDomainSkill->getActivityThemeDomain() === $this) {
                $activityThemeDomainSkill->setActivityThemeDomain(null);
            }
        }

        return $this;
    }


    /**
     * String representation of this object.
     * @return string
     */
    public function __toString(): string
    {
        return $this->getDisplayName();
    }
}
