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

use App\Form\UserProfileType;
use App\Form\UserType;
use App\Entity\User;
use App\Repository\ClassroomRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Class UsersController, manage action available on users add / remove / update / set role ...
 * @package App\Controller
 *
 */
class UsersController extends AbstractController
{
    private $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }


    /**
     * @Route("/users", name="users")
     * @IsGranted("ROLE_USER_LIST_ALL", statusCode=404, message="Not found")
     *
     * @return Response
     */
    public function index()
    {
        // Getting all non admin users.
        if(!in_array('ROLE_ADMIN', $this->getUser()->getRoles())) {
            $users = $this->repository->findByRole('ROLE_ADMIN', false);
        }
        else {
            $users = $this->repository->findAll();
        }
        return $this->render('users/index.html.twig', [
            'users' => $users,
        ]);
    }


    /**
     * @Route("/user/edit/{id}/{redirect}", name="user_edit", defaults={"redirect"=null})
     * @IsGranted("ROLE_USER_EDIT", statusCode=404, message="Not found")
     *
     * @param Request $request
     * @param User $user
     * @param String $redirect
     * @return Response
     */
    public function edit(Request $request, User $user, UserRepository $repository, ?string $redirect)
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if($repository->userExists($user)) {
                $this->addFlash('error', 'User already exists');
            }
            else {
                $this->getDoctrine()->getManager()->flush();
                $this->addFlash('success', 'Successfully updated');

                if (!is_null($redirect)) {
                    $redirect = json_decode(base64_decode($redirect), true);
                    return $this->redirectToRoute($redirect['route'], $redirect["params"]);
                }

                return $this->redirectToRoute('users');
            }
        }

        return $this->render('users/form.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/user/add", name="user_add")
     * @IsGranted("ROLE_USER_CREATE", statusCode=404, message="Not found")
     *
     * @param Request $request
     * @param UserRepository $repository
     * @return RedirectResponse|Response
     */
    public function add(Request $request, UserRepository $repository)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if($repository->userExists($user)) {
                $this->addFlash('error', 'User already exists');
            }
            else {
                if(empty($user->getPassword())) {
                    // Checking here, cause user can update its profile without password modification !
                    $this->addFlash('error', "Password is empty");
                }
                else {
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($user);
                    $entityManager->flush();
                    $this->addFlash('success', 'Successfully added');

                    return $this->redirectToRoute('users');
                }
            }
        }

        return $this->render('users/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/user/delete/{id}", name="user_delete", methods={"POST"})
     * @IsGranted("ROLE_USER_DELETE", statusCode=404, message="Not found")
     *
     * @param ClassroomRepository $classroomRepository
     * @param Request $request
     * @param User $user
     * @return JsonResponse
     */
    public function delete(ClassroomRepository $classroomRepository, Request $request, User $user)
    {
        $jsonRequest = json_decode($request->getContent(), true);
        if( !isset($jsonRequest['csrf']) || !$this->isCsrfTokenValid('user_delete'.$user->getId(), $jsonRequest['csrf'])) {
            return $this->json(['message' => 'Invalid csrf token'], 201);
        }

        $entityManager = $this->getDoctrine()->getManager();

        // Setting all user activities owner to orphan ( null ) in order to keep notes already attributed.
        foreach($user->getActivities() as $activity) {
            $activity->setUser(null);
            $user->removeActivity($activity);
            $entityManager->persist($activity);
            $entityManager->persist($user);
            $entityManager->flush();
        }

        foreach($classroomRepository->findAll() as $classroom) {

            if($classroom->getOwner() === $user) {
                $classroom->setOwner(null);
                $entityManager->persist($classroom);
            }

            if(in_array($user, $classroom->getUsers()->toArray())) {
                $classroom->removeUser($user);
                $entityManager->persist($classroom);
            }
            $entityManager->flush();
        }



        $entityManager->remove($user);
        $entityManager->flush();

        return $this->json(['message' => 'User deleted'], 200);
    }


    /**
     * @Route("/user/profile", name="user_profile")
     *
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function profile(EntityManagerInterface $entityManager, Request $request)
    {
        $userForm = $this->createForm(UserProfileType::class, $this->getUser());

        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid())
        {
            $entityManager->persist($this->getUser());
            $entityManager->flush();
            $this->addFlash('success', 'Successfully updated');
            return $this->redirectToRoute("user_profile");
        }

        return $this->render('users/profile.html.twig', [
            'user' => $this->getUser(),
            'userProfileForm' => $userForm->createView(),
        ]);
    }


    /**
     * @Route("/user/view/classrooms/{id}", name="user_view_classrooms")
     *
     * @param User $user
     * @return Response
     */
    public function viewClasses(User $user)
    {
        return $this->render('classrooms/index.html.twig', [
            'classrooms' => $user->getClassrooms(),
            'redirect' => base64_encode(json_encode([
                'route'  => 'user_view_classrooms',
                'params' => ['id' => $user->getId()],
            ])),
        ]);
    }


    /**
     * @Route("/user/view/students/{id}", name="user_view_students")
     *
     * @param User $user
     * @return Response
     */
    public function viewStudents(User $user)
    {
        return $this->render('students/index.html.twig', [
            'students' => $user->getStudents(),
            'redirect' => base64_encode(json_encode([
                'route'  => 'user_view_students',
                'params' => ['id' => $user->getId()],
            ])),
        ]);
    }

}
