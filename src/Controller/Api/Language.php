<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class Language
 * Handle api call to /api/language/
 */
class Language extends AbstractController {

    /**
     * @Route("/api/locale/{domain}/{msgid}", name="api_locale")
     * @param TranslatorInterface $translator
     * @param string $domain
     * @param string $msgid
     * @return JsonResponse
     */
    public function getString(TranslatorInterface $translator, string $domain, string $msgid)
    {
        return $this->json([
            'string' => $translator->trans($msgid, [], $domain)
        ]);
    }
}