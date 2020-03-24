<?php

namespace App\Service;

use App\Entity\Implantation;
use App\Form\ImplantationType;


class ImplantationService
{
    private $formService;


    /**
     * Manage school services.
     *
     * @param FormService $formService
     */
    public function __construct(FormService $formService)
    {
        $this->formService = $formService;
    }


    /**
     * @param Implantation $implantation
     * @return array
     */
    public function addForm(Implantation $implantation)
    {
        $id = $implantation->getId() ?? "0";
        return $this->formService->createForm('implantation-edit' . $id, ImplantationType::class, $implantation);
    }

}