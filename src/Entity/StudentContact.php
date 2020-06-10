<?php

namespace App\Entity;

use App\Repository\StudentContactRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StudentContactRepository::class)
 */
class StudentContact
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @ORM\OneToMany(targetEntity=StudentContactRelation::class, mappedBy="contact", orphanRemoval=true)
     */
    private $studentContactRelations;


    /**
     * StudentContact constructor.
     */
    public function __construct()
    {
        $this->studentContactRelations = new ArrayCollection();
    }


    /**
     * Return the contact id.
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * Return the contact first name.
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }


    /**
     * Set the contact first name.
     * @param string $firstName
     * @return $this
     */
    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }


    /**
     * Return the contact last name.
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }


    /**
     * Set the contact last name.
     * @param string $lastName
     * @return $this
     */
    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }


    /**
     * Return the contact address.
     * @return string|null
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }


    /**
     * Set the contact address.
     * @param string $address
     * @return $this
     */
    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }


    /**
     * Return the contact Phone
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }


    /**
     * Set the contact phone
     * @param string|null $phone
     * @return $this
     */
    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }


    /**
     * Return the contact mail address.
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }


    /**
     * Set the contact mail address.
     * @param string|null $email
     * @return $this
     */
    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }


    /**
     * Return the contact relations with students, StudentContactRelation define relation between a student entity and a contact entity.
     * @return Collection|StudentContactRelation[]
     */
    public function getStudentContactRelations(): Collection
    {
        return $this->studentContactRelations;
    }


    /**
     * Add a new contact relation with student.
     * @param StudentContactRelation $studentContactRelation
     * @return $this
     */
    public function addStudentContactRelation(StudentContactRelation $studentContactRelation): self
    {
        if (!$this->studentContactRelations->contains($studentContactRelation)) {
            $this->studentContactRelations[] = $studentContactRelation;
            $studentContactRelation->setContact($this);
        }

        return $this;
    }


    /**
     * Remove a contact relation.
     * @param StudentContactRelation $studentContactRelation
     * @return $this
     */
    public function removeStudentContactRelation(StudentContactRelation $studentContactRelation): self
    {
        if ($this->studentContactRelations->contains($studentContactRelation)) {
            $this->studentContactRelations->removeElement($studentContactRelation);
            // set the owning side to null (unless already changed)
            if ($studentContactRelation->getContact() === $this) {
                $studentContactRelation->setContact(null);
            }
        }

        return $this;
    }
}
