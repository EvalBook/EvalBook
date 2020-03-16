<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EcoleRepository")
 */
class Ecole
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Implantation", mappedBy="ecole")
     */
    private $implantations;


    /**
     * Ecole constructor.
     */
    public function __construct()
    {
        $this->implantations = new ArrayCollection();
    }


    /**
     * Return the Ecole object ID.
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * Return the Ecole object name.
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }


    /**
     * Set the Ecole object Name.
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }


    /**
     * Return a collection of Implantation objects related to the Ecole object.
     * @return Collection|Implantation[]
     */
    public function getImplantations(): Collection
    {
        return $this->implantations;
    }


    /**
     * Add an implantation to the Ecole object.
     * @param Implantation $implantation
     * @return $this
     */
    public function addImplantation(Implantation $implantation): self
    {
        if (!$this->implantations->contains($implantation)) {
            $this->implantations[] = $implantation;
            $implantation->setEcole($this);
        }

        return $this;
    }


    /**
     * Remove an implantation from the Ecole object.
     * @param Implantation $implantation
     * @return $this
     */
    public function removeImplantation(Implantation $implantation): self
    {
        if ($this->implantations->contains($implantation)) {
            $this->implantations->removeElement($implantation);
            // set the owning side to null (unless already changed)
            if ($implantation->getEcole() === $this) {
                $implantation->setEcole(null);
            }
        }

        return $this;
    }
}