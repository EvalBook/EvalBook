<?php

namespace App\Controller\Api;

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