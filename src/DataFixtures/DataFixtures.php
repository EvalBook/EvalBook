<?php

namespace App\DataFixtures;

use App\Entity\Activite;
use App\Entity\Classe;
use App\Entity\Eleve;
use App\Entity\Implantation;
use App\Entity\Note;
use App\Entity\NoteType;
use App\Entity\Periode;
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
        for($i = 0; $i < 50; $i++) {
            $user = new User();
            $user
                ->setLastName($faker->lastName)
                ->setFirstName($faker->firstName)
                ->setEmail($faker->email)
                ->setPassword(password_hash('Dev007!!', PASSWORD_BCRYPT))
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
            ->setPassword(password_hash('Dev007!!', PASSWORD_BCRYPT))
            ->setRoles(["ROLE_ADMIN"])
        ;
        $em->persist($user);
        $users[] = $user;

        // Creating Eleves.
        $students = array();
        for($i = 0; $i < 500; $i++) {
            $student = new Eleve();
            $student
                ->setLastName($faker->lastName)
                ->setFirstName($faker->firstName)
                ->setBirthday($faker->dateTime)
            ;
            $em->persist($student);
            $students[] = $student;
        }


        for($i = 0; $i < 6; $i++) {
            // Creating Implantations
            $implantation = new Implantation();
            $implantation
                ->setName($faker->company)
                ->setCountry($faker->country)
                ->setZipCode($faker->postcode)
                ->setAddress($faker->address)
            ;

            // Creating Periods.
            for($j = 0; $j < 4; $j++) {
                $periode = new Periode();
                $periode
                    ->setName("Periode $i")
                    ->setImplantation($implantation)
                    ->setDateEnd($faker->dateTimeBetween($startDate = '-8 months', $endDate = '+2 months', $timezone = null))
                    ->setDateStart($faker->dateTimeBetween($startDate = '-2 months', $endDate = '+6 months', $timezone = null))
                ;
                $implantation->addPeriode($periode);
                $em->persist($periode);
            }
            // Persist implantation with provided periods.
            $em->persist($implantation);

            // Creating Classes
            for($j = 0; $j < 6; $j++) {
                $classe = new Classe();
                $classe
                    ->setName("Primaire $j")
                    ->setImplantation($implantation)
                    ->setStudents($faker->randomElements($students, mt_rand(10, 25)))
                    ->setUsers($faker->randomElements($users, mt_rand(2, 5)))
                    ->setTitulaire($faker->randomElement($users))
                ;
                $em->persist($classe);

                // Adding activities to this class.
                for($k = 0; $k < 10; $k++) {
                    $activity = new Activite();
                    $name = $k % 2 ? "maths" : "français";
                    $activityNoteType = $faker->randomElement($notesTypes);

                    $activity
                        ->setName("Activité $name $k")
                        ->setUser($faker->randomElement($users))
                        ->setPeriode($faker->randomElement($classe->getImplantation()->getPeriodes()))
                        ->setComment("Generated activity comment N° $k")
                        ->setNoteType($activityNoteType)
                        // Last one cause void method, no chaining possible.
                        ->setClasse($classe)
                    ;

                    $em->persist($activity);

                    // Adding notes to this activity.
                    $notesComments = ["Peux mieux faire", "On y va !", "Comme on", "Tu progresse vite", "A ton rythme", "Au suivant !"];

                    foreach($classe->getEleves() as $eleve) {
                        $note = new Note();
                        $note
                            ->setActivite($activity)
                            ->setComment($faker->randomElement($notesComments))
                            ->setEleve($eleve)
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