<?php

namespace App\Controller;

use App\Entity;
use App\Entity\InstallHelper\CommonUserHelper;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class TestController extends AbstractController
{
    /**
     * @Route("/test", name="test")
     * @param EntityManagerInterface $em
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     */
    public function index(EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder)
    {
        /**
         * Création d'un utilisateur et sécurisation du mot de passe en utilisant la classe helper...
         */

        // Création d'une secrétaire.
        $helper = new CommonUserHelper($em, $passwordEncoder);
        $result = $helper->createSecretary("first", "lastName", "admin@evalbook.dev", "Dev007!!");
        $result ? $this->addFlash('success', 'User created') : $this->addFlash('error', 'User creation error');



        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }
}
