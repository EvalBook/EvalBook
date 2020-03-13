<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TwigTestsController extends AbstractController
{
    /**
     * @Route("/twig", name="twig_tests")
     */
    public function index()
    {
        return $this->render('twig_tests/index.html.twig', [
            'controller_name' => 'TwigTestsController',
        ]);
    }
}