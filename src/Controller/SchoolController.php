<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SchoolController extends AbstractController
{
    /**
     * @Route("/school", name="school")
     */
    public function index()
    {
        return $this->render('schools/index.html.twig', [
            'controller_name' => 'SchoolController',
        ]);
    }
}
