<?php

namespace App\Entity;

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
    private $id_type_classe_fk;

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

    public function getIdTypeClasseFk(): ?TypeClasse
    {
        return $this->id_type_classe_fk;
    }

    public function setIdTypeClasseFk(?TypeClasse $id_type_classe_fk): self
    {
        $this->id_type_classe_fk = $id_type_classe_fk;

        return $this;
    }
}
