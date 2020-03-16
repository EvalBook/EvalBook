<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;


/**
 * Class UsersController, manage action available on users add / remove / update / set role ...
 * @package App\Controller
 * @Route("/users", name="users_")
 */
class UsersController extends AbstractController
{
    private $translator;

    /**
     * UsersController constructor.
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

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


    /**
     * @Route("/edit/{id}", name="edit")
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @param User $user
     * @return Response
     */
    public function editUser(EntityManagerInterface $entityManager, Request $request, User $user)
    {
        $userEditForm = $this->createForm(UserType::class, $user);
        $userEditForm->handleRequest($request);

        // Checking if form is valid and is submited.
        if($userEditForm->isSubmitted() && $userEditForm->isValid()) {
            try {
                $entityManager->persist($user);
                $entityManager->flush();
                $this->addFlash('success', $this->translator->trans("User updated"));
            }
            catch(\Exception $e) {
                $this->addFlash('danger', $this->translator->trans("Error updating user"));
            }
        }

        return $this->render('users/edit.html.twig', [
            'userForm' => $userEditForm->createView(),
            'username' => $user->getFirstName() . " " . $user->getLastName() | "",
        ]);
    }


    /**
     * @Route("/delete/{id}", name="delete")
     * @param EntityManager $entityManager
     * @param User $user
     * @return Response
     */
    public function deleteUser(EntityManagerInterface $entityManager, User $user)
    {
        dd($user);
        return $this->render('users/delete.html.twig');
    }
}
