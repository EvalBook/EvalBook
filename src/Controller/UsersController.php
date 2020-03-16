<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UsersController, manage action available on users add / remove / update / set role ...
 * @package App\Controller
 */
class UsersController extends AbstractController
{
    /**
     * @Route("/user", name="user")
     */
    public function index(UserRepository $repository)
    {
        return $this->render('users/index.html.twig', [
            'controller_name' => 'UsersController', [
                'users' => $repository->findAll()
            ]
        ]);
    }
}
