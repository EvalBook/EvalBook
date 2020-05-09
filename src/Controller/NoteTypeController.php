<?php

namespace App\Controller;

use App\Entity\NoteType;
use App\Form\NoteTypeType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class NoteTypeController extends AbstractController
{
    /**
     * @Route("/note/type/add", name="note_type_add")
     *
     * @param Request $request
     * @return Response
     */
    public function add(Request $request): Response
    {
        $noteType = new NoteType();
        $form = $this->createForm(NoteTypeType::class, $noteType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($noteType);
            $entityManager->flush();

            return $this->redirectToRoute('note_type_index');
        }

        return $this->render('note_type/form.html.twig', [
            'note_type' => $noteType,
            'form' => $form->createView(),
        ]);
    }
}
