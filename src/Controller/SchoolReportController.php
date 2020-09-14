<?php

namespace App\Controller;

use App\Entity\Classroom;
use App\Entity\Student;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SchoolReportController extends AbstractController
{
    /**
     * @Route("/school/report/{classroom}", name="school_report")
     * @param Classroom $classroom
     * @return Response
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
            'implantation' => $classroom->getImplantation(),
        ]);
    }


    /**
     * @Route("/school/report/classroom/{classroom}", name="school_report_classroom")
     * @param Classroom $classroom
     */
    public function getClassroomSchoolReport(Classroom $classroom)
    {
        $student=[];


    }


    /**
     * @Route("/school/report/pupil/{pupil}", name="school_report_pupil")
     * @param Student $student
     */

    public function pupil(){


        return $this -> render('school_report/school_report_individual.html.twig');

    }
}
