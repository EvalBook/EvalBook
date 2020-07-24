<?php

namespace App\Controller;

use App\Entity\NoteType;
use App\Form\NoteTypeType;
use App\Repository\NoteTypeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NoteTypeController extends AbstractController
{

    /**
     * @Route("/note_type/add/{classroom}", name="note_type_add", defaults={"classroom"=null})
     *
     * @param NoteTypeRepository $repository
     * @param Request $request
     * @param int|null $classroom
     * @return Response
     */
    public function add(NoteTypeRepository $repository, Request $request, ?int $classroom): Response
    {
        // All new note type will be globally added and no deletion is available for now

        $noteType = new NoteType();
        $form = $this->createForm(NoteTypeType::class, $noteType);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($noteType);
            $entityManager->flush();
            $this->addFlash('success', 'Successfully updated');

            if(!is_null($classroom)) {
                return $this->redirectToRoute('activity_add', [
                    'classroom' => $classroom,
                ]);
            }
        }

        return $this->render('note_type/form.html.twig', [
            'availableNoteTypes' => $repository->findAll(),
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/note_type/edit/{id}", name="note_type_edit")
     *
     * @param NoteTypeRepository $repository
     * @param NoteType $noteType
     * @return Response
     */
    public function edit(NoteTypeRepository $repository, NoteType $noteType)
    {
        // All new note type will be globally added.
        return $this->render('note_type/form.html.twig', [
            'availableNoteTypes' => $repository->findAll(),
        ]);
    }


    public function delete()
    {
        // Deletion only available if no activities are using the note type.
        // Default note types can be deleted if they are not used.
    }
}
