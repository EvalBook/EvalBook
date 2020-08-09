<?php

namespace App\Entity;

use App\Repository\KnowledgeTypeRepository;
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
     * @ORM\ManyToOne(targetEntity=ActivityType::class, inversedBy="knowledgeTypes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $activityType;

    /**
     * @ORM\ManyToOne(targetEntity=NoteType::class, inversedBy="knowledgeTypes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $noteType;


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
     * Return the parent acticity typoe attached to this knowledge.
     * @return ActivityType|null
     */
    public function getActivityType(): ?ActivityType
    {
        return $this->activityType;
    }


    /**
     * Set the activity type attached to this knowledge.
     * @param ActivityType|null $activityType
     * @return $this
     */
    public function setActivityType(?ActivityType $activityType): self
    {
        $this->activityType = $activityType;

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
}
