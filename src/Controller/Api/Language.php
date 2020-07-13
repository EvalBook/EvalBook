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

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class Language
 * Handle api call to /api/language/
 */
class Language extends AbstractController {

    /**
     * @Route("/api/strings", name="api_strings")
     * @param Request $request
     * @param TranslatorInterface $translator
     * @return JsonResponse
     */
    public function getString(Request $request, TranslatorInterface $translator)
    {
        $jsonRequest = json_decode($request->getContent(), true);
        // Send missing parameter if translation domain is not provided.
        if(!isset($jsonRequest['domain']) || !isset($jsonRequest['strings'])) {
            // Send 'Missing parameter code' in case translation domain was not provided.
            return $this->json(['message' => 'Missing parameter'], 201);
        }

        $rValues = array();
        foreach($jsonRequest['strings'] as $stringId) {
            $rValues[$stringId] = $translator->trans($stringId, [], $jsonRequest['domain']);
        }

        return $this->json($rValues, 200);
    }
}