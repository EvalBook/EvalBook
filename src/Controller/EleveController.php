<?php

namespace App\Controller;

use App\Entity\Eleve;
use App\Form\EleveType;
use App\Repository\EleveRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class EleveController extends AbstractController
{
    /**
     * @Route("/eleves", name="eleves", methods={"GET"})
     * @IsGranted("ROLE_STUDENT_LIST_ALL", statusCode=404, message="Not found")
     *
     * @param EleveRepository $eleveRepository
     * @return Response
     */
    public function index(EleveRepository $eleveRepository): Response
    {
        return $this->render('eleve/index.html.twig', [
            'eleves' => $eleveRepository->findAll(),
        ]);
    }


    /**
     * @Route("/eleve/view/{id}", name="eleve_view")
     *
     * @param Eleve $eleve
     * @return Response
     */
    public function view(Eleve $eleve): Response
    {
        // TODO add student infos as notes and activities...
        return $this->render('eleve/show.html.twig', [
            'eleve' => $eleve,
        ]);
    }


    /**
     * @Route("/eleve/add", name="eleve_add")
     * @IsGranted("ROLE_STUDENT_CREATE", statusCode=404, message="Not found")
     *
     * @param Request $request
     * @return Response
     */
    public function add(Request $request): Response
    {
        $eleve = new Eleve();
        $form = $this->createForm(EleveType::class, $eleve);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($eleve);
            $entityManager->flush();

            return $this->redirectToRoute('eleves');
        }

        return $this->render('eleve/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/eleve/edit/{id}", name="eleve_edit")
     * @IsGranted("ROLE_STUDENT_EDIT", statusCode=404, message="Not found")
     *
     * @param Request $request
     * @param Eleve $eleve
     * @return Response
     */
    public function edit(Request $request, Eleve $eleve): Response
    {
        $form = $this->createForm(EleveType::class, $eleve);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('eleves');
        }

        return $this->render('eleve/form.html.twig', [
            'eleve' => $eleve,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/eleve/delete/{id}", name="eleve_delete", methods={"POST"})
     * @IsGranted("ROLE_STUDENT_DELETE", statusCode=404, message="Not found")
     *
     * @param Request $request
     * @param Eleve $eleve
     * @return Response
     */
    public function delete(Request $request, Eleve $eleve): Response
    {
        $jsonRequest = json_decode($request->getContent(), true);
        if( !isset($jsonRequest['csrf']) || !$this->isCsrfTokenValid('eleve_delete'.$eleve->getId(), $jsonRequest['csrf'])) {
            return $this->json(['message' => 'Invalid csrf token'], 201);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($eleve);
        $entityManager->flush();

        return $this->json(['message' => 'Student deleted'], 200);
    }
}
