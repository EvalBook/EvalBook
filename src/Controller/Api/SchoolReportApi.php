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
use App\Entity\SchoolReportTheme;
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

        $result = [];
        if(count($notes) > 0) {
            $result = $this->compute($notes);
        }

        // Fetching school report theme to use.
        $theme = $this->getDoctrine()->getRepository(SchoolReportTheme::class)->findOneBy([
            'id' => $implantation->getSchoolReportTheme(),
        ]);

        $view = $this->renderView('@SchoolReportThemes/' . $theme->getUuid() . '/view.twig', [
            'student' => $student,
            'report' => $result,
            'css' => '/themes/school_report_themes/' . $theme->getUuid() . '/theme.css',
        ]);


        return new JsonResponse([
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
            // Only if activity must be added to the school report.
            if($note->getActivity()->getIsInShoolReport()) {
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
                    'note' => $note,
                    'skill' => $skill,
                ];
            }
        }

        // Compute total of notes.
        // From theme
        foreach($result as $key => $themes) {
            // From domaine
            foreach($themes as $key2 => $domains) {

                // For each school report skill.
                foreach($domains as $key3 => $skills ) {
                    // m = (2 × 1 + 9 × 2 + 27 × 3) / (10 × 1 + 15 × 2 + 30 × 3) × 20
                    $amount = 0;
                    $supp = 0;

                    foreach ($skills as $key4 => $endpoint) {

                        // Do not compute not attributed notes.
                        if(strtolower($endpoint['note']->getNote() === 'abs')) {
                            continue;
                        }

                        // Fetching needle information.
                        $notesIntervals = $endpoint['note']->getActivity()->getNoteType()->getIntervals();
                        array_push($notesIntervals, $endpoint['note']->getActivity()->getNoteType()->getMaximum());
                        array_unshift($notesIntervals, $endpoint['note']->getActivity()->getNoteType()->getMinimum());

                        // Transform note so n/m is position/count instead of 4/20.
                        $n = array_search($endpoint['note']->getNote(), $notesIntervals);
                        $m = count($notesIntervals) - 1;

                        $amount += $n * $endpoint['note']->getActivity()->getCoefficient();
                        $supp += $m * $endpoint['note']->getActivity()->getCoefficient();
                    }

                    // Fetching skill in case of no note.
                    $skill = $skillRepository->findOneBy(['id' => $key3]);
                    if($skill->getNoteType()->isNumeric()) {
                        $low = ($amount / $supp) * $skill->getNoteType()->getMaximum();
                        $low = $this->round_up($low, 1);
                    }
                    else {
                        $intervals = $skill->getNoteType()->getIntervals();
                        array_push($intervals, $skill->getNoteType()->getMaximum());
                        array_unshift($intervals, $skill->getNoteType()->getMinimum());
                        $low = ($amount / $supp) * count($intervals);
                        if($low > count($intervals) - 1)
                            $low = count($intervals) - 1;
                        $low = $intervals[$this->round_up($low, 0)];
                    }

                    echo $skill->getName() . " => $low / " . $skill->getNoteType()->getMaximum() . "\n";
                    $endpoint['min'] = $low;
                    $endpoint['max'] = $skill->getNoteType()->getMaximum();
                }
            }
        }

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