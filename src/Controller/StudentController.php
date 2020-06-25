<?php

namespace App\Controller;

use App\Entity\Student;
use App\Entity\StudentContact;
use App\Entity\StudentContactRelation;
use App\Form\StudentContactType;
use App\Form\StudentExistingContactType;
use App\Form\StudentType;
use App\Repository\StudentRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
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


    /**
     * @Route("/student/view/contacts/{id}", name="student_view_contact")
     *
     * @param Student $student
     * @return Response
     */
    public function viewContacts(Student $student)
    {
        return $this->render('students/contacts.html.twig', [
            'student' => $student,
            'medicalContactsRelations' => $student->getMedicalContactsRelations(),
            'parentsContactsRelations' => $student->getParentsContactsRelations(),
            'otherContactsRelations' => $student->getOtherContactsRelations(),
        ]);
    }


    /**
     * @Route("/student/contact/add/{id}", name="student_add_contact")
     *
     * @param Student $student
     * @param Request $request
     * @return Response
     */
    public function addContact(Student $student, Request $request)
    {
        $relations = StudentContactRelation::getAvailableRelations();
        // Create a form with existing contacts.
        $existingContactsForm = $this->createForm(StudentExistingContactType::class, null, ['relations' => $relations]);
        // Create a form to ass new contacts and attach it to the selected student.
        $newContactForm = $this->createForm(StudentContactType::class, null, ['relations' => $relations]);

        $existingContactsForm->handleRequest($request);
        $newContactForm->handleRequest($request);
        $em = $this->getDoctrine()->getManager();

        // New contact form was submited, so adding a new contact.
        if ($newContactForm->isSubmitted() && $newContactForm->isValid()) {
            $contact = (new StudentContact())
                ->setFirstName($newContactForm->get('firstName')->getData())
                ->setLastName($newContactForm->get('lastName')->getData())
                ->setEmail($newContactForm->get('email')->getData())
                ->setAddress($newContactForm->get('address')->getData())
                ->setPhone($newContactForm->get('phone')->getData())
            ;

            $contactRelation = (new StudentContactRelation())
                ->setStudent($student)
                ->setContact($contact)
                ->setRelation($newContactForm->get('relation')->getData())
                ->setSendSchoolReport($newContactForm->get('schoolReport')->getData())
            ;
            // Checking if contact already exists in database before flushing.
            $scRepository = $this->getDoctrine()->getRepository(StudentContact::class);
            if(!$scRepository->contactExists($contact)) {
                $em->persist($contact);
                $em->persist($contactRelation);
                $em->flush();
                $this->addFlash('success', 'The new contact was added and linked to the student');
            }
            else {
                $this->addFlash('error', 'Error, it sounds like this contact already exists, please use the box to select the contact');
            }

            // Redirect to the selected student.
            return $this->redirectToRoute('student_view_contact', [
                'id' => $student->getId(),
            ]);

        }

        // Existing form was submited, so attaching an existing contact to the student
        if ($existingContactsForm->isSubmitted() && $existingContactsForm->isValid()) {
            // TODO check if the relation between student and contact already exists
            $contact = $existingContactsForm->get('contact')->getData();
            $contactRelation = (new StudentContactRelation())
                ->setStudent($student)
                ->setContact($contact)
                ->setRelation($existingContactsForm->get('relation')->getData())
                ->setSendSchoolReport($existingContactsForm->get('schoolReport')->getData())
            ;

            $scrRepository = $this->getDoctrine()->getRepository(StudentContactRelation::class);
            if(!$scrRepository->contactRelationExists($contact, $student)) {
                $em->persist($contact);
                $em->persist($contactRelation);
                $em->flush();
                $this->addFlash('success', 'A new relation with the selected contact was added to the selected student');
            }
            else {
                $this->addFlash('error', 'It sounds like a relation with this contact already exists for this student');
            }

            // Redirect to the selected student.
            return $this->redirectToRoute('student_view_contact', [
                'id' => $student->getId(),
            ]);
        }

        return $this->render('students/contact-add-form.html.twig', [
            'student' => $student,
            'existingContactsForm' => $existingContactsForm->createView(),
            'newContactForm' => $newContactForm->createView(),
        ]);
    }


    /**
     * @Route("/student/contact/edit/{id}", name="student_edit_contact")
     *
     * @param StudentContactRelation $contactRelation
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function editContact(StudentContactRelation $contactRelation, Request $request)
    {
        $contact = $contactRelation->getContact();
        $contactForm = $this->createForm(StudentContactType::class, null, [
            'relations' => StudentContactRelation::getAvailableRelations(),
            'contactRelation' => $contactRelation,
        ]);

        $contactForm->handleRequest($request);
        $em = $this->getDoctrine()->getManager();

        // New contact form was submited, so adding a new contact.
        if ($contactForm->isSubmitted() && $contactForm->isValid()) {
            $contact
                ->setFirstName($contactForm->get('firstName')->getData())
                ->setLastName($contactForm->get('lastName')->getData())
                ->setEmail($contactForm->get('email')->getData())
                ->setAddress($contactForm->get('address')->getData())
                ->setPhone($contactForm->get('phone')->getData())
            ;

            $contactRelation
                ->setRelation($contactForm->get('relation')->getData())
                ->setSendSchoolReport($contactForm->get('schoolReport')->getData())
            ;

            $em->persist($contact);
            $em->persist($contactRelation);
            $em->flush();
            $this->addFlash('success', 'The contact was updated');


            // Redirect to the selected student.
            return $this->redirectToRoute('student_view_contact', [
                'id' => $contactRelation->getStudent()->getId(),
            ]);

        }

        return $this->render('students/contact-add-form.html.twig', [
            'student' => $contactRelation->getStudent(),
            'newContactForm' => $contactForm->createView(),
        ]);
    }


    /**
     * @Route("/student/contact/delete/{id}", name="student_delete_contact")
     *
     * @param StudentContactRelation $contactRelation
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteContact(StudentContactRelation $contactRelation, Request $request)
    {
        $jsonRequest = json_decode($request->getContent(), true);
        if( !isset($jsonRequest['csrf']) || !$this->isCsrfTokenValid('contact_relation_delete'.$contactRelation->getId(), $jsonRequest['csrf'])) {
            return $this->json(['message' => 'Invalid csrf token'], 201);
        }

        // Removing student contact relation, NOT the contact itself.
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($contactRelation);
        $entityManager->flush();

        return $this->json(['message' => 'Element deleted'], 200);
    }
}
