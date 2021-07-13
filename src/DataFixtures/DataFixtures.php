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

namespace App\DataFixtures;

use App\Entity\Activity;
use App\Entity\Classroom;
use App\Entity\School;
use App\Entity\Student;
use App\Entity\Implantation;
use App\Entity\Note;
use App\Entity\NoteType;
use App\Entity\Period;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class DataFixtures extends Fixture implements ContainerAwareInterface, FixtureInterface, OrderedFixtureInterface, FixtureGroupInterface
{
    private $passwordEncoder;
    private ?ContainerInterface $container;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder) {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }


    public function load(ObjectManager $em)
    {

        $faker = Factory::create('fr_FR');

        // Adding available NoteTypes.
        $notesTypes = array();

        // A..F interval
        $noteType1 = new NoteType();
        $noteType1->setName("A..F coeff 1")
                  ->setDescription("Notation de A à F coefficient 1")
                  ->setMaximum('A')
                  ->setMinimum('F')
                  ->setIntervals(['B', 'C', 'D', 'E']);

        $noteType2 = new NoteType();
        $noteType2->setName("A..Z coeff 1")
                  ->setDescription("Notation de A à Z coefficient 1")
                  ->setMaximum('A')
                  ->setMinimum('Z')
                  ->setIntervals(['B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S',
                                 'T', 'U', 'V', 'W', 'X', 'Y']);

        $em->persist($noteType1);
        $em->persist($noteType2);
        $notesTypes[] = $noteType1;
        $notesTypes[] = $noteType2;


        // Creating Users.
        $users = array();
        for($i = 0; $i < 20; $i++) {
            $user = new User();
            $user
                ->setLastName($faker->lastName)
                ->setFirstName($faker->firstName)
                ->setEmail($faker->email)
                ->setPassword($this->passwordEncoder->encodePassword($user, 'Dev007!!'))
                ->setRoles(["ROLE_USER", "ROLE_CLASS_EDIT_STUDENTS"])
                // Reste classe titulaire et classes.
            ;
            $em->persist($user);
            $users[] = $user;
        }

        // Adding test admin user.
        $user = new User();
        $user
            ->setLastName('Admin')
            ->setFirstName('Super')
            ->setEmail("admin@evalbook.dev")
            ->setPassword($this->passwordEncoder->encodePassword($user, 'Dev007!!'))
            ->setRoles(["ROLE_ADMIN"])
        ;
        $em->persist($user);
        $users[] = $user;

        // Creating Eleves.
        $students = array();
        for($i = 0; $i < 250; $i++) {
            $student = new Student();
            $student
                ->setLastName($faker->lastName)
                ->setFirstName($faker->firstName)
                ->setBirthday($faker->dateTime)
            ;
            $em->persist($student);
            $students[] = $student;
        }


        for($i = 0; $i < 3; $i++) {
            // Creating Implantations
            $school = new School();
            $school->setName("Ecole $i");

            $implantation = new Implantation();
            $implantation
                ->setName("Implantation " . $faker->name)
                ->setAddress($faker->address)
                ->setSchool($school)
            ;

            // Creating Periods.
            $periodes = array();
            for($j = 0; $j < 4; $j++) {
                $periode = new Period();
                $periode
                    ->setName("Periode $j")
                    ->setImplantation($implantation)
                    ->setDateEnd($faker->dateTimeBetween('-8 months', '+2 months'))
                    ->setDateStart($faker->dateTimeBetween('-2 months', '+6 months'))
                ;
                $periode->setImplantation($implantation);
                $em->persist($periode);
                $periodes[] = $periode;
            }
            // Persist implantation with provided periods.
            $em->persist($school);
            $em->persist($implantation);

            // Creating Classes
            for($j = 0; $j < 6; $j++) {
                $classe = new Classroom();
                $classe
                    ->setName("Primaire $j")
                    ->setImplantation($implantation)
                    ->setStudents($faker->randomElements($students, mt_rand(10, 25)))
                    ->setUsers($faker->randomElements($users, mt_rand(2, 4)))
                    ->setOwner($faker->randomElement($users))
                ;
                $em->persist($classe);

                // Adding activities to this class.
                for($k = 0; $k < 5; $k++) {
                    $activity = new Activity();
                    $name = $k % 2 ? "maths" : "français";
                    $activityNoteType = $faker->randomElement($notesTypes);

                    $activity
                        ->setName("Activité $name $k")
                        ->setUser($faker->randomElement($classe->getUsers()->toArray()))
                        ->setPeriod($faker->randomElement($periodes))
                        ->setComment("Generated activity comment N° $k")
                        ->setNoteType($activityNoteType)
                        ->setIsInShoolReport(true)
                        ->setCoefficient(1)
                        // Last one cause void method, no chaining possible.
                        ->setClassroom($classe)
                    ;

                    $em->persist($activity);

                    // Adding notes to this activity.
                    $notesComments = ["Peux mieux faire", "On y va !", "Comme on", "Tu progresse vite", "A ton rythme", "Au suivant !"];

                    foreach($classe->getStudents() as $eleve) {
                        $note = new Note();
                        $note
                            ->setActivity($activity)
                            ->setComment($faker->randomElement($notesComments))
                            ->setStudent($eleve)
                            ->setNote($this->generateNote($activity->getNoteType(), $faker))
                        ;
                        $em->persist($note);
                    }
                }
            }
        }

        // Finally pushing to db.
        $em->flush();
    }


    /**
     * @param NoteType $noteType
     * @param \Faker\Generator $faker
     * @return bool
     */
    private function generateNote(NoteType $noteType, \Faker\Generator $faker)
    {
        $notes = array_merge([$noteType->getMinimum(), $noteType->getMaximum()], $noteType->getIntervals());
        return $faker->randomElement($notes);
    }



    public function getOrder()
    {
        return 1;
    }


    /**
     * @return array
     */
    public static function getGroups(): array
    {
        return ["local", "dev"];
    }
}