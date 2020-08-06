<?php

namespace App\Entity;

use App\Repository\ActivityTypeRepository;
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
    private $type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * Return the activity type id.
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * Return the activity type type.
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }


    /**
     * Set the activity type type.
     * @param string $type
     * @return $this
     */
    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }


    /**
     * Return the activity type name.
     * @return string|null$
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
}
