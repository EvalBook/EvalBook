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

use App\Entity\ActivityTypeChild;
use App\Entity\KnowledgeType;
use App\Entity\NoteType;
use App\Repository\KnowledgeTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class Activity API
 * Handle api call for activities related contents.
 */
class ActivityApi extends AbstractController {

    /**
     * @Route("/api/knowledge/get", name="api_knowledge")
     * @param Request $request
     * @param TranslatorInterface $translator
     * @return JsonResponse
     */
    public function getKnowledges(Request $request, TranslatorInterface $translator) {
        $response = [
            "knowledges" => [],
            "noteTypes"  => [],
        ];

        $jsonRequest = json_decode($request->getContent(), true);
        // Send missing parameter if activity child was not specified.
        if(!isset($jsonRequest['activityTypeChild'])) {
            return $this->json(['message' => 'Missing parameter'], 201);
        }

        $activityTypeChild = $this->getDoctrine()->getRepository(ActivityTypeChild::class)->find(
            intval($jsonRequest['activityTypeChild'])
        );

        // Return if no activity found.
        if(is_null(($activityTypeChild))) {
            return $this->json(['message' => 'Bad parameter'], 201);
        }

        $knowledgesRepository = $this->getDoctrine()->getRepository(KnowledgeType::class);
        $noteTypesRepository  = $this->getDoctrine()->getRepository(NoteType::class);

        // Getting knowledge.
        $knowledges = $knowledgesRepository->findBy([
            "activityTypeChild" => $activityTypeChild->getId()
        ]);

        $noteTypes = $noteTypesRepository->findByType($activityTypeChild->getActivityType()->getIsNumericNotes());

        // Providing response knowledges..
        if(count($knowledges) > 0) {
            $data = [];
            foreach($knowledges as $knowledge) {
                $data[] = [
                    'id'   => $knowledge->getId(),
                    'name' => $knowledge->getName(),
                ];
            }
            $response["knowledges"] = $data;
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