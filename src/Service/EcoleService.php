<?php

namespace App\Service;

use App\Entity\Ecole;
use App\Form\EcoleType;


class EcoleService
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
     * @param Ecole $ecole
     * @return
     */
    public function addForm(Ecole $ecole)
    {
        $id = $ecole->getId() ?? "0";
        return $this->formService->createForm('school-edit-' . $id, EcoleType::class, $ecole);
    }

}