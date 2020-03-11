<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RoleRepository")
 */
class Role
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
     * @ORM\Column(type="text")
     */
    private $description;


    /**
     * Return the Role ID.
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * Return the Role name.
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }


    /**
     * Set the Role name.
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }


    /**
     * Return the Role description.
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }


    /**
     * Set the Role description.
     * @param string $description
     * @return $this
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }
}