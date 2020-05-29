<?php

namespace App\Tests;

use App\Entity\Activity;
use App\Entity\Classe;
use App\Entity\Student;
use App\Entity\Implantation;
use App\Entity\Note;
use App\Entity\NoteType;
use App\Entity\Period;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectRepository;

class TestUtil
{
    private $manager;

    /**
     * TestUtil constructor.
     * @param ObjectManager $em
     */
    public function __construct(ObjectManager $em)
    {
        $this->manager = $em;
    }

    /**
     * Return the entity manager.
     * @return ObjectManager
     */
    public function getEntityManager(): ObjectManager
    {
        return $this->manager;
    }

    /**
     * Persist and flush.
     * @param Object $object
     */
    public function persistAndFlush(Object $object): void
    {
        $this->manager->persist($object);
        $this->manager->flush();
    }

    /**
     * Return the target entity repository.
     * @param String $classname
     * @return ObjectRepository
     */
    public function getRepository(String $classname): ObjectRepository
    {
        return $this->manager->getRepository($classname);
    }

    /**
     * Create a full implantation object and persist if needed.
     * @param String $name
     * @param String $address
     * @param String $zip
     * @param String $country
     * @param bool $persist
     * @return Implantation
     */
    public function createImplantation(String $name, String $address, String $zip, String $country, bool $persist = false): Implantation
    {
        // Create an implantation and all I need in order to test real values.
        $implantation = new Implantation();
        $implantation
            ->setName($name)
            ->setAddress($address)
            ->setZipCode($zip)
            ->setCountry($country)
        ;

        if($persist)
            $this->manager->persist($implantation);

        return $implantation;
    }

    /**
     * Create a Period object and persist if needed.
     * @param String $modifier
     * @param String $name
     * @param bool $persist
     * @return Period
     */
    public function createPeriod(String $modifier, String $name, bool $persist = false): Period
    {
        $period = new Period();
        $period
            ->setDateStart((new \DateTime())->modify("- $modifier month"))
            ->setDateEnd((new \DateTime())->modify("+ $modifier month"))
            ->setName($name)
        ;

        if($persist)
            $this->manager->persist($period);

        return $period;
    }

    /**
     * Create a fake NoteType
     * @param String $ponderation
     * @param bool $persist
     * @return NoteType
     */
    public function createNoteType(String $ponderation, bool $persist = false): NoteType
    {
        $noteType = new NoteType();
        $noteType
            ->setPonderation($ponderation)
            ->setName($ponderation)
        ;

        if($persist)
            $this->manager->persist($noteType);

        return $noteType;
    }

    /**
     * Create a full class object and persist if needed.
     * @param Implantation $implantation
     * @param String $name
     * @param bool $persist
     * @return Classe
     */
    public function createClass(Implantation $implantation, String $name, bool $persist = false): Classe
    {
        // Creating the target test class.
        $classe = new Classe();
        $classe
            ->setName($name)
            ->setImplantation($implantation)
        ;

        if($persist)
            $this->manager->persist($classe);

        return $classe;
    }

    /**
     * Create a student and persist if needed.
     * @param String $firstName
     * @param String $lastName
     * @param bool $persist
     * @return Student
     */
    public function createStudent(String $firstName, String $lastName, bool $persist = false): Student
    {
        $student = new Student();
        $student
            ->setFirstName($firstName)
            ->setLastName($lastName)
            ->setBirthday(new \DateTime())
        ;

        if($persist)
            $this->manager->persist($student);

        return $student;
    }

    /**
     * Create an activity and perform persist if needed.
     * @param NoteType $noteType
     * @param Period $period
     * @param String $name
     * @param bool $persist
     * @return Activity
     */
    public function createActivity(NoteType $noteType, Period $period, String $name, bool $persist = false): Activity
    {
        // Setting up activities.
        $activity = new Activity();
        $activity
            ->setNoteType($noteType)
            ->setName($name)
            ->setPeriode($period)
        ;

        if($persist)
            $this->manager->persist($activity);

        return $activity;
    }

    /**
     * Create a Note object and persist if needed.
     * @param Student $student
     * @param String $noteValue
     * @param bool $persist
     * @return Note
     */
    public function createNote(Student $student, String $noteValue, bool $persist = false): Note
    {
        $note = new Note();
        $note
            ->setEleve($student)
            ->setNote($noteValue)
        ;

        if($persist)
            $this->manager->persist($note);

        return $note;
    }
}
