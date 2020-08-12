<?php

namespace App\Entity;

use App\Repository\ActivityTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ActivityTypeRepository::class)
 */
class ActivityType
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
     * @ORM\Column(type="smallint")
     */
    private $weight;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isNumericNotes;

    /**
     * @ORM\OneToMany(targetEntity=ActivityTypeChild::class, mappedBy="activityType", orphanRemoval=true)
     */
    private $activityTypeChildren;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $displayName;


    /**
     * ActivityType constructor.
     */
    public function __construct()
    {
        $this->activityTypeChildren = new ArrayCollection();
    }


    /**
     * Return the activity type id
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * Return the activity type name.
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }


    /**
     * Set the activity type name.
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }


    /**
     * Return the activity type school report display weight.
     * @return int|null
     */
    public function getWeight(): ?int
    {
        return $this->weight;
    }


    /**
     * Set the activity type school report display weight.
     * @param int $weight
     * @return $this
     */
    public function setWeight(int $weight): self
    {
        $this->weight = $weight;

        return $this;
    }


    /**
     * Return true if this activity type must use numeric notes types.
     * @return bool|null
     */
    public function getIsNumericNotes(): ?bool
    {
        return $this->isNumericNotes;
    }


    /**
     * @param bool $isNumericNotes
     * @return $this
     */
    public function setIsNumericNotes(bool $isNumericNotes): self
    {
        $this->isNumericNotes = $isNumericNotes;

        return $this;
    }


    /**
     * @return Collection|ActivityTypeChild[]
     */
    public function getActivityTypeChildren(): Collection
    {
        return $this->activityTypeChildren;
    }


    /**
     * Add an activity type child.
     * @param ActivityTypeChild $activityTypeChild
     * @return $this
     */
    public function addActivityTypeChild(ActivityTypeChild $activityTypeChild): self
    {
        if (!$this->activityTypeChildren->contains($activityTypeChild)) {
            $this->activityTypeChildren[] = $activityTypeChild;
            $activityTypeChild->setActivityType($this);
        }

        return $this;
    }


    /**
     * Remove an activity type child.
     * @param ActivityTypeChild $activityTypeChild
     * @return $this
     */
    public function removeActivityTypeChild(ActivityTypeChild $activityTypeChild): self
    {
        if ($this->activityTypeChildren->contains($activityTypeChild)) {
            $this->activityTypeChildren->removeElement($activityTypeChild);
            // set the owning side to null (unless already changed)
            if ($activityTypeChild->getActivityType() === $this) {
                $activityTypeChild->setActivityType(null);
            }
        }

        return $this;
    }


    /**
     * Return the Activity Type display name.
     * @return string|null
     */
    public function getDisplayName(): ?string
    {
        return $this->displayName;
    }


    /**
     * Set the activity type display name.
     * @param string $displayName
     * @return $this
     */
    public function setDisplayName(string $displayName): self
    {
        $this->displayName = $displayName;

        return $this;
    }


    /**
     * String representation of ActivityType.
     */
    public function __toString(): string
    {
        return $this->getDisplayName();
    }

}
