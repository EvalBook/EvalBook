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
    private $ecole;

    /**
     * @ORM\Column(type="boolean")
     */
    private $defaultImplantation;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Classe", mappedBy="implantation")
     */
    private $classes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Periode", mappedBy="implantation")
     */
    private $periodes;


    /**
     * Implantation constructor.
     */
    public function __construct()
    {
        $this->classes = new ArrayCollection();
        $this->periodes = new ArrayCollection();
    }


    /**
     * Return the Implantation ID.
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * Return the Implantation name.
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }


    /**
     * Set the Implantation name.
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }


    /**
     * Return the implantation address ( street and number ).
     * @return string|null
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }


    /**
     * Set the Implantation address ( street and number ).
     * @param string $address
     * @return $this
     */
    public function setAddress(string $address): self
    {
        $this->address = $address;
        return $this;
    }


    /**
     * Return the Implantation zip code.
     * @return string|null
     */
    public function getZipCode(): ?string
    {
        return $this->zipCode;
    }


    /**
     * Set the Implantation zip code.
     * @param string $zipCode
     * @return $this
     */
    public function setZipCode(string $zipCode): self
    {
        $this->zipCode = $zipCode;
        return $this;
    }


    /**
     * Return the Implantation country.
     * @return string|null
     */
    public function getCountry(): ?string
    {
        return $this->country;
    }


    /**
     * Set the Implantation country.
     * @param string $country
     * @return $this
     */
    public function setCountry(string $country): self
    {
        $this->country = $country;
        return $this;
    }


    /**
     * Return the Ecole object that own the Implantation object.
     * @return Ecole|null
     */
    public function getEcole(): ?Ecole
    {
        return $this->ecole;
    }


    /**
     * Set the Ecole thaht own the implantation.
     * @param Ecole|null $ecole
     * @return $this
     */
    public function setEcole(?Ecole $ecole): self
    {
        $this->ecole = $ecole;
        return $this;
    }


    /**
     * Return true if the Implantation object is the default one for the Ecole object ( eg: siège ).
     * @return bool|null
     */
    public function isDefaultImplantation(): ?bool
    {
        return $this->defaultImplantation;
    }


    /**
     * Define if the Implantation object is the default one.
     * @param bool $defaultImplantation
     * @return $this
     */
    public function setDefaultImplantation(bool $defaultImplantation): self
    {
        $this->defaultImplantation = $defaultImplantation;
        return $this;
    }


    /**
     * Return a collection of Classe object owned byt the Implantation object.
     * @return Collection|Classe[]
     */
    public function getClasses(): Collection
    {
        return $this->classes;
    }


    /**
     * Add a Class to the Implantation object.
     * @param Classe $class
     * @return $this
     */
    public function addClass(Classe $class): self
    {
        if (!$this->classes->contains($class)) {
            $this->classes[] = $class;
            $class->setImplantation($this);
        }

        return $this;
    }


    /**
     * Remove a class from Implantation.
     * @param Classe $class
     * @return $this
     */
    public function removeClass(Classe $class): self
    {
        if ($this->classes->contains($class)) {
            $this->classes->removeElement($class);
            // set the owning side to null (unless already changed)
            if ($class->getImplantation() === $this) {
                $class->setImplantation(null);
            }
        }

        return $this;
    }


    /**
     * Return the available list of Perdiod objects for the Implantation.
     * @return Collection|Periode[]
     */
    public function getPeriodes(): Collection
    {
        return $this->periodes;
    }


    /**
     * Add a Period object to the Implantation.
     * @param Periode $periode
     * @return $this
     */
    public function addPeriode(Periode $periode): self
    {
        if (!$this->periodes->contains($periode)) {
            $this->periodes[] = $periode;
            $periode->setImplantation($this);
        }

        return $this;
    }


    /**
     * Remove a Perdiod from the Implantation.
     * @param Periode $periode
     * @return $this
     */
    public function removePeriode(Periode $periode): self
    {
        if ($this->periodes->contains($periode)) {
            $this->periodes->removeElement($periode);
            // set the owning side to null (unless already changed)
            if ($periode->getImplantation() === $this) {
                $periode->setImplantation(null);
            }
        }

        return $this;
    }
}