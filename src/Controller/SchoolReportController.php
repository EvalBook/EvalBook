<?php

namespace App\Controller;

use App\Entity\Classroom;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SchoolReportController extends AbstractController
{
    /**
     * @Route("/school/report/{classroom}", name="school_report")
     */
    public function index(Classroom $classroom)
    {
        return $this->render('school_report/index.html.twig', [
            'classroom' => $classroom,
        ]);
    }
}
