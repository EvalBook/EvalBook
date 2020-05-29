<?php

namespace App\Tests;

use App\Entity\Classe;
use App\Entity\Period;
use App\Controller\NoteBookController;
use Doctrine\DBAL\Exception\NotNullConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;


class GlobalTest extends KernelTestCase
{
    private $utils;
    private $classe;

    /**
     * Executed each time a test is requested, initialize entity manager and TestUtil class.
     */
    protected function setUp(): void
    {
        self::bootKernel();
        $em = static::$kernel->getContainer()
             ->get('doctrine')
             ->getManager();
        $this->utils = new TestUtil($em);

    }

    /**
     * Test that implantation can be created and deleted.
     */
    public function testCanCRUDImplantation()
    {
        // Can create
        $implantation = $this->utils->createImplantation('name', 'address', '6464', 'country', true);
        $this->utils->getEntityManager()->flush();
        $this->assertNotNull($implantation->getId());

        // Can update / read
        $this->utils->getEntityManager()->persist($implantation->setName('My brand new name'));
        $this->utils->getEntityManager()->flush();
        $this->assertEquals('My brand new name', $implantation->getName());

        // Can delete
        $this->utils->getEntityManager()->remove($implantation);
        $this->utils->getEntityManager()->flush();
        $this->assertNull($implantation->getId());
    }


    /**
     * Test Period can be CRU and trying to save will throw a violation exception.
     */
    public function testCanCRUPeriod()
    {
        $periode = $this->utils->createPeriod(5, 'name', true);

        // Can create.
        $implantation = $this->utils->createImplantation('n', 'a', '6', 'c',true);
        $periode->setImplantation($implantation);
        $this->utils->persistAndFlush($periode);
        $this->assertNotNull($this->utils->getRepository(Period::class)->find($periode->getId()));

        // Can update / read
        $startDate = new \DateTime();
        $periode->setDateStart($startDate);
        $this->utils->persistAndFlush($periode);
        $saved = $this->utils->getRepository(Period::class)->find($periode->getId());
        $this->assertEquals($startDate, $saved->getDateStart());

        // Assert a constraint violation will be thrown by not providing an implantation.
        $periode->setImplantation(null);
        $this->expectException(NotNullConstraintViolationException::class);
        $this->utils->persistAndFlush($periode);
    }

    /**
     * Test a notebook composition.
     * @throws \Exception
     *
     * @runInSeparateProcess
     */
    public function testNotebookComponents()
    {
        $this->classe = $this->createTestClassNeededObjects();
        $notebookController = new NoteBookController();

        // Class does not have proper implantation nor period, so notebook should return an empty array of periods.
        $this->assertCount(0, $notebookController->getNotebookPeriods(new Classe()));

        // Assert the class contains exactly 20 students.
        $this->assertCount(20, $this->classe->getEleves()->toArray());

        // Assert implantation is not null and has 4 periods defined in it.
        $this->assertNotNull($this->classe->getImplantation());
        $this->assertCount(4, $this->classe->getImplantation()->getPeriodes()->toArray());

        // Assert that the class contains 4 activities and each activity has 20 * students notes in it.
        $this->assertCount(4, $this->classe->getActivites()->toArray());

        foreach($this->classe->getActivites()->toArray() as $activity) {
            $this->assertCount(20, $activity->getNotes()->toArray());
            // Assert these activities have notes that equals 5 ( defined in createTestClassNeededObject function )
            foreach($activity->getNotes()->toArray() as $note) {
                // 5 is the note provided in createTestClassNeededObjects function.
                $this->assertEquals(5, $note->getNote());
            }
        }

        // Testing notebook creation.
        $notebookPeriods = $notebookController->getNotebookPeriods($this->classe);
        // Notebook should contain 4 keys representing available periods.
        $this->assertCount(4, $notebookPeriods);
        $notebook = $notebookController->constructNotebook($this->classe);
        // Assert the notebook sheet contains 20 entries matching students list.
        $this->assertCount(20, $notebook);

        // Testing the notes Total
        // Expected ( 4 periods * 1 activity by student by period * 5 note provided * 20 students ) = 400.
        $total = 0;
        foreach($notebook as $studentRow) {
            // Getting values without 'max' delimiter  so 5 / 10 becomes 5.
            $values = array_map( function($note) {
                return substr($note, 0, 1);
            }, $studentRow);

            $total += array_sum($values);
        }

        $this->assertEquals(400, $total);
    }


    /**
     * Create all needed object in order to test notebook compose function.
     * @return Classe
     */
    private function createTestClassNeededObjects()
    {
        // Create an implantation and all I need in order to test real values.
        $implantation = $this->utils->createImplantation('Implantation test', 'Address test', '6464', 'country test');
        $classe = $this->utils->createClass($implantation, 'Primaire 1');
        $noteType     = $this->utils->createNoteType('0..10', true);

        // Setting up students
        for($i = 0; $i < 20; $i++) {
            $student = $this->utils->createStudent("Student $i first name", "Student $i last name", true);
            $classe->addEleve($student);
        }

        // Adding periods and activities.
        for($i = 0; $i < 4; $i++) {
            $period = $this->utils->createPeriod($i, "Periode $i");
            $activity = $this->utils->createActivity($noteType, $period, "Activity $i");

            // Setting notes and students for activity.
            foreach($classe->getEleves()->toArray() as $student) {
                $note = $this->utils->createNote($student, '5', true);
                $student->addNote($note);
                $activity->addNote($note);
            }

            $implantation->addPeriode($period);
            $classe->addActivite($activity);
            $this->utils->getEntityManager()->persist($activity);
            $this->utils->getEntityManager()->persist($period);
        }

        $this->utils->getEntityManager()->persist($implantation);
        $this->utils->getEntityManager()->persist($classe);

        $this->utils->getEntityManager()->flush();
        $this->utils->getEntityManager()->clear();

        return $classe;
    }
}