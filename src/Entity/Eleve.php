<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EleveRepository")
 */
class Eleve
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
    private $last_name;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $first_name;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Classe", inversedBy="eleves")
     */
    private $id_classes_fk;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Contact", inversedBy="eleves")
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_contact_fk;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Contact", inversedBy="eleves")
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_contact_fk_secondary;

    public function __construct()
    {
        $this->id_classes_fk = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): self
    {
        $this->last_name = $last_name;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): self
    {
        $this->first_name = $first_name;

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
    public function getIdClassesFk(): Collection
    {
        return $this->id_classes_fk;
    }

    public function addIdClassesFk(Classe $idClassesFk): self
    {
        if (!$this->id_classes_fk->contains($idClassesFk)) {
            $this->id_classes_fk[] = $idClassesFk;
        }

        return $this;
    }

    public function removeIdClassesFk(Classe $idClassesFk): self
    {
        if ($this->id_classes_fk->contains($idClassesFk)) {
            $this->id_classes_fk->removeElement($idClassesFk);
        }

        return $this;
    }

    public function getIdContactFk(): ?Contact
    {
        return $this->id_contact_fk;
    }

    public function setIdContactFk(?Contact $id_contact_fk): self
    {
        $this->id_contact_fk = $id_contact_fk;

        return $this;
    }

    public function getIdContactFkSecondary(): ?Contact
    {
        return $this->id_contact_fk_secondary;
    }

    public function setIdContactFkSecondary(?Contact $id_contact_fk_secondary): self
    {
        $this->id_contact_fk_secondary = $id_contact_fk_secondary;

        return $this;
    }
}
