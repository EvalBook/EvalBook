<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Role", inversedBy="roles")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $firstName;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Classe", inversedBy="users")
     */
    private $idClasses;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Activite", mappedBy="idUser")
     */
    private $activites;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Classe", mappedBy="titulaire", cascade={"persist", "remove"})
     */
    private $classeTitulaire;

    public function __construct()
    {
        $this->idClasses = new ArrayCollection();
        $this->activites = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    /**
     * @return Collection|Classe[]
     */
    public function getIdClasses(): Collection
    {
        return $this->idClasses;
    }

    public function addIdClass(Classe $idClass): self
    {
        if (!$this->idClasses->contains($idClass)) {
            $this->idClasses[] = $idClass;
        }

        return $this;
    }

    public function removeIdClass(Classe $idClass): self
    {
        if ($this->idClasses->contains($idClass)) {
            $this->idClasses->removeElement($idClass);
        }

        return $this;
    }

    /**
     * @return Collection|Activite[]
     */
    public function getActivites(): Collection
    {
        return $this->activites;
    }

    public function addActivite(Activite $activite): self
    {
        if (!$this->activites->contains($activite)) {
            $this->activites[] = $activite;
            $activite->setIdUser($this);
        }

        return $this;
    }

    public function removeActivite(Activite $activite): self
    {
        if ($this->activites->contains($activite)) {
            $this->activites->removeElement($activite);
            // set the owning side to null (unless already changed)
            if ($activite->getIdUser() === $this) {
                $activite->setIdUser(null);
            }
        }

        return $this;
    }

    public function getClasseTitulaire(): ?Classe
    {
        return $this->classeTitulaire;
    }

    public function setClasseTitulaire(?Classe $classeTitulaire): self
    {
        $this->classeTitulaire = $classeTitulaire;

        // set (or unset) the owning side of the relation if necessary
        $newTitulaire = null === $classeTitulaire ? null : $this;
        if ($classeTitulaire->getTitulaire() !== $newTitulaire) {
            $classeTitulaire->setTitulaire($newTitulaire);
        }

        return $this;
    }
}
