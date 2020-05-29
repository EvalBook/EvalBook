<?php

namespace App\DataFixtures;

use App\Entity\Activity;
use App\Entity\Classroom;
use App\Entity\Student;
use App\Entity\Implantation;
use App\Entity\Note;
use App\Entity\NoteType;
use App\Entity\Period;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\common\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


class DataFixtures extends Fixture implements ContainerAwareInterface, FixtureInterface, OrderedFixtureInterface
{

    private $container;

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
        $noteType1->setName("A..F");
        $noteType1->setPonderation("A..F");

        $noteType2 = new NoteType();
        $noteType2->setName("A..Z");
        $noteType2->setPonderation("A..Z");

        $em->persist($noteType1);
        $em->persist($noteType2);
        $notesTypes[] = $noteType1;
        $notesTypes[] = $noteType2;

        for($i = 5; $i <= 100; $i += 5) {
            $nt = new NoteType();
            $nt->setName("0..$i");
            $nt->setPonderation("0..$i");
            $em->persist($nt);
            $notesTypes[] = $nt;
        }


        // Creating Users.
        $users = array();
        for($i = 0; $i < 20; $i++) {
            $user = new User();
            $user
                ->setLastName($faker->lastName)
                ->setFirstName($faker->firstName)
                ->setEmail($faker->email)
                ->setPassword('Dev007!!')
                ->setRoles(["ROLE_USER, ROLE_CLASS_EDIT_STUDENTS"])
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
            ->setPassword('Dev007!!')
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
            $implantation = new Implantation();
            $implantation
                ->setName("Implantation " . $faker->name)
                ->setCountry($faker->country)
                ->setZipCode($faker->postcode)
                ->setAddress($faker->address)
            ;

            // Creating Periods.
            $periodes = array();
            for($j = 0; $j < 4; $j++) {
                $periode = new Period();
                $periode
                    ->setName("Periode $j")
                    ->setImplantation($implantation)
                    ->setDateEnd($faker->dateTimeBetween($startDate = '-8 months', $endDate = '+2 months', $timezone = null))
                    ->setDateStart($faker->dateTimeBetween($startDate = '-2 months', $endDate = '+6 months', $timezone = null))
                ;
                $periode->setImplantation($implantation);
                $em->persist($periode);
                $periodes[] = $periode;
            }
            // Persist implantation with provided periods.
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
                        ->setUser($faker->randomElement($classe->getUsers()))
                        ->setPeriod($faker->randomElement($periodes))
                        ->setComment("Generated activity comment N° $k")
                        ->setNoteType($activityNoteType)
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

        $lower = strtolower(substr($noteType->getPonderation(), 0, strpos($noteType->getPonderation(), '.')));
        $higher = strtolower(substr($noteType->getPonderation(), 1 + strrpos($noteType->getPonderation(), '.')));

        // Check format.
        if (!is_numeric($lower)) {

            $data = [];
            for ($i = ord($lower); $i <= ord($higher); $i++) {
                $data[] = chr($i);
            }

            return $faker->randomElement($data);
        }

        return $faker->randomNumber(strlen($higher));
    }



    public function getOrder()
    {
        return 1;
    }
}