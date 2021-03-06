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

use App\Entity\Classroom;
use App\Entity\Student;
use App\Entity\User;
use App\Form\ClassroomType;
use App\Repository\ClassroomRepository;
use App\Repository\ImplantationRepository;
use App\Repository\StudentRepository;
use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ClassroomController extends AbstractController
{

    private $classroomRepository;
    private $implantationRepository;


    /**
     * ClassroomController constructor.
     * @param ClassroomRepository $classroomRepository
     * @param ImplantationRepository $implantationRepository
     */
    public function __construct(ClassroomRepository $classroomRepository, ImplantationRepository $implantationRepository)
    {
        $this->classroomRepository = $classroomRepository;
        $this->implantationRepository = $implantationRepository;
    }


    /**
     * @Route("/classrooms", name="classrooms")
     *
     * @return Response
     */
    public function index(): Response
    {
        $user = $this->getUser();

        // Getting all classes if user has role to view all.
        if ($this->isGranted('ROLE_CLASS_LIST_ALL')) {
            $classrooms = $this->classroomRepository->findAll();
        }
        else {
            // If not, getting classes the user is subscribed to.
            $classrooms = $user->getClassrooms();
        }

        return $this->render('classrooms/index.html.twig', [
            'classrooms' => $classrooms,
        ]);
    }


    /**
     * @Route("/classroom/add", name="classroom_add")
     * @IsGranted("ROLE_CLASS_CREATE", statusCode=404, message="Not found")
     *
     * @param Request $request
     * @return Response
     */
    public function add(Request $request): Response
    {
        if($this->implantationRepository->count([]) > 0) {
            $classroom = new Classroom();
            $form = $this->createForm(ClassroomType::class, $classroom);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                if ($this->classroomRepository->classroomNameAlreadyTaken($classroom)) {
                    $this->addFlash('error', 'Class name already taken in the class implantation');
                }
                else {
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($classroom);
                    $entityManager->flush();

                    return $this->redirectToRoute('classrooms');
                }
            }

            return $this->render('classrooms/form.html.twig', [
                'form' => $form->createView(),
            ]);
        }
        else {
            $this->addFlash('error', "No implantation was found, add a new implantation and try again");
        }

        return $this->redirectToRoute('classrooms');
    }


    /**
     * @Route("/classroom/edit/{id}/{redirect}", name="classroom_edit", defaults={"redirect"=null})
     * @IsGranted("ROLE_CLASS_EDIT", statusCode=404, message="Not found")
     *
     * @param Request $request
     * @param Classroom $classroom
     * @param String|null $redirect
     * @return Response
     */
    public function edit(Request $request, Classroom $classroom, ?String $redirect): Response
    {
        $form = $this->createForm(ClassroomType::class, $classroom);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if($this->classroomRepository->classroomNameAlreadyTaken($classroom)) {
                $this->addFlash('error', 'Class name already taken in the class implantation');
            }
            else {
                $this->getDoctrine()->getManager()->flush();

                if (!is_null($redirect)) {
                    $redirect = json_decode(base64_decode($redirect), true);
                    return $this->redirectToRoute($redirect['route'], $redirect["params"]);
                }

                return $this->redirectToRoute('classrooms');
            }
        }

        return $this->render('classrooms/form.html.twig', [
            'classroom' => $classroom,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/classroom/delete/{id}", name="classroom_delete", methods={"POST"})
     * @IsGranted("ROLE_CLASS_DELETE", statusCode=404, message="Not found")
     *
     * @param Request $request
     * @param Classroom $classroom
     * @return Response
     */
    public function delete(Request $request, Classroom $classroom): Response
    {
        $jsonRequest = json_decode($request->getContent(), true);
        if( !isset($jsonRequest['csrf']) || !$this->isCsrfTokenValid('classroom_delete'.$classroom->getId(), $jsonRequest['csrf'])) {
            return $this->json(['message' => 'Invalid csrf token'], 201);
        }

        $entityManager = $this->getDoctrine()->getManager();
        // Making activities orphans
        foreach($classroom->getActivities()->toArray() as $activity) {
            $activity->detachClassroom();
            $entityManager->flush();
        }

        $entityManager->remove($classroom);
        $entityManager->flush();
        return $this->json(['message' => 'Class deleted'], 200);

    }


    /**
     * @Route("/classroom/users/{id}", name="classroom_manage_users")
     * @IsGranted("ROLE_CLASS_EDIT_USERS", statusCode=404, message="Not found")
     *
     * @param UserRepository $userRepository
     * @param Request $request
     * @param Classroom $classroom
     * @return RedirectResponse|Response
     */
    public function manageClassroomUsers(UserRepository $userRepository, Request $request, Classroom $classroom)
    {
        $formUsers = $userRepository->findAll();
        // Removing owner as it has full rights on his class.
        $owner = $classroom->getOwner();
        if($owner && in_array($owner, $formUsers)) {
            if($key = array_search($owner, $formUsers))
                unset($formUsers[$key]);
        }

        $form = $this->createFormBuilder([
                'users' => $classroom->getUsers(),
              ])

              ->add('users', EntityType::class, [
                  'class' => User::class,
                  'multiple' => true,
                  'expanded' => true,
                  // !! Remember this to force storing without refernce^^
                  'by_reference' => false,
                  'choices' => $formUsers,
              ])

              // Submit button.
              ->add('submit', SubmitType::class)
              ->getForm()
        ;

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Getting posted information.
            $users = $form["users"]->getData()->toArray();

            if(!is_null($users)) {
                $em = $this->getDoctrine()->getManager();
                $classroom->setUsers($users);
                $em->persist($classroom);
                $em->flush();
            }

            return $this->redirectToRoute('classrooms');
        }

        return $this->render('classrooms/form-manage-users.html.twig', [
            'classroom' => $classroom,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/classroom/students/{id}", name="classroom_manage_students")
     * @IsGranted("ROLE_CLASS_EDIT_STUDENTS", statusCode=404, message="Not found")
     *
     * @param Request $request
     * @param Classroom $classroom
     * @param StudentRepository $studentRepository
     * @return RedirectResponse|Response
     */
    public function manageClassroomStudents(Request $request, Classroom $classroom, StudentRepository $studentRepository)
    {
        $students = $studentRepository->findAll();
        // If the classroom is owned ( titulaire ), then getting ALL this classroom students + unassigned students.
        if($classroom->getOwner() !== null) {
            $students = array_filter($students, function($student) {
                $stClassrooms = $student->getClassrooms();
                foreach($stClassrooms as $classroom) {
                    if($classroom->getOwner() !== null) {
                        return false;
                    }
                }
                return true;
            });
           // Final students array containing the classroom students and the filtered unassigned students.
           $students = array_merge($students, $classroom->getStudents()->toArray());
        }

        $form = $this->createFormBuilder(['students' => $classroom->getStudents()])

            ->add('students', EntityType::class, [
                'class' => Student::class,
                'multiple' => true,
                'expanded' => true,
                'by_reference' => false,
                'choices' => $students,
            ])

            // Submit button.
            ->add('submit', SubmitType::class)
            ->getForm()
        ;

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $students = $form->getData()["students"]->toArray();
            if(!is_null($students)) {
                $em = $this->getDoctrine()->getManager();
                $classroom->setStudents($students);
                $em->persist($classroom);
                $em->flush();
            }

            return $this->redirectToRoute('classrooms');
        }

        return $this->render('classrooms/form-manage-students.html.twig', [
            'classroom' => $classroom,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/classroom/view/users/{id}", name="classroom_view_users")
     *
     * @param Classroom $classroom
     * @return Response
     */
    public function viewClassroomUsers(Classroom $classroom)
    {
        return $this->render('users/index.html.twig', [
           'users'   => $classroom->getUsers(),
           'redirect' => base64_encode(json_encode([
               'route'  => 'classroom_view_users',
               'params' => ['id' => $classroom->getId()],
           ])),
        ]);
    }


    /**
     * @Route("/classroom/view/students/{id}", name="classroom_view_students")
     *
     * @param Classroom $classroom
     * @return Response
     */
    public function viewClassroomStudents(Classroom $classroom)
    {
        return $this->render('students/index.html.twig', [
            'students' => $classroom->getStudents(),
            'redirect' => base64_encode(json_encode([
                'route'  => 'classroom_view_students',
                'params' => ['id' => $classroom->getId()],
            ])),
        ]);
    }

}