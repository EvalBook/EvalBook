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

namespace App\Controller\Api;

header("Access-Control-Allow-Origin: *");

use App\Entity\Note;
use App\Entity\Student;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;


class SchoolReportApi extends AbstractController
{
    /**
     * @Route("/school/report/individual/{student}", name="school_report_individual")
     * @param Student $student
     */
    public function getIndividualSchoolReport(Student $student)
    {
        $notes = $student->getNotes()->toArray();
        $notes = array_filter($notes, function(Note $note){
            $period = $note->getActivity()->getPeriod();
            $now = date('now');
            // if end date >= now and start_date <= now
            return $period->getDateEnd() >= $now && $period->getDateStart() <= $now;
        });

        dd($notes);
        $result = [];
        if(count($notes) > 0) {
            $result = $this->compute($notes);
        }

        $view = $this->renderView('school_report/school-report.html.twig', [
            'student' => $student,
        ]);


        return new JsonResponse([
            'message' => 'test',
            'html' => $view,
        ], 200);
    }


    /**
     * Compute the student notes.
     * @param array $notes
     */
    private function compute(array $notes)
    {

    }
}