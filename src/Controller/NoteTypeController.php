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
     * @Route("/note_type/add", name="note_type_add")
     *
     * @param NoteTypeRepository $repository
     * @return Response
     */
    public function add(NoteTypeRepository $repository, Request $request)
    {
        $noteType = new NoteType();
        $form = $this->createForm(NoteTypeType::class, $noteType);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            dd("This form is valid");
            // If not type not already exisist... continue.
        }

        // All new note type will be globally added.
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
