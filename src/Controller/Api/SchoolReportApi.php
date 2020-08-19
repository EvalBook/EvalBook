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

use App\Entity\ActivityTheme;
use App\Entity\ActivityThemeDomain;
use App\Entity\ActivityThemeDomainSkill;
use App\Entity\Implantation;
use App\Entity\Note;
use App\Entity\Period;
use App\Entity\Student;
use App\Repository\PeriodRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;


class SchoolReportApi extends AbstractController
{
    /**
     * @Route("/school/report/individual/{student}/{implantation}", name="school_report_individual")
     * @ParamConverter("student", class="App\Entity\Student")
     * @ParamConverter("implantation", class="App\Entity\Implantation")
     *
     * @param Student $student
     * @param Implantation $implantation
     * @param PeriodRepository $periodRepository
     * @return JsonResponse
     */
    public function getIndividualSchoolReport(Student $student, Implantation $implantation, PeriodRepository $periodRepository){
        $notes = [];

        // Getting closed periods.
        $periods = $implantation->getPeriods();
        $periods = array_filter($periods->toArray(), function(Period $period) {
            return $period->getDateStart() < new \DateTime('now');
        });

        // Filtering notes by periods.
        foreach($student->getNotes() as $note) {
            if(in_array($note->getActivity()->getPeriod()->getId(), array_map(function(Period $period){return $period->getId();}, $periods))) {
                $notes[] = $note;
            }
        }

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
     * @return array
     */
    private function compute(array $notes)
    {
        $themeRepository  = $this->getDoctrine()->getRepository(ActivityTheme::class);
        $domainRepository = $this->getDoctrine()->getRepository(ActivityThemeDomain::class);
        $skillRepository  = $this->getDoctrine()->getRepository(ActivityThemeDomainSkill::class);

        $result = [];
        // Sorting the whole notes.
        foreach($notes as $note) {
            /* @var $note Note */
            // Getting target theme.
            $theme = $themeRepository->findOneBy([
                'id' => $note->getActivity()->getActivityThemeDomainSkill()->getActivityThemeDomain()->getActivityTheme()
            ]);

            // Getting target domain.
            $domain = $domainRepository->findOneBy([
                'id' => $note->getActivity()->getActivityThemeDomainSkill()->getActivityThemeDomain()
            ]);

            // Getting target skill.
            $skill = $skillRepository->findOneBy([
                'id' => $note->getActivity()->getActivityThemeDomainSkill()
            ]);

            $result[$theme->getDisplayName()][$domain->getDisplayName()][$skill->getId()][] = [
                'note'  => $note,
                'skill' => $skill,
            ];
        }

        // Compute total of notes.
        $skills = [];
        // From theme
        foreach($result as $key => $themes) {
            // From domaine
            foreach($themes as $key2 => $domains) {
                // From skills
                foreach($domains as $key3 => $skills ) {
                    // FIXME pas le bon moyen de récuper ca !!
                    if($skills['skill']->getNoteType()->isNumeric()) {
                        // m = (2 × 1 + 9 × 2 + 27 × 3) / (10 × 1 + 15 × 2 + 30 × 3) × 20
                        $amount = 0;
                        $supp = 0;

                        foreach ($skills as $key4 => $endpoint) {
                            //echo "$key4 => " . $note->getNote() . "\n";
                            $max = $endpoint['note']->getActivity()->getNoteType()->getMaximum();
                            $coefficient = $endpoint['note']->getActivity()->getNoteType()->getCoefficient();
                            $amount += $endpoint['note']->getNote() * $coefficient;
                            $supp += $max * $coefficient;
                        }

                        $m = ($amount / $supp) * $endpoint['skill']->getNoteType()->getMaximum();
                        $m = $this->round_up($m, 1);
                        echo $endpoint['skill']->getName() . " => $m / " . $endpoint['skill']->getNoteType()->getMaximum() . "\n";
                    }
                    else {
                        echo "Found non numeric values\n";
                        dd($skill["skill"]);
                        $amount = 0;
                        $supp = 0;
                    }
                }
            }
        }

        //dd($skills);

        return $result;
    }


    /**
     * Round a number.
     * @param $value
     * @param $precision
     * @return float|int
     */
    private function round_up( $value, $precision ) {
        $pow = pow ( 10, $precision );
        return ( ceil ( $pow * $value ) + ceil ( $pow * $value - ceil ( $pow * $value ) ) ) / $pow;
    }
}