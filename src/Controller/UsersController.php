<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
    private $entityManager;

    /**
     * UsersController constructor.
     * @param EntityManagerInterface $entityManager
     * @param TranslatorInterface $translator
     */
    public function __construct(EntityManagerInterface $entityManager, TranslatorInterface $translator)
    {
        $this->entityManager = $entityManager;
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
     * @param Request $request
     * @param User $user
     * @return Response
     */
    public function editUser(Request $request, User $user)
    {
        $userEditForm = $this->createForm(UserType::class, $user);
        try {
            $userEditForm->handleRequest($request);

            // Checking if form is valid and is submited.
            if($userEditForm->isSubmitted() && $userEditForm->isValid()) {
                $this->entityManager->persist($user);
                $this->entityManager->flush();
                $this->addFlash('success', $this->translator->trans("User updated"));
                return $this->redirectToRoute("users_list");
            }
        }
        catch(\Exception $e) {
            dd($e);
            $this->addFlash('danger', $this->translator->trans("Error updating user"));
        }

        return $this->render('users/edit.html.twig', [
            'userForm' => $userEditForm->createView(),
            'username' => $user->getFirstName() . " " . $user->getLastName() | "",
        ]);
    }


    /**
     * @Route("/add", name="add")
     * @param Request $request
     */
    public function addUser(Request $request)
    {

    }


    /**
     * @Route("/delete/{id}", name="delete")
     * @param User $user
     * @return Response
     */
    public function deleteUser(User $user)
    {
        dd($user);
        return $this->render('users/delete.html.twig');
    }
}
