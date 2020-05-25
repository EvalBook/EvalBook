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

use App\Entity\Classe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NoteBookController extends AbstractController
{
    /**
     * @Route("/notebook/classe/{classe}", name="note_book_view")
     *
     * @param Classe $classe
     * @return Response
     */
    public function viewNotebook(Classe $classe)
    {
        $this->checkClassAccesses($classe);

        return $this->render('note_book/notebook.html.twig', [
            'classe'  => $classe,
            'notebook' => $this->constructNotebook($classe),
            'periodes' => $this->getNotebookPeriods($classe),
        ]);
    }


    /**
     * @Route("/notebook/classes", name="note_book_select_class")
     *
     * @return Response
     */
    public function index()
    {
        return $this->render('note_book/index.html.twig', [
            'classes' => $this->getUser()->getClasses(),
        ]);
    }


    /**
     * Generate a more 'workable' notebook for template.
     *
     * @param Classe $classe
     * @return array
     */
    public function constructNotebook(Classe $classe)
    {
        $notebook = array();

        foreach($classe->getEleves() as $eleve) {
            foreach($classe->getActivites() as $activity) {
                $max = strtoupper($activity->getNoteType()->getMax());
                $note = !is_null($eleve->getNote($activity)) ? $eleve->getNote($activity) . " / $max" : '-';
                $notebook[$eleve->getLastName() . ' ' . $eleve->getFirstName()][] = $note;
            }
        }

        return $notebook;
    }


    /**
     * Return available periods for notebook.
     *
     * @param Classe $classe
     * @return array
     */
    public function getNotebookPeriods(Classe $classe)
    {
        $periods = array();
        foreach($classe->getActivites() as $activite) {
            $periods[] = $activite->getPeriode()->getName();
        }
        return array_count_values($periods);
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
}
