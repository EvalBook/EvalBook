<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EcoleRepository")
 */
// TODO start from here.
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
     * @ORM\OneToMany(targetEntity="App\Entity\Implantation", mappedBy="idEcole")
     */
    private $implantations;

    public function __construct()
    {
        $this->implantations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Implantation[]
     */
    public function getImplantations(): Collection
    {
        return $this->implantations;
    }

    public function addImplantation(Implantation $implantation): self
    {
        if (!$this->implantations->contains($implantation)) {
            $this->implantations[] = $implantation;
            $implantation->setIdEcole($this);
        }

        return $this;
    }

    public function removeImplantation(Implantation $implantation): self
    {
        if ($this->implantations->contains($implantation)) {
            $this->implantations->removeElement($implantation);
            // set the owning side to null (unless already changed)
            if ($implantation->getIdEcole() === $this) {
                $implantation->setIdEcole(null);
            }
        }

        return $this;
    }
}
