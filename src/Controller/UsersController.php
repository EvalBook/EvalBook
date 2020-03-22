<?php

/**
 * Copyleft (c) 2020 EvalBook
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the European Union Public Licence (EUPL V 1.2),
 * version 1.2 (or any later version).
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * European Union Public Licence for more details.
 *
 * You should have received a copy of the European Union Public Licence
 * along with this program. If not, see
 * https://joinup.ec.europa.eu/collection/eupl/eupl-text-eupl-12
 **/

namespace App\Controller;

use App\Service\UserService;
use App\Entity\User;
use App\Form\UserRoleType;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
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
     * @Route("/", name="index")
     */
    public function index()
    {
        if(!$this->isGranted("ROLE_USER_DELETE")) {
            return $this->redirectToRoute("app_login");
        }
        return $this->render('users/index.html.twig');
    }


    /**
     * @Route("/list", name="list")
     * @IsGranted("ROLE_USERS_LIST", statusCode=404, message="Not found")
     * 
     * @param UserRepository $repository
     * @return Response
     */
    public function listUsers(UserRepository $repository)
    {
        return $this->render('users/list.html.twig', [
            'users' => $repository->findAll(),
        ]);
    }


    /**
     * @Route("/edit", name="edit")
     * @IsGranted("ROLE_USER_EDIT", statusCode=404, message="Not found")
     *
     * @param Request $request
     * @param UserRepository $repository
     * @return Response
     */
    public function editUsers(UserService $userService, UserRepository $repository)
    {
        $users = $repository->findAll();
        $rolesForms = array();
        $editForms = array();

        foreach($users as $usr) {
            list($rResult, $formRolesEdit) = $userService->roleEditForm($usr);
            list($eResult, $formEdit) = $userService->editForm($usr);
            
            $rolesForms[$usr->getId()] = $formRolesEdit;
            $editForms[$usr->getId()] =  $formEdit;

            if((!is_null($rResult) && $rResult) || (!is_null($eResult) && $eResult)) {
                $this->addFlash('success', $this->translator->trans("User updated"));
                return $this->redirectToRoute("users_edit"); 
            }
        }
        return $this->render('users/edit.html.twig', [
            'users' => $users,
            'rolesForms' => $rolesForms,
            'editForms' => $editForms,
        ]);
    }


    /**
     * @Route("/add", name="add")
     * @IsGranted("ROLE_USER_CREATE", statusCode=404, message="Not found")
     *
     * @return RedirectResponse|Response
     */
    public function addUser(UserService $userService)
    {
        list($result, $form) = $userService->addForm();
        
        if(!is_null($result) && $result) {
            $this->addFlash('success', $this->translator->trans("User added"));
            return $this->redirectToRoute("users_edit");
        }
        
        return $this->render('users/add.html.twig', [
            'userForm' => $form
        ]);
    }


    /**
     * @Route("/delete", name="delete")
     * @IsGranted("ROLE_USER_DELETE", statusCode=404, message="Not found")
     *
     * @param UserRepository $repository
     * @return Response
     */
    public function deleteUser(UserRepository $repository)
    {
        return $this->render('users/delete.html.twig', [
            'users' => $repository->findByRole("ROLE_ADMIN", false),
        ]);
    }


    /**
     * @Route("/delete/{id}", name="delete_confirm")
     * @IsGranted("ROLE_USER_DELETE", statusCode=404, message="Not found")
     * 
     * @param UserService $userService
     * @param User $user
     * @return Response
     */
    public function deleteUserConfirm(UserService $userService, User $user)
    {
        list($message, $type) = $userService->delete($user) ? ["User deleted", 'success'] : ["An error occured deleting the user", 'danger'];
        $this->addFlash($type, $this->translator->trans($message));
        
        return $this->redirectToRoute("users_delete");
    }
}