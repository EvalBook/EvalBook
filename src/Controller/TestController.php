<?php

namespace App\Controller;

use App\Entity;
use App\Entity\InstallHelper\CommonUserHelper;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    /**
     * @Route("/test", name="test")
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function index(EntityManagerInterface $em)
    {
        // Création d'un rôle.
/**
        $role = new Entity\Role();
        $role->setName("MANAGE_USERS");
        $role->setDescription("A simple role allowing user to create new users");

        // Enregistrement en base de donénes.
        $em->persist($role);
        $em->flush();
*/


        /**
         * NoteType test.
        $tClassType = new TypeClasse();
        $tClassType->setName("Hello my class");
        echo $tClassType->getName();
        $em = $this->getDoctrine()->getManager();
        $em->persist($tClassType);
        $em->flush();

         **/

        // Création d'une secrétaire.
        $helper = new CommonUserHelper($em);
        if($helper->createSecretary("firstName", "lastName", "email@t.orgin", "gfergegr"))
            echo "Success";
        else
            echo "Error";


        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }
}
