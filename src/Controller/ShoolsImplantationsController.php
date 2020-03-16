<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ShoolsImplantationsController extends AbstractController
{
    /**
     * @Route("/shools/implantations", name="shools_implantations")
     */
    public function index()
    {
        return $this->render('schools_implantations/index.html.twig', [
            'controller_name' => 'ShoolsImplantationsController',
        ]);
    }
}
