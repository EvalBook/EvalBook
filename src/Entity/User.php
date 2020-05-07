<?php

/**
 * Copyleft (c) 2020 EvalBook
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the European Union Public Licence (EUPL V 1.2),
 * version 1.2 (or any later version).
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * European Union Public Licence for more details.
 *
 * You should have received a copy of the European Union Public Licence
 * along with this program. If not, see
 * https://joinup.ec.europa.eu/collection/eupl/eupl-text-eupl-12
 **/

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
     * Return true if the user has at least one of the provided role in array.
     * @param array $roles
     * @return boolean
     */
    public function firstRoleMatch(array $roles)
    {
        foreach($this->getRoles() as $role) {
            if(in_array($role, $roles)) {
                return true;
            }
        }
        return in_array("ROLE_ADMIN", $this->getRoles());
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


    public function getEleves()
    {
        $students = array();
        foreach($this->getClasses() as $classe) {
            foreach($classe->getEleves() as $eleve) {
                $students[] = $eleve;
            }
        }

        return $students;
    }


    /**
     * Return a list of all available roles.
     */
    public static function getAssignableRoles(): array
    {
        return array(
            // Users related.
            'ROLE_USER_LIST_ALL',
            'ROLE_USER_CREATE',
            'ROLE_USER_EDIT',
            'ROLE_USER_DELETE',
            // Students related.
            'ROLE_STUDENT_LIST_ALL',
            'ROLE_STUDENT_CREATE',
            'ROLE_STUDENT_EDIT',
            'ROLE_STUDENT_DELETE',
            // Periods related.
            'ROLE_PERIOD_LIST_ALL',
            'ROLE_PERIOD_CREATE',
            'ROLE_PERIOD_EDIT',
            'ROLE_PERIOD_DELETE',
            // Classes related.
            'ROLE_CLASS_LIST_ALL',
            'ROLE_CLASS_CREATE',
            'ROLE_CLASS_EDIT',
            'ROLE_CLASS_DELETE',
            'ROLE_CLASS_EDIT_STUDENTS',
            'ROLE_CLASS_EDIT_USERS',
            // Activities related.
            'ROLE_ACTIVITY_LIST_ALL',
            'ROLE_ACTIVITY_CREATE',
            'ROLE_ACTIVITY_EDIT',
            'ROLE_ACTIVITY_DELETE',
            // Notebook related.
            'ROLE_NOTEBOOK_VIEW',
            // Bulletins related.
            'ROLE_BULLETIN_LIST_ALL',
            'ROLE_BULLETIN_PRINT_ALL',
            'ROLE_BULLETIN_VALIDATE',
            'ROLE_BULLETIN_ADD_COMMENT',
            'ROLE_BULLETIN_STYLE_EDIT',
            // Implantations related.
            'ROLE_IMPLANTATION_LIST_ALL',
            'ROLE_IMPLANTATION_EDIT',
            'ROLE_IMPLANTATION_CREATE',
            'ROLE_IMPLANTATION_DELETE',
        );
    }

    public function __toString()
    {
        return $this->getFirstName() . " " . $this->getLastName();
    }
}