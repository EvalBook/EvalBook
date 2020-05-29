<?php

namespace App\Controller;

use App\Entity\Student;
use App\Form\StudentType;
use App\Repository\StudentRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;


class StudentController extends AbstractController
{
    /**
     * @Route("/students", name="students", methods={"GET"})
     *
     * @param StudentRepository $studentRepository
     * @param Security $security
     * @return Response
     */
    public function index(StudentRepository $studentRepository, Security $security): Response
    {
        $user = $security->getUser();

        // Getting all classes students if user has role to view all.
        if($security->isGranted('ROLE_STUDENT_LIST_ALL')) {
            $students = $studentRepository->findAll();
        }
        // If not, getting classes the user is subscribed to.
        else {
            $students = array();
            foreach($user->getClassrooms() as $classroom) {
                $students = array_merge($students, $classroom->getStudents()->toArray());
            }
        }

        return $this->render('students/index.html.twig', [
            'students' => array_unique($students),
        ]);
    }


    /**
     * @Route("/student/add", name="student_add")
     * @IsGranted("ROLE_STUDENT_CREATE", statusCode=404, message="Not found")
     *
     * @param Request $request
     * @return Response
     */
    public function add(Request $request): Response
    {
        $student = new Student();
        $form = $this->createForm(StudentType::class, $student);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($student);
            $entityManager->flush();

            return $this->redirectToRoute('students');
        }

        return $this->render('students/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/student/edit/{id}/{redirect}", name="student_edit", defaults={"redirect"=null})
     * @IsGranted("ROLE_STUDENT_EDIT", statusCode=404, message="Not found")
     *
     * @param Request $request
     * @param Student $student
     * @param String|null $redirect
     * @return Response
     */
    public function edit(Request $request, Student $student, ?String $redirect): Response
    {
        $form = $this->createForm(StudentType::class, $student);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            if(!is_null($redirect)) {
                $redirect = json_decode(base64_decode($redirect), true);
                return $this->redirectToRoute($redirect['route'], $redirect["params"]);
            }

            return $this->redirectToRoute('students');
        }

        return $this->render('students/form.html.twig', [
            'student' => $student,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/student/delete/{id}", name="student_delete", methods={"POST"})
     * @IsGranted("ROLE_STUDENT_DELETE", statusCode=404, message="Not found")
     *
     * @param Request $request
     * @param Student $student
     * @return Response
     */
    public function delete(Request $request, Student $student): Response
    {
        $jsonRequest = json_decode($request->getContent(), true);
        if( !isset($jsonRequest['csrf']) || !$this->isCsrfTokenValid('student_delete'.$student->getId(), $jsonRequest['csrf'])) {
            return $this->json(['message' => 'Invalid csrf token'], 201);
        }

        $entityManager = $this->getDoctrine()->getManager();
        // Deleting all student notes too.
        foreach($student->getNotes() as $note) {
            $entityManager->remove($note);
        }
        $entityManager->flush();

        // Removing student.
        $entityManager->remove($student);
        $entityManager->flush();

        return $this->json(['message' => 'Student deleted'], 200);
    }


    /**
     * @Route("/student/view/classrooms/{id}", name="student_view_classrooms")
     *
     * @param Student $student
     * @return Response
     */
    public function viewClassrooms(Student $student)
    {
        return $this->render('classrooms/index.html.twig', [
            'classrooms' => $student->getClassrooms(),
            'redirect' => base64_encode(json_encode([
                'route'  => 'student_view_classrooms',
                'params' => ['id' => $student->getId()],
            ])),
        ]);
    }
}
