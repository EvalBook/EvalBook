<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class BulletinsController extends AbstractController
{
    /**
     * @Route("/bulletins", name="bulletins")
     */
    public function index()
    {
        return $this->render('bulletins/index.html.twig', [
            'controller_name' => 'BulletinsController',
        ]);
    }
}
