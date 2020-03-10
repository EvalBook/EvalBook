<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass="App\Repository\TypeClasseRepository")
 */
class TypeClasse
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=45)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Classe", mappedBy="idTypeClasse")
     */
    private $classes;


    /**
     * TypeClasse constructor.
     */
    public function __construct()
    {
        $this->classes = new ArrayCollection();
    }


    /**
     * Return the TypeClass ID.
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * Return the TypeClass name.
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }


    /**
     * Set the TypeClasse name.
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }


    /**
     * Return all available classes attached to object ClassType.
     * @return Collection|Classe[]
     */
    public function getClasses(): Collection
    {
        return $this->classes;
    }


    /**
     * Add a class with this ClassType.
     * @param Classe $class
     * @return $this
     */
    public function addClass(Classe $class): self
    {
        if (!$this->classes->contains($class)) {
            $this->classes[] = $class;
            $class->setIdTypeClasse($this);
        }

        return $this;
    }


    /**
     * Remove a class with this ClassType.
     * @param Classe $class
     * @return $this
     */
    public function removeClass(Classe $class): self
    {
        if ($this->classes->contains($class)) {
            $this->classes->removeElement($class);
            // set the owning side to null (unless already changed)
            if ($class->getIdTypeClasse() === $this) {
                $class->setIdTypeClasse(null);
            }
        }

        return $this;
    }
}