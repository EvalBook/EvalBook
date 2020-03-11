<?php

namespace App\Controller;

use App\Entity\TypeClasse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    /**
     * @Route("/test", name="test")
     */
    public function index()
    {
        $tClassType = new TypeClasse();
        $tClassType->setName("Hello my class");
        echo $tClassType->getName();
        $em = $this->getDoctrine()->getManager();
        $em->persist($tClassType);
        $em->flush();

        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }
}
