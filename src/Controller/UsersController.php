<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UsersController, manage action available on users add / remove / update / set role ...
 * @package App\Controller
 * @Route("/users", name="users_")
 */
class UsersController extends AbstractController
{
    /**
     * @Route("/", name="list")
     * @param UserRepository $repository
     * @return Response
     */
    public function index(UserRepository $repository)
    {
        return $this->render('users/index.html.twig', [
            'users' => $repository->findAll(),
        ]);
    }
}
