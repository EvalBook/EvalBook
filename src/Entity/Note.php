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


use DateTime;
use DateTimeInterface;
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Activity", inversedBy="notes")
     * @ORM\JoinColumn(nullable=true)
     */
    private $activity; // Nullable true in order to keep students note in case of implantation deletion.

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Student", inversedBy="notes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $student;

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
        $this->date = new DateTime('now');
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
     * Return the Activity object attached to the Note.
     * @return Activity|null
     */
    public function getActivity(): ?Activity
    {
        return $this->activity;
    }


    /**
     * Set the Note object Activity.
     * @param Activity $activity
     * @return $this
     */
    public function setActivity(?Activity $activity): self
    {
        $this->activity = $activity;
        return $this;
    }


    /**
     * Return the Student object the Note is attached to.
     * @return Student|null
     */
    public function getStudent(): ?Student
    {
        return $this->student;
    }


    /**
     * Set the Student object the Note is attached to.
     * @param Student|null $student
     * @return $this
     */
    public function setStudent(?Student $student): self
    {
        $this->student = $student;
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
        $this->note = strtoupper($note);
        return $this;
    }


    /**
     * Return the Date the Note were written.
     * @return DateTimeInterface|null
     */
    public function getDate(): ?DateTimeInterface
    {
        return $this->date;
    }


    /**
     * Set the date the Note were written.
     * @param DateTimeInterface $date
     * @return $this
     */
    public function setDate(DateTimeInterface $date): self
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


    /**
     * Check if a note match the provided note type pattern.
     *
     * @param NoteType|null $noteType
     * @return bool
     */
    public function isValid(?NoteType $noteType)
    {
        // TODO s'assurer dans le controller de bien stocker une valeur strtoupper.
        $available = array_merge([$noteType->getMaximum(), $noteType->getMinimum(), 'ABS'], $noteType->getIntervals());

        if(in_array(strtoupper($this->getNote()), $available)) {
            return true;
        }
        return false;
    }
}