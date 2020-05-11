<?php

namespace App\Controller;

use App\Entity\Classe;
use App\Entity\Eleve;
use App\Entity\User;
use App\Form\ClasseType;
use App\Repository\ClasseRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
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
     * @Route("/")
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
     * @IsGranted("ROLE_CLASS_CREATE", statusCode=404, message="Not found")
     *
     * @param Request $request
     * @return Response
     */
    public function add(Request $request): Response
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
     * @Route("/classe/edit/{id}/{redirect}", name="classe_edit", defaults={"redirect"=null})
     * @IsGranted("ROLE_CLASS_EDIT", statusCode=404, message="Not found")
     *
     * @param Request $request
     * @param Classe $classe
     * @return Response
     */
    public function edit(Request $request, Classe $classe, ?String $redirect): Response
    {
        $form = $this->createForm(ClasseType::class, $classe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            if(!is_null($redirect)) {
                $redirect = json_decode(base64_decode($redirect), true);
                return $this->redirectToRoute($redirect['route'], $redirect["params"]);
            }

            return $this->redirectToRoute('classes');
        }

        return $this->render('classe/form.html.twig', [
            'classe' => $classe,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/classe/delete/{id}", name="classe_delete", methods={"POST"})
     * @IsGranted("ROLE_CLASS_DELETE", statusCode=404, message="Not found")
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

        // Checking if the class has activities in it.
        if(!($classe->getActivites()) > 0) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($classe);
            $entityManager->flush();
            return $this->json(['message' => 'Classe deleted'], 200);
        }

        return $this->json(['error' => true], 200);
    }


    /**
     * @Route("/classe/users/{id}", name="classe_manage_users")
     * @IsGranted("ROLE_CLASS_EDIT_USERS", statusCode=404, message="Not found")
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
     * @IsGranted("ROLE_CLASS_EDIT_STUDENTS", statusCode=404, message="Not found")
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


    /**
     * @Route("/classe/view/users/{id}", name="classe_view_users")
     *
     * @param Classe $classe
     * @return Response
     */
    public function viewClassUsers(Classe $classe)
    {
        return $this->render('users/index.html.twig', [
           'users'   => $classe->getUsers(),
           'redirect' => base64_encode(json_encode([
               'route'  => 'classe_view_users',
               'params' => ['id' => $classe->getId()],
           ])),
        ]);
    }


    /**
     * @Route("/classe/view/students/{id}", name="classe_view_students")
     *
     * @param Classe $classe
     * @return Response
     */
    public function viewClassStudents(Classe $classe)
    {
        return $this->render('eleve/index.html.twig', [
            'eleves' => $classe->getEleves(),
            'redirect' => base64_encode(json_encode([
                'route'  => 'classe_view_students',
                'params' => ['id' => $classe->getId()],
            ])),
        ]);
    }

}