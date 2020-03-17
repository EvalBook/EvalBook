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

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EleveCommentRepository")
 */
class EleveComment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Periode")
     */
    private $periode;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Eleve", inversedBy="eleveComments")
     */
    private $eleve;


    /**
     * Return the EleveComment ID.
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * Return the EleveComment Periode.
     * @return Periode|null
     */
    public function getPeriode(): ?Periode
    {
        return $this->periode;
    }


    /**
     * Set the EleveComment Period.
     * @param Periode|null $periode
     * @return $this
     */
    public function setPeriode(?Periode $periode): self
    {
        $this->periode = $periode;
        return $this;
    }


    /**
     * Return the Eleve EleveComment is related to.
     * @return Eleve|null
     */
    public function getEleve(): ?Eleve
    {
        return $this->eleve;
    }


    /**
     * Assign an Eleve to EleveComment.
     * @param Eleve|null $eleve
     * @return $this
     */
    public function setEleve(?Eleve $eleve): self
    {
        $this->eleve = $eleve;
        return $this;
    }
}
