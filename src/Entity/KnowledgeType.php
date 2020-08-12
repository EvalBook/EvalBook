<?php

namespace App\Entity;

use App\Repository\KnowledgeTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=KnowledgeTypeRepository::class)
 */
class KnowledgeType
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
     * @ORM\ManyToOne(targetEntity=NoteType::class, inversedBy="knowledgeTypes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $noteType;

    /**
     * @ORM\ManyToOne(targetEntity=ActivityTypeChild::class, inversedBy="knowledgeTypes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $activityTypeChild;

    /**
     * @ORM\OneToMany(targetEntity=Activity::class, mappedBy="knowledgeType", orphanRemoval=true)
     */
    private $activities;

    public function __construct()
    {
        $this->activities = new ArrayCollection();
    }


    /**
     * Return the knowledge id.
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * Return the knowledge name.
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }


    /**
     * Set the knowledge name.
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }


    /**
     * Return the knowledge description.
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }


    /**
     * Set the knowledge description.
     * @param string|null $description
     * @return $this
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }


    /**
     * Return the note type attached to this knowledge.
     * @return NoteType|null
     */
    public function getNoteType(): ?NoteType
    {
        return $this->noteType;
    }


    /**
     * Set the not type attached to this knowledge.
     * @param NoteType|null $noteType
     * @return $this
     */
    public function setNoteType(?NoteType $noteType): self
    {
        $this->noteType = $noteType;

        return $this;
    }


    /**
     * Return the parent Activity type child.
     * @return ActivityTypeChild|null
     */
    public function getActivityTypeChild(): ?ActivityTypeChild
    {
        return $this->activityTypeChild;
    }


    /**
     * Set the parent activity type child.
     * @param ActivityTypeChild|null $activityTypeChild
     * @return $this
     */
    public function setActivityTypeChild(?ActivityTypeChild $activityTypeChild): self
    {
        $this->activityTypeChild = $activityTypeChild;

        return $this;
    }

    /**
     * @return Collection|Activity[]
     */
    public function getActivities(): Collection
    {
        return $this->activities;
    }

    public function addActivity(Activity $activity): self
    {
        if (!$this->activities->contains($activity)) {
            $this->activities[] = $activity;
            $activity->setKnowledgeType($this);
        }

        return $this;
    }

    public function removeActivity(Activity $activity): self
    {
        if ($this->activities->contains($activity)) {
            $this->activities->removeElement($activity);
            // set the owning side to null (unless already changed)
            if ($activity->getKnowledgeType() === $this) {
                $activity->setKnowledgeType(null);
            }
        }

        return $this;
    }
}
