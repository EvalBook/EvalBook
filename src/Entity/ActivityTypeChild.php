<?php

namespace App\Entity;

use App\Repository\ActivityTypeChildRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ActivityTypeChildRepository::class)
 */
class ActivityTypeChild
{
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
     * @ORM\ManyToOne(targetEntity=ActivityType::class, inversedBy="activityTypeChildren")
     * @ORM\JoinColumn(nullable=false)
     */
    private $activityType;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $displayName;

    /**
     * @ORM\ManyToOne(targetEntity=Classroom::class, inversedBy="activityTypeChildren")
     */
    private $classroom;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $type;

    /**
     * Return the activity type chidren id.
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * Return the activity type child name.
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }


    /**
     * Set the activity type child name.
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }


    /**
     * Return related activity type.
     * @return ActivityType|null
     */
    public function getActivityType(): ?ActivityType
    {
        return $this->activityType;
    }


    /**
     * Set related activity type.
     * @param ActivityType|null $activityType
     * @return $this
     */
    public function setActivityType(?ActivityType $activityType): self
    {
        $this->activityType = $activityType;

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
     * Set the activity type child display name.
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
     * String representation of this object.
     * @return string
     */
    public function __toString(): string
    {
        return $this->getDisplayName();
    }


    /**
     * Return the activity type child type ( generic / special_classroom )
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }


    /**
     * Set the activity type child type ( generic / special_classroom )
     * @param string $type
     * @return $this
     */
    public function setType(string $type): self
    {
        // Ensure type is allowed before applying.
        if(in_array($type, [self::TYPE_GENERIC, self::TYPE_SPECIAL_CLASSROOM])) {
            $this->type = $type;
        }

        return $this;
    }
}
