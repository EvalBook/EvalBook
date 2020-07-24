<?php

/**
 * Copyleft (c) 2020 EvalBook
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the European Union Public Licence (EUPL V 1.2),
 * version 1.2 (or any later version).
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * European Union Public Licence for more details.
 *
 * You should have received a copy of the European Union Public Licence
 * along with this program. If not, see
 * https://joinup.ec.europa.eu/collection/eupl/eupl-text-eupl-12
 **/

namespace App\Controller;

use App\Entity\Classroom;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NoteBookController extends AbstractController
{
    /**
     * @Route("/notebook/classroom/{classroom}", name="note_book_view")
     *
     * @param Classroom $classroom
     * @return Response
     */
    public function viewNotebook(Classroom $classroom)
    {
        $this->checkClassroomAccesses($classroom);

        return $this->render('note_book/notebook.html.twig', [
            'classroom' => $classroom,
            'notebook'  => $this->constructNotebook($classroom),
            'periods'   => $this->getNotebookPeriods($classroom),
        ]);
    }


    /**
     * @Route("/notebook/classrooms", name="note_book_select_classroom")
     *
     * @return Response
     */
    public function index()
    {
        return $this->render('note_book/index.html.twig', [
            'classrooms' => $this->getUser()->getClassrooms(),
        ]);
    }


    /**
     * Generate a more 'workable' notebook for template.
     *
     * @param Classroom $classroom
     * @return array
     */
    public function constructNotebook(Classroom $classroom)
    {
        $notebook = array();

        foreach($classroom->getStudents() as $student) {
            foreach($classroom->getActivities() as $activity) {
                $max = $activity->getNoteType()->getMaximum();
                $note = !is_null($student->getNote($activity)) ? $student->getNote($activity) . " / $max" : '-';
                $notebook[$student->getLastName() . ' ' . $student->getFirstName()][] = $note;
            }
        }

        return $notebook;
    }


    /**
     * Return available periods for notebook.
     *
     * @param Classroom $classroom
     * @return array
     */
    public function getNotebookPeriods(Classroom $classroom)
    {
        $periods = array();
        foreach($classroom->getActivities() as $activity) {
            $periods[] = $activity->getPeriod()->getName();
        }
        return array_count_values($periods);
    }


    /**
     * Check the provided class user access and throw access denied if user does not have rights to see it.
     * @param Classroom|null $classroom
     */
    private function checkClassroomAccesses(?Classroom $classroom)
    {
        // If user is not allowed to use the class, then return a 405
        if(!is_null($classroom)) {
            if (!$this->getUser() === $classroom->getOwner() || !in_array($this->getUser(), $classroom->getUsers()->toArray())) {
                throw $this->createAccessDeniedException();
            }
        }
    }
}
