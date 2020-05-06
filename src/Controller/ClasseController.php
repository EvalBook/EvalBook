<?php

namespace App\Controller;

use App\Entity\Classe;
use App\Entity\Eleve;
use App\Entity\User;
use App\Form\ClasseType;
use App\Repository\ClasseRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;


class ClasseController extends AbstractController
{
    /**
     * @Route("/classes", name="classes")
     *
     * @param ClasseRepository $classeRepository
     * @param Security $security
     * @return Response
     */
    public function index(ClasseRepository $classeRepository, Security $security): Response
    {
        $user = $security->getUser();

        // Getting all classes if user has role to view all.
        if(in_array('ROLE_CLASS_LIST_ALL', $user->getRoles()) || in_array('ROLE_ADMIN', $user->getRoles()))
            $classes = $classeRepository->findAll();
        // If not, getting classes the user is subscribed to.
        else
            $classes = $user->getClasses();

        return $this->render('classe/index.html.twig', [
            'classes' => $classes,
        ]);
    }


    /**
     * @Route("/classe/add", name="classe_add")
     *
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $classe = new Classe();
        $form = $this->createForm(ClasseType::class, $classe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($classe);
            $entityManager->flush();

            return $this->redirectToRoute('classes');
        }

        return $this->render('classe/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/classe/view/{id}", name="classe_view")
     *
     * @param Classe $classe
     * @return Response
     */
    public function show(Classe $classe): Response
    {
        return $this->render('classe/show.html.twig', [
            'classe' => $classe,
        ]);
    }

    /**
     * @Route("/classe/edit/{id}", name="classe_edit")
     *
     * @param Request $request
     * @param Classe $classe
     * @return Response
     */
    public function edit(Request $request, Classe $classe): Response
    {
        $form = $this->createForm(ClasseType::class, $classe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('classes');
        }

        return $this->render('classe/form.html.twig', [
            'classe' => $classe,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/classe/delete/{id}", name="classe_delete", methods={"POST"})
     *
     * @param Request $request
     * @param Classe $classe
     * @return Response
     */
    public function delete(Request $request, Classe $classe): Response
    {
        $jsonRequest = json_decode($request->getContent(), true);
        if( !isset($jsonRequest['csrf']) || !$this->isCsrfTokenValid('classe_delete'.$classe->getId(), $jsonRequest['csrf'])) {
            return $this->json(['message' => 'Invalid csrf token'], 201);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($classe);
        $entityManager->flush();

        return $this->json(['message' => 'Classe deleted'], 200);
    }


    /**
     * @Route("/classe/users/{id}", name="classe_manage_users")
     *
     * @param Request $request
     * @param Classe $classe
     */
    public function manageClassUsers(Request $request, Classe $classe)
    {
        $form = $this->createFormBuilder([
                'users' => $classe->getUsers(),
              ])

              ->add('users', EntityType::class, [
                  'class' => User::class,
                  'multiple' => true,
                  'expanded' => true,
              ])

              // Submit button.
              ->add('submit', SubmitType::class)

              ->getForm()
        ;

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Getting posted information.
            $users = $form->getData()["users"];
            if(!is_null($users) && count($users) > 0) {
                foreach ($users as $user) {
                    $user->addClasse($classe);
                }
                // Saving added users.
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
            }

            return $this->redirectToRoute('classes');
        }

        return $this->render('classe/form-manage-users.html.twig', [
            'classe' => $classe,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/classe/students/{id}", name="classe_manage_students")
     *
     * @param Request $request
     * @param Classe $classe
     */
    public function manageClassStudents(Request $request, Classe $classe)
    {
        $form = $this->createFormBuilder([
            'students' => $classe->getEleves(),
        ])

            ->add('students', EntityType::class, [
                'class' => Eleve::class,
                'multiple' => true,
                'expanded' => true,
            ])

            // Submit button.
            ->add('submit', SubmitType::class)

            ->getForm()
        ;

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $students = $form->getData()["students"];
            if(!is_null($students) && count($students) > 0) {
                foreach ($students as $student) {
                    $student->addClasse($classe);
                }
                // Saving added users.
                $em = $this->getDoctrine()->getManager();
                $em->persist($student);
                $em->flush();
            }

            return $this->redirectToRoute('classes');
        }

        return $this->render('classe/form-manage-students.html.twig', [
            'classe' => $classe,
            'form' => $form->createView(),
        ]);
    }
}
