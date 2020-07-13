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

use App\Repository\StudentContactRelationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StudentContactRelationRepository::class)
 */
class StudentContactRelation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=StudentContact::class, inversedBy="studentContactRelations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $contact;

    /**
     * @ORM\ManyToOne(targetEntity=Student::class, inversedBy="studentContactRelations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $student;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $relation;

    /**
     * @ORM\Column(type="boolean")
     */
    private $sendSchoolReport;


    /**
     * Return the relation id.
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * Return the Contact entity attached to this relation.
     * @return StudentContact|null
     */
    public function getContact(): ?StudentContact
    {
        return $this->contact;
    }


    /**
     * Set the Contact entity attached to this relation.
     * @param StudentContact|null $contact
     * @return $this
     */
    public function setContact(?StudentContact $contact): self
    {
        $this->contact = $contact;

        return $this;
    }


    /**
     * Return the student attached to this relation.
     * @return Student|null
     */
    public function getStudent(): ?Student
    {
        return $this->student;
    }


    /**
     * Set the student attached to this relation.
     * @param Student|null $student
     * @return $this
     */
    public function setStudent(?Student $student): self
    {
        $this->student = $student;

        return $this;
    }


    /**
     * Return the relation.
     * @see self::getAvailableRelations()
     * @return string|null
     */
    public function getRelation(): ?string
    {
        return $this->relation;
    }


    /**
     * Set the relation.
     * @see self::getAvailableRelations()
     * @param string $relation
     * @return $this
     */
    public function setRelation(string $relation): self
    {
        $this->relation = $relation;

        return $this;
    }


    /**
     * Return true if contact attached to this relation should receive the school report.
     * @return bool|null
     */
    public function getSendSchoolReport(): ?bool
    {
        return $this->sendSchoolReport;
    }


    /**
     * Set to true to make the contact attached to the relation receive the school report of attached relation Student.
     * @param bool $sendSchoolReport
     * @return $this
     */
    public function setSendSchoolReport(bool $sendSchoolReport): self
    {
        $this->sendSchoolReport = $sendSchoolReport;

        return $this;
    }


    /**
     * Return a list of available relations
     * @return string[]
     */
    public static function getAvailableRelations() {
        return array(
            'MOTHER',
            'FATHER',
            'MOTHER FATHER',
            'MEDICAL',
            'GRAND PARENTS',
            'LEGAL GUARDIAN',
            'OTHER',
        );
    }
}
