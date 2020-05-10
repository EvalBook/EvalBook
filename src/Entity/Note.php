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

/**
 * @ORM\Entity(repositoryClass="App\Repository\NoteRepository")
 */
class Note
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Activite", inversedBy="notes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $activite;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Eleve", inversedBy="notes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $eleve;

    /**
     * @ORM\Column(type="string", length=45)
     */
    private $note;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment;


    /**µ
     * Note constructor.
     */
    public function __construct()
    {
        // Setting the date here for auto insert.
        $this->date = new \DateTime('now');
    }


    /**
     * Return the Note ID.
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * Return the Activite object attached to the Note.
     * @return Activite|null
     */
    public function getActivite(): ?Activite
    {
        return $this->activite;
    }


    /**
     * Set the Note object Activity.
     * @param Activite $activite
     * @return $this
     */
    public function setActivite(Activite $activite): self
    {
        $this->activite = $activite;
        return $this;
    }


    /**
     * Return the Eleve object the Note is attached to.
     * @return Eleve|null
     */
    public function getEleve(): ?Eleve
    {
        return $this->eleve;
    }


    /**
     * Set the Eleve object the Note is attached to.
     * @param Eleve|null $eleve
     * @return $this
     */
    public function setEleve(?Eleve $eleve): self
    {
        $this->eleve = $eleve;
        return $this;
    }


    /**
     * Return the given note.
     * @return string|null
     */
    public function getNote(): ?string
    {
        return $this->note;
    }


    /**
     * Set the Note note.
     * @param string $note
     * @return $this
     */
    public function setNote(string $note): self
    {
        $this->note = $note;

        return $this;
    }


    /**
     * Return the Date the Note were written.
     * @return \DateTimeInterface|null
     */
    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }


    /**
     * Set the date the Note were written.
     * @param \DateTimeInterface $date
     * @return $this
     */
    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;
        return $this;
    }


    /**
     * Return the Note comment.
     * @return string|null
     */
    public function getComment(): ?string
    {
        return $this->comment;
    }


    /**
     * Set the Note comment.
     * @param string|null $comment
     * @return $this
     */
    public function setComment(?string $comment): self
    {
        $this->comment = $comment;
        return $this;
    }
}