<?php

namespace App\Controller;

use App\Entity\Activite;
use App\Entity\Classe;
use App\Entity\Note;
use App\Form\ActiviteNotesType;
use App\Form\ActiviteType;
use App\Repository\ActiviteRepository;
use App\Repository\NoteTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ActivityController extends AbstractController
{
    /**
     * @Route("/activites", name="activites")
     *
     * @param ActiviteRepository $activiteRepository
     * @return Response
     */
    public function index(ActiviteRepository $activiteRepository): Response
    {
        $activities = array();
        foreach($this->getUser()->getClasses() as $classe) {
            $activities = array_merge($activities, $classe->getActivites()->toArray());
        }
        // Setting activities with the right order ( by date descending ).
        usort($activities, function(Activite $one, Activite $two) {
            return $one->getDateAdded() < $two->getDateAdded();
        });

        // Getting the user activities.
        return $this->render('activities/index.html.twig', [
            'classes' => $this->getUser()->getClasses(),
            'activites' => $activities,
        ]);
    }

    /**
     * @Route("/activite/add/{classe}", defaults={"classe"=null}, name="activite_add")
     *
     * @param Classe|null $classe
     * @param Request $request
     * @param NoteTypeRepository $noteTypeRepository
     * @return Response
     */
    public function add(?Classe $classe, Request $request, NoteTypeRepository $noteTypeRepository): Response
    {
        // If user is not allowed to use the class, then return a 405
        $this->checkClassAccesses($classe);

        // If no implantation was specified, then go to the implantation / school pair selection.
        if(is_null($classe)) {
            return $this->render('activities/select-class.html.twig', [
                'classes' => $this->getUser()->getClasses(),
            ]);
        }

        if($noteTypeRepository->count([]) === 0) {
            // No not type found, then populating database with the default ones.
            $noteTypeRepository->populate();
        }
        $activite = new Activite();
        $periodes = array();

        foreach( $classe->getImplantation()->getPeriodes() as $periode) {
            // dd($periode->getDateEnd(), date('now'));
            if($periode->getDateEnd() > new \DateTime('now')) {
                $periodes[] = $periode;
            }
        }
        $form = $this->createForm(ActiviteType::class, $activite, [
            'periodes' => $periodes,
        ]);

        $form->handleRequest($request);
        // Make sur to override any other values sent by request.
        $activite->setClasse($classe);
        $activite->setUser($this->getUser());

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($activite);
            $entityManager->flush();

            return $this->redirectToRoute('activites');
        }

        return $this->render('activities/form.html.twig', [
            'activite' => $activite,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/activite/edit/{id}", name="activite_edit")
     *
     * @param Request $request
     * @param Activite $activite
     * @return Response
     */
    public function edit(Request $request, Activite $activite): Response
    {
        // Throw a 403 error if user can't edit the activity.
        $this->checkActivityAccesses($activite);

        // If activity period target is passed, then redirect to activities list.
        if($activite->getPeriode()->getDateEnd() < new \DateTime())
            return $this->redirectToRoute('activites');

        $periodes = array();

        foreach( $activite->getClasse()->getImplantation()->getPeriodes() as $periode) {

            if($periode->getDateEnd() > new \DateTime('now')) {
                $periodes[] = $periode;
            }
        }

        $form = $this->createForm(ActiviteType::class, $activite, [
            'periodes' => $periodes,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Checking the note type attributed to the activity if notes were provided.
            if( count($activite->getNotes()) > 0) {
                // Then checking agin notes and redirect if the pattern does not match the previously provided notes.
                foreach ($activite->getNotes() as $note) {

                    if(!$note->isValid($activite->getNoteType())) {
                        $this->addFlash('error', 'Please, update the notes you provided as you changed the activity note type');
                        return $this->redirectToRoute('activite_note_add', [
                            'id' => $activite->getId(),
                        ]);
                    }
                }
            }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('activites');
        }

        return $this->render('activities/form.html.twig', [
            'activite' => $activite,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/activite/delete/{id}", name="activite_delete", methods={"POST"})
     *
     * @param Request $request
     * @param Activite $activite
     * @return Response
     */
    public function delete(Request $request, Activite $activite): Response
    {
        // Throw a 403 error if user can't delete this activity ( not the owner ).
        $this->checkActivityAccesses($activite);

        // If activity period target is passed, then redirect to activities list.
        if($activite->getPeriode()->getDateEnd() < new \DateTime())
            return $this->redirectToRoute('activites');

        $jsonRequest = json_decode($request->getContent(), true);
        if( !isset($jsonRequest['csrf']) || !$this->isCsrfTokenValid('activite_delete'.$activite->getId(), $jsonRequest['csrf'])) {
            return $this->json(['message' => 'Invalid csrf token'], 201);
        }

        $entityManager = $this->getDoctrine()->getManager();

        // Deleting notes attached to this activity.
        foreach($activite->getNotes() as $note) {
            $entityManager->remove($note);
        }

        $entityManager->remove($activite);
        $entityManager->flush();

        return $this->json(['message' => 'Activity deleted'], 200);
    }


    /**
     * @Route("/activite/note/add/{id}", name="activite_note_add")
     *
     * @param Activite $activite
     * @param Request $request
     * @return Response
     */
    public function addNotes(Activite $activite, Request $request)
    {
        // Throw a 403 error if user can't add notes to this activity ( not the owner ).
        $this->checkActivityAccesses($activite);

        // If activity period target is passed, then redirect to activities list.
        if($activite->getPeriode()->getDateEnd() < new \DateTime())
            return $this->redirectToRoute('activites');

        // To make sure the user how is inserting notes is the activity owner.
        if(!$activite->getUser() === $this->getUser())
            return $this->redirectToRoute('activites');

        foreach($activite->getClasse()->getEleves() as $student) {
            // If student was not be noted for this activity, then setting a new note for him.
            if(!$student->hasNoteFor($activite)) {
                $note = new Note();
                $note->setEleve($student);
                $activite->addNote($note);
            }
        }

        $form = $this->createForm(ActiviteNotesType::class, $activite);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Checking provided notes format.
            foreach($activite->getNotes() as $note) {
                if(!$note->isValid($activite->getNoteType())) {
                    $this->addFlash('error', 'The notes you provided does not match the note type pattern of activity !');

                    return $this->render('activities/notes-add.html.twig', [
                        'activity' => $activite,
                        'students' => $activite->getClasse()->getEleves(),
                        'form' => $form->createView(),
                    ]);
                }
            }

            // Then register updated information.
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('activites', [
                'id' => $activite->getId(),
            ]);
        }

        return $this->render('activities/notes-add.html.twig', [
            'activity' => $activite,
            'students' => $activite->getClasse()->getEleves(),
            'form' => $form->createView(),
        ]);
    }


    /**
     * Check the provided class user acces and throw access denied if user does not have rights to see it.
     * @param Classe|null $classe
     */
    private function checkClassAccesses(?Classe $classe)
    {
        // If user is not allowed to use the class, then return a 405
        if(!is_null($classe)) {
            if (!$this->getUser() === $classe->getTitulaire() || !in_array($this->getUser(), $classe->getUsers()->toArray())) {
                throw $this->createAccessDeniedException();
            }
        }
    }


    /**
     * Check activity accesses and throw 403 error if an other user is trying to edit an activity.
     * @param Activite|null $activity
     */
    private function checkActivityAccesses(?Activite $activity)
    {
        if($activity->getUser() !== $this->getUser())
            throw $this->createAccessDeniedException();
    }

}

