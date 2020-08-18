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

use App\Entity\ActivityThemeDomain;
use App\Entity\ActivityThemeDomainSkill;
use App\Entity\Classroom;
use App\Entity\NoteType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class Activity API
 * Handle api call for activities related contents.
 */
class ActivityApi extends AbstractController {

    /**
     * @Route("/api/skills/get", name="api_skill")
     * @param Request $request
     * @return JsonResponse
     */
    public function getSkills(Request $request) {
        $response = [
            "skills" => [],
            "noteTypes"  => [],
        ];

        $jsonRequest = json_decode($request->getContent(), true);
        // Send missing parameter if activity theme domain was not specified.
        if(!isset($jsonRequest['activityThemeDomain']) || !isset($jsonRequest['classroom'])) {
            return $this->json(['message' => 'Missing parameter'], 201);
        }

        $activityThemeDomain = $this->getDoctrine()->getRepository(ActivityThemeDomain::class)->find(
            intval($jsonRequest['activityThemeDomain'])
        );

        // Return if no activity found.
        if(is_null(($activityThemeDomain))) {
            return $this->json(['message' => 'Bad parameter'], 201);
        }

        $skillsRepository = $this->getDoctrine()->getRepository(ActivityThemeDomainSkill::class);
        $noteTypesRepository  = $this->getDoctrine()->getRepository(NoteType::class);
        // Needed to filter skills by classroom.
        $classroom = intval($jsonRequest['classroom']);
        $classroom = $this->getDoctrine()->getRepository(Classroom::class)->find($classroom);

        // Getting skill.
        $skills = $skillsRepository->findBy([
            "activityThemeDomain" => $activityThemeDomain->getId()
        ]);

        $noteTypes = $noteTypesRepository->findByType($activityThemeDomain->getActivityTheme()->getIsNumericNotes());

        // Providing skill response.
        if(count($skills) > 0 && !is_null($classroom)) {
            // Filer skill that are only attached to the classroom target.

            $skills = array_filter($skills, function(ActivityThemeDomainSkill $skill) use($classroom){
                    return $skill->getClassroom()->getId() === $classroom->getId();
            });

            $data = [];
            foreach($skills as $skill) {
                $data[] = [
                    'id'   => $skill->getId(),
                    'name' => $skill->getName(),
                ];
            }
            $response["skills"] = $data;
        }

        // Providing available note types ( right format ).
        if(count($noteTypes) > 0) {
            $data = [];
            foreach($noteTypes as $noteType) {
                $data[] = [
                    'id'   => $noteType->getId(),
                    'name' => $noteType->getName(),
                ];
            }
            $response["noteTypes"] = $data;
        }

        return $this->json($response, 200);
    }

}