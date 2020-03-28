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

use App\Form\UserRoleType;
use App\Form\UserType;
use App\Service\FormService;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Class UsersController, manage action available on users add / remove / update / set role ...
 * @package App\Controller
 * 
 * @Route("/users", name="users_")
 */
class UsersController extends AbstractController
{
    private $entityManager;

    /**
     * UsersController constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
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
        return $this->render('users/users-list.html.twig', [
            'users' => $repository->findAll(),
        ]);
    }


    /**
     * @Route("/edit", name="edit")
     * @IsGranted("ROLE_USER_EDIT", statusCode=404, message="Not found")
     *
     * @param FormService $service
     * @param UserRepository $repository
     * @return Response
     */
    public function editUsers(FormService $service, UserRepository $repository)
    {
        $users = $repository->findAll();
        $rolesForms = array();
        $editForms = array();

        foreach($users as $usr) {
            list($rResult, $formRolesEdit) = $service->createSimpleForm('edit-user-' . $usr->getId(), UserRoleType::class, $usr);
            list($eResult, $formEdit) = $service->createSimpleForm('edit-user-roles-' . $usr->getId(), UserType::class, $usr);
            
            $rolesForms[$usr->getId()] = $formRolesEdit;
            $editForms[$usr->getId()] =  $formEdit;

            if((!is_null($rResult) && $rResult) || (!is_null($eResult) && $eResult)) {
                $this->addFlash('success', 'user.user-updated');
                return $this->redirectToRoute("users_edit"); 
            }
        }
        return $this->render('users/users-edit-list.html.twig', [
            'users' => $users,
            'rolesForms' => $rolesForms,
            'editForms' => $editForms,
        ]);
    }


    /**
     * @Route("/add", name="add")
     * @IsGranted("ROLE_USER_CREATE", statusCode=404, message="Not found")
     *
     * @param Request $request
     * @param array|null $roles
     * @param string|null $redirect
     * @return RedirectResponse|Response
     */
    public function addUser(Request $request, ?array $roles, ?string $redirect)
    {
        $user = new User();
        $userForm = $this->createForm(UserType::class, $user);

        try {
            $userForm->handleRequest($request);

            if ($userForm->isSubmitted() && $userForm->isValid()) {
                if(is_null($roles)) {
                    $roles = [
                        'ROLE_CLASS_CREATE',
                        'ROLE_CLASS_EDIT',
                        'ROLE_CLASS_PARAMETERS',
                        'ROLE_CLASS_VIEW',
                        'ROLE_CLASS_ASSIGN_STUDENT',
                        'ROLE_ACTIVITIES_LIST',
                        'ROLE_ACTIVITY_CREATE',
                        'ROLE_ACTIVITY_EDIT',
                        'ROLE_ACTIVITY_DELETE',
                        'ROLE_NOTEBOOK_VIEW',
                        'ROLE_BULLETINS_LIST',
                        'ROLE_BULLETINS_PRINT',
                        'ROLE_BULLETIN_VALIDATE',
                        'ROLE_BULLETIN_ADD_COMMENT',
                        'ROLE_BULLETIN_STYLE_EDIT',
                    ];
                }

                $user->setRoles($roles);
                $this->entityManager->persist($user);
                $this->entityManager->flush();

                $this->addFlash('success', 'user.user-added');
                return $this->redirectToRoute($redirect || "users_add");
            }
        } catch (\Exception $e) {
            $this->addFlash('danger', 'user.user-add-error');
        }
        
        return $this->render('users/user-add-form.html.twig', [
            'form' => $userForm->createView()
        ]);
    }


    /**
     * @Route("/add/admin", name="add_admin")
     * @IsGranted("ROLE_ADMIN", statusCode=404, message="Not found")
     * Add a super admin to the system.
     *
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function addUserAdmin(Request $request)
    {
        return $this->addUser($request, ["ROLE_ADMIN"], 'users_add_admin');
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
        return $this->render('users/users-delete-list.html.twig', [
            'users' => $repository->findByRole("ROLE_ADMIN", false),
        ]);
    }


    /**
     * @Route("/delete/{id}", name="delete_confirm")
     * @IsGranted("ROLE_USER_DELETE", statusCode=404, message="Not found")
     *
     * @param User $user
     * @return Response
     */
    public function deleteUserConfirm(User $user)
    {
        try {
            $this->entityManager->remove($user);
            $this->entityManager->flush();
            $this->addFlash('success', 'user.user-deleted');
        }
        catch(\Exception $e) {
            $this->addFlash('danger', 'user.user-delete-error');
        }
        
        return $this->redirectToRoute("users_delete");
    }
}