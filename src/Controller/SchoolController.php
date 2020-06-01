<?php

namespace App\Controller;

use App\Entity\School;
use App\Form\SchoolType;
use App\Repository\SchoolRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class SchoolController extends AbstractController
{
    /**
     * @Route("/schools", name="schools")
     * @IsGranted("ROLE_SCHOOL_LIST_ALL", statusCode=404, message="Not found")
     *
     * @param SchoolRepository $repository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(SchoolRepository $repository)
    {
        return $this->render('schools/index.html.twig', [
            'schools' => $repository->findAll(),
        ]);
    }


    /**
     * @Route("/school/add", name="school_add")
     * @IsGranted("ROLE_SCHOOL_CREATE", statusCode=404, message="Not found")
     *
     * @param Request $request
     * @param SchoolRepository $repository
     * @return RedirectResponse|Response
     */
    public function add(Request $request, SchoolRepository $repository)
    {
        $school = new School();
        $form = $this->createForm(SchoolType::class, $school);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if($repository->count(['name' => $school->getName()]) > 0) {
                $this->addFlash('error', 'A school with this name already exists');
            }
            else {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($school);
                $entityManager->flush();
                $this->addFlash('success', 'Successfully added');
                return $this->redirectToRoute('schools');
            }
        }

        return $this->render('schools/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/school/edit/{id}", name="school_edit")
     * @IsGranted("ROLE_SCHOOL_EDIT", statusCode=404, message="Not found")
     *
     * @param School $school
     * @param Request $request
     * @param SchoolRepository $repository
     * @return RedirectResponse|Response
     */
    public function edit(School $school, Request $request, SchoolRepository $repository)
    {
        $form = $this->createForm(SchoolType::class, $school);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if($repository->schoolNameAlreadyTaken($school)) {
                $this->addFlash('error', 'Name already taken');
            }
            else {
                $this->getDoctrine()->getManager()->flush();
                $this->addFlash('success', 'Successfully updated');

                return $this->redirectToRoute('schools');
            }
        }

        return $this->render('schools/form.html.twig', [
            'school' => $school,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/school/delete/{id}", name="school_delete", methods={"POST"})
     * @IsGranted("ROLE_SCHOOL_DELETE", statusCode=404, message="Not found")
     *
     * @param ImplantationController $implController
     * @param School $school
     * @param Request $request
     * @return JsonResponse
     */
    public function delete(ImplantationController $implController, School $school, Request $request)
    {
        $jsonRequest = json_decode($request->getContent(), true);
        if( !isset($jsonRequest['csrf']) || !$this->isCsrfTokenValid('school_delete'.$school->getId(), $jsonRequest['csrf'])) {
            return $this->json(['message' => 'Invalid csrf token'], 201);
        }

        $entityManager = $this->getDoctrine()->getManager();

        foreach($school->getImplantations()->toArray() as $implantation) {
            $implController->delete($implantation, $request, false);
        }
        $entityManager->remove($school);
        $entityManager->flush();

        return $this->json(['message' => 'school deleted'], 200);
    }


    /**
     * @Route("/school/view/implantations/{id}", name="school_view_implantations")
     * @IsGranted("ROLE_IMPLANTATION_LIST_ALL", statusCode=404, message="Not found")
     *
     * @param School $school
     * @return Response
     */
    public function viewImplantations(School $school)
    {
        return $this->render('implantations/index.html.twig', [
            'implantations' => $school->getImplantations(),
            'redirect' => base64_encode(json_encode([
                'route'  => 'school_view_implantations',
                'params' => ['id' => $school->getId()],
            ])),
        ]);
    }

}
