<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ContactRepository")
 */
class Contact
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
    private $firstName;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $lastName;

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
     * @ORM\ManyToMany(targetEntity="App\Entity\Eleve", mappedBy="contacts")
     */
    private $eleves;


    /**
     * Contact constructor.
     */
    public function __construct()
    {
        $this->eleves = new ArrayCollection();
    }


    /**
     * Return the contact object ID.
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * Return the Contact first name.
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }


    /**
     * Set the Contact object first name.
     * @param string $firstName
     * @return $this
     */
    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;
        return $this;
    }


    /**
     * Return the Contact object last name.
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }


    /**
     * Set the Contact object last name.
     * @param string $lastName
     * @return $this
     */
    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;
        return $this;
    }


    /**
     * Return the Contact object address ( street and number ).
     * @return string|null
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }


    /**
     * Set the Contact object address ( street and number ).
     * @param string $address
     * @return $this
     */
    public function setAddress(string $address): self
    {
        $this->address = $address;
        return $this;
    }


    /**
     * Return the Contact object zip code.
     * @return string|null
     */
    public function getZipCode(): ?string
    {
        return $this->zipCode;
    }


    /**
     * Set the Contact object zip code.
     * @param string $zipCode
     * @return $this
     */
    public function setZipCode(string $zipCode): self
    {
        $this->zipCode = $zipCode;
        return $this;
    }


    /**
     * Return the Contact object country.
     * @return string|null
     */
    public function getCountry(): ?string
    {
        return $this->country;
    }


    /**
     * Set the Contact object country.
     * @param string $country
     * @return $this
     */
    public function setCountry(string $country): self
    {
        $this->country = $country;
        return $this;
    }


    /**
     * Return a list of Eleve objects that are linked to this Contact object.
     * @return Collection|Eleve[]
     */
    public function getEleves(): Collection
    {
        return $this->eleves;
    }


    /**
     * Add an Eleve object to this Contact.
     * @param Eleve $eleve
     * @return $this
     */
    public function addEleve(Eleve $eleve): self
    {
        if (!$this->eleves->contains($eleve)) {
            $this->eleves[] = $eleve;
            $eleve->addContact($this);
        }

        return $this;
    }


    /**
     * Remove an Eleve object for this contact.
     * @param Eleve $eleve
     * @return $this
     */
    public function removeEleve(Eleve $eleve): self
    {
        if ($this->eleves->contains($eleve)) {
            $this->eleves->removeElement($eleve);
            $eleve->removeContact($this);
        }

        return $this;
    }
}