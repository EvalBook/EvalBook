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
     * @ORM\OneToMany(targetEntity=KnowledgeType::class, mappedBy="activityType", orphanRemoval=true)
     */
    private $knowledgeTypes;


    /**
     * ActivityType constructor.
     */
    public function __construct()
    {
        $this->knowledgeTypes = new ArrayCollection();
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
     * Return all available knowledge types.
     * @return Collection|KnowledgeType[]
     */
    public function getKnowledgeTypes(): Collection
    {
        return $this->knowledgeTypes;
    }


    /**
     * Add a new knowledge type to this activity type.
     * @param KnowledgeType $knowledgeType
     * @return $this
     */
    public function addKnowledgeType(KnowledgeType $knowledgeType): self
    {
        if (!$this->knowledgeTypes->contains($knowledgeType)) {
            $this->knowledgeTypes[] = $knowledgeType;
            $knowledgeType->setActivityType($this);
        }

        return $this;
    }


    /**
     * Remove a knowledge type from this activity type.
     * @param KnowledgeType $knowledgeType
     * @return $this
     */
    public function removeKnowledgeType(KnowledgeType $knowledgeType): self
    {
        if ($this->knowledgeTypes->contains($knowledgeType)) {
            $this->knowledgeTypes->removeElement($knowledgeType);
            // set the owning side to null (unless already changed)
            if ($knowledgeType->getActivityType() === $this) {
                $knowledgeType->setActivityType(null);
            }
        }

        return $this;
    }
}
