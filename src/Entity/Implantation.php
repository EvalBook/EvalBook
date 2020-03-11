<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ImplantationRepository")
 */
class Implantation
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
     * @ORM\Column(type="string", length=255)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $zipCode;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private $country;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Ecole", inversedBy="implantations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idEcole;

    /**
     * @ORM\Column(type="boolean")
     */
    private $defaultImplantation;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Classe", mappedBy="idImplantation")
     */
    private $classes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Periode", mappedBy="idImplantation")
     */
    private $periodes;

    public function __construct()
    {
        $this->classes = new ArrayCollection();
        $this->periodes = new ArrayCollection();
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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getZipCode(): ?string
    {
        return $this->zipCode;
    }

    public function setZipCode(string $zipCode): self
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getIdEcole(): ?Ecole
    {
        return $this->idEcole;
    }

    public function setIdEcole(?Ecole $idEcole): self
    {
        $this->idEcole = $idEcole;

        return $this;
    }

    public function getDefaultImplantation(): ?bool
    {
        return $this->defaultImplantation;
    }

    public function setDefaultImplantation(bool $defaultImplantation): self
    {
        $this->defaultImplantation = $defaultImplantation;

        return $this;
    }

    /**
     * @return Collection|Classe[]
     */
    public function getClasses(): Collection
    {
        return $this->classes;
    }

    public function addClass(Classe $class): self
    {
        if (!$this->classes->contains($class)) {
            $this->classes[] = $class;
            $class->setIdImplantation($this);
        }

        return $this;
    }

    public function removeClass(Classe $class): self
    {
        if ($this->classes->contains($class)) {
            $this->classes->removeElement($class);
            // set the owning side to null (unless already changed)
            if ($class->getIdImplantation() === $this) {
                $class->setIdImplantation(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Periode[]
     */
    public function getPeriodes(): Collection
    {
        return $this->periodes;
    }

    public function addPeriode(Periode $periode): self
    {
        if (!$this->periodes->contains($periode)) {
            $this->periodes[] = $periode;
            $periode->setIdImplantation($this);
        }

        return $this;
    }

    public function removePeriode(Periode $periode): self
    {
        if ($this->periodes->contains($periode)) {
            $this->periodes->removeElement($periode);
            // set the owning side to null (unless already changed)
            if ($periode->getIdImplantation() === $this) {
                $periode->setIdImplantation(null);
            }
        }

        return $this;
    }
}
