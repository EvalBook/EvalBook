<?php

namespace App\Controller;

use App\Entity\Classroom;
use App\Entity\Student;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SchoolReportController extends AbstractController
{
    /**
     * @Route("/school/report/{classroom}", name="school_report")
     */
    public function index(Classroom $classroom)
    {
        $students = [];
        // The school report is only available to classrooms that have owner ( titulaire ).
        if(!is_null($classroom->getOwner())) {
            $students = $classroom->getStudents();
        }

        return $this->render('school_report/index.html.twig', [
            'classroom' => $classroom,
            'students' => $students,
        ]);
    }


    /**
     * @Route("/school/report/individual/{student}", name="school_report_individual")
     * @param Student $student
     */
    public function getIndividualSchoolReport(Student $student)
    {

    }


    /**
     * @Route("/school/report/classroom/{classroom}", name="school_report_classroom")
     * @param Classroom $classroom
     */
    public function getClassroomSchoolReport(Classroom $classroom)
    {

    }
}
