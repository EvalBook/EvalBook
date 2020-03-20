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

use App\Entity\User;
use App\Form\UserRoleType;
use App\Form\UserType;
use App\Kernel;
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
     * @Route("/", name="index")
     */
    public function index()
    {
        // Return the global users available actions.
        return $this->render('users/index.html.twig');
    }


    /**
     * @Route("/edit", name="edit")
     * @param Request $request
     * @param UserRepository $repository
     * @return Response
     */
    public function edit(Request $request, UserRepository $repository)
    {
        $users = $repository->findAll();
        $rolesForms = array();
        $editForms = array();

        foreach($users as $usr) {
            // Roles edition form.
            $formRolesEdit = $this->get('form.factory')->createNamed('edit-roles' . $usr->getId(), UserRoleType::class, $usr);
            $rolesForms[$usr->getId()] = $formRolesEdit->createView();

            // User edition form.
            $formEditUser = $this->get('form.factory')->createNamed('edit-user' . $usr->getId(), UserType::class, $usr);
            $editForms[$usr->getId()] =  $formEditUser->createView();

            try {
                $formRolesEdit->handleRequest($request);
                $formEditUser->handleRequest($request);

                if ($formRolesEdit->isSubmitted() && $formRolesEdit->isValid() ||
                    $formEditUser->isSubmitted() && $formEditUser->isValid())
                {
                    $this->entityManager->persist($usr);
                    $this->entityManager->flush();
                    $this->addFlash('success', $this->translator->trans("User roles updated"));
                    return $this->redirectToRoute("users_edit");
                }
            }
            catch(\Exception $e) {
                $this->addFlash('danger', $this->translator->trans("Error updating user"));
            }
        }
        return $this->render('users/edit.html.twig', [
            'users' => $repository->findAll(),
            'rolesForms' => $rolesForms,
            'editForms' => $editForms,
        ]);
    }


    /**
     * @Route("/add", name="add")
     *
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