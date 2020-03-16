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
     * @ORM\Column(type="json")
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
    private $classes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Activite", mappedBy="user")
     */
    private $activites;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Classe", mappedBy="titulaire", cascade={"persist", "remove"})
     */
    private $classeTitulaire;


    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->classes = new ArrayCollection();
        $this->activites = new ArrayCollection();
    }


    /**
     * Return the User ID.
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * Return the User mail.
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }


    /**
     * Set the User mail.
     * @param string $email
     * @return $this
     */
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
        return (string) $this->getLastName() . " " . $this->getFirstName();
    }


    /**
     * Return User roles.
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }


    /**
     * Set the User roles.
     * @param array $roles
     * @return $this
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }


    /**
     * Return the User password.
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }


    /**
     * Set the user Password.
     * @param string $password
     * @return $this
     */
    public function setPassword(?string $password): self
    {
        if(!is_null($password)) {
            $this->password = $password;
        }
        return $this;
    }


    /**
     * Return the used salt.
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }


    /**
     * Erase credentials.
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }


    /**
     * Return the last name.
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }


    /**
     * Set the last name.
     * @param string $lastName
     * @return $this
     */
    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;
        return $this;
    }


    /**
     * Return the first name.
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }


    /**
     * Set the user first name.
     * @param string $firstName
     * @return $this
     */
    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;
        return $this;
    }


    /**
     * Return true if user is active.
     * @return bool|null
     */
    public function getActive(): ?bool
    {
        return $this->active;
    }


    /**
     * Set the user active or not.
     * @param bool $active
     * @return $this
     */
    public function setActive(bool $active): self
    {
        $this->active = $active;
        return $this;
    }


    /**
     * Return a collection of Classes the user own.
     * @return Collection|Classe[]
     */
    public function getClasses(): Collection
    {
        return $this->classes;
    }


    /**
     * Add a class to the user.
     * @param Classe $classe
     * @return $this
     */
    public function addClasse(Classe $classe): self
    {
        if (!$this->classes->contains($classe)) {
            $this->classes[] = $classe;
        }

        return $this;
    }


    /**
     * Remove a class a user own.
     * @param Classe $classe
     * @return $this
     */
    public function removeClasse(Classe $classe): self
    {
        if ($this->classes->contains($classe)) {
            $this->classes->removeElement($classe);
        }

        return $this;
    }


    /**
     * Return a collection of Activite User created.
     * @return Collection|Activite[]
     */
    public function getActivites(): Collection
    {
        return $this->activites;
    }


    /**
     * Add an Activity to User.
     * @param Activite $activite
     * @return $this
     */
    public function addActivite(Activite $activite): self
    {
        if (!$this->activites->contains($activite)) {
            $this->activites[] = $activite;
            $activite->setUser($this);
        }

        return $this;
    }


    /**
     * Remove an Activite from the User Activite collection.
     * @param Activite $activite
     * @return $this
     */
    public function removeActivite(Activite $activite): self
    {
        if ($this->activites->contains($activite)) {
            $this->activites->removeElement($activite);
            // set the owning side to null (unless already changed)
            if ($activite->getUser() === $this) {
                $activite->setUser(null);
            }
        }

        return $this;
    }


    /**
     * Return the Classe the User is 'titulaire'.
     * @return Classe|null
     */
    public function getClasseTitulaire(): ?Classe
    {
        return $this->classeTitulaire;
    }


    /**
     * Set the Class User is 'titulaire'.
     * @param Classe|null $classeTitulaire
     * @return $this
     */
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