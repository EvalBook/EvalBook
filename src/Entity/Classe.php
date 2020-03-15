<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass="App\Repository\ClasseRepository")
 */
class Classe
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
     * @ORM\ManyToOne(targetEntity="App\Entity\TypeClasse", inversedBy="classes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $typeClasse;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", mappedBy="classes")
     */
    private $users;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Eleve", mappedBy="classes")
     */
    private $eleves;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", inversedBy="classeTitulaire", cascade={"persist", "remove"})
     */
    private $titulaire;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Implantation", inversedBy="classes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $implantation;


    /**
     * Classe constructor.
     */
    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->eleves = new ArrayCollection();
    }


    /**
     * Return the Class ID.
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * Return the classe name.
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }


    /**
     * Set the classe name.
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }


    /**
     * Return the classe TypeClasse.
     * @return TypeClasse|null
     */
    public function getTypeClasse(): ?TypeClasse
    {
        return $this->typeClasse;
    }


    /**
     * Set the classe type classe.
     * @param TypeClasse|null $typeClasse
     * @return $this
     */
    public function setTypeClasse(?TypeClasse $typeClasse): self
    {
        $this->typeClasse = $typeClasse;
        return $this;
    }


    /**
     * Return the available Classe Users ( eg: Prof, Special master, ... all allowed users to manage the Class ).
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }


    /**
     * Enable a user to manage this Class by adding it to the object users list.
     * @param User $user
     * @return $this
     */
    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addClass($this);
        }

        return $this;
    }


    /**
     * Removes a user from allowed users for the Classe.
     * @param User $user
     * @return $this
     */
    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            $user->removeClass($this);
        }

        return $this;
    }


    /**
     * Return a Collection of Eleve objects registered to the Class object.
     * @return Collection|Eleve[]
     */
    public function getEleves(): Collection
    {
        return $this->eleves;
    }


    /**
     * Add an Eleve object to the Classe object.
     * @param Eleve $eleve
     * @return $this
     */
    public function addEleve(Eleve $eleve): self
    {
        if (!$this->eleves->contains($eleve)) {
            $this->eleves[] = $eleve;
            $eleve->addClass($this);
        }

        return $this;
    }


    /**
     * Remove an Eleve Object to the Classe object.
     * @param Eleve $eleve
     * @return $this
     */
    public function removeEleve(Eleve $eleve): self
    {
        if ($this->eleves->contains($eleve)) {
            $this->eleves->removeElement($eleve);
            $eleve->removeClass($this);
        }

        return $this;
    }


    /**
     * Return the User object that own the Class ( aka titulaire ).
     * @return User|null
     */
    public function getTitulaire(): ?User
    {
        return $this->titulaire;
    }


    /**
     * Set the class object owner ( aka titulaire ).
     * @param User|null $titulaire
     * @return $this
     */
    public function setTitulaire(?User $titulaire): self
    {
        $this->titulaire = $titulaire;
        return $this;
    }


    /**
     * Return the Classe object implantation.
     * @return Implantation|null
     */
    public function getImplantation(): ?Implantation
    {
        return $this->implantation;
    }


    /**
     * Set the Classe Object implantation.
     * @param Implantation|null $implantation
     * @return $this
     */
    public function setImplantation(?Implantation $implantation): self
    {
        $this->implantation = $implantation;
        return $this;
    }
}