<?php

namespace App\Entity;

use App\Repository\ActivityThemeDomainSkillRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ActivityThemeDomainSkillRepository::class)
 */
class ActivityThemeDomainSkill
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=NoteType::class, inversedBy="activityThemeDomainSkills")
     * @ORM\JoinColumn(nullable=false)
     */
    private $noteType;

    /**
     * @ORM\ManyToOne(targetEntity=ActivityThemeDomain::class, inversedBy="activityThemeDomainSkills")
     * @ORM\JoinColumn(nullable=false)
     */
    private $activityThemeDomain;

    /**
     * @ORM\OneToMany(targetEntity=Activity::class, mappedBy="activityThemeDomainSkill", orphanRemoval=true)
     */
    private $activities;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=true)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Classroom::class, inversedBy="activityThemeDomainSkills")
     * @ORM\JoinColumn(nullable=false)
     */
    private $classroom;


    /**
     * ActivityThemeDomainSkill constructor.
     */
    public function __construct()
    {
        $this->activities = new ArrayCollection();
    }


    /**
     * Return the skill id.
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * Return the skill name.
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }


    /**
     * Set the skill name.
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }


    /**
     * Return the skill description.
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }


    /**
     * Set the skill description.
     * @param string|null $description
     * @return $this
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }


    /**
     * Return the note type attached to this skill.
     * @return NoteType|null
     */
    public function getNoteType(): ?NoteType
    {
        return $this->noteType;
    }


    /**
     * Set the not type attached to this skill.
     * @param NoteType|null $noteType
     * @return $this
     */
    public function setNoteType(?NoteType $noteType): self
    {
        $this->noteType = $noteType;

        return $this;
    }


    /**
     * Return the parent Activity theme domain.
     * @return ActivityThemeDomain|null
     */
    public function getActivityThemeDomain(): ?ActivityThemeDomain
    {
        return $this->activityThemeDomain;
    }


    /**
     * Set the parent activity theme domain.
     * @param ActivityThemeDomain|null $activityThemeDomain
     * @return $this
     */
    public function setActivityThemeDomain(?ActivityThemeDomain $activityThemeDomain): self
    {
        $this->activityThemeDomain = $activityThemeDomain;

        return $this;
    }


    /**
     * @return Collection|Activity[]
     */
    public function getActivities(): Collection
    {
        return $this->activities;
    }


    /**
     * String representation of this object.
     * @return string|null
     */
    public function __toString()
    {
        return $this->getName();
    }


    /**
     * Return the skill owner.
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }


    /**
     * Return the skill owner.
     * @param User|null $user
     * @return $this
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }


    /**
     * Return classroom attached to the skill.
     * @return Classroom|null
     */
    public function getClassroom(): ?Classroom
    {
        return $this->classroom;
    }


    /**
     * Set the classroom attached to the skill.
     * @param Classroom|null $classroom
     * @return $this
     */
    public function setClassroom(?Classroom $classroom): self
    {
        $this->classroom = $classroom;

        return $this;
    }
}
