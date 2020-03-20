<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SchoolReportsController extends AbstractController
{
    /**
     * @Route("/school/reports", name="school_reports")
     */
    public function index()
    {
        return $this->render('school_reports/index.html.twig', [
            'controller_name' => 'SchoolReportsController',
        ]);
    }
}
