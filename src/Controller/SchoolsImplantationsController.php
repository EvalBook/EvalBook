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

namespace App\Controller;

use App\Entity\Ecole;
use App\Entity\Implantation;
use App\Form\EcoleType;
use App\Form\ImplantationType;
use App\Repository\EcoleRepository;
use App\Repository\ImplantationRepository;
use App\Service\EntityService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/schools", name="schools_")
 * Class SchoolsImplantationsController
 * @package App\Controller
 */
class SchoolsImplantationsController extends AbstractController
{
    private $entityService;


    /**
     * SchoolsImplantationsController constructor.
     * @param EntityService $service
     */
    public function __construct(EntityService $service)
    {
        $this->entityService = $service;
    }


    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        return $this->render('schools_implantations/index.html.twig');
    }


    /**
     * @Route("/schools/list", name="schools_list")
     * @IsGranted("ROLE_ADMIN", statusCode=404, message="Not found")
     *
     * @param EcoleRepository $repository
     * @return Response
     */
    public function schoolsList(EcoleRepository $repository)
    {
        return $this->render('schools_implantations/schools-list.html.twig', [
            'schools' => $this->entityService->setRepository($repository)->findAll()
        ]);
    }


    /**
     * @Route("/implantations/list", name="implantations_list")
     * @IsGranted("ROLE_IMPLANTATIONS_LIST", statusCode=404, message="Not found")
     * @param ImplantationRepository $repository
     * @return Response
     */
    public function implantationList(ImplantationRepository $repository)
    {
        return $this->render('schools_implantations/implantations-list.html.twig', [
            'implantations' => $this->entityService->setRepository($repository)->findAll()
        ]);
    }


    /**
     * @Route("/schools/add", name="schools_add")
     * @IsGranted("ROLE_ADMIN", statusCode=404, message="Not found")
     * @return RedirectResponse|Response
     */
    public function addSchool()
    {
        list($result, $form) = $this->entityService->addForm(EcoleType::class, new Ecole(), 'school-add');

        if(!is_null($result) && $result) {
            $this->addFlash('success', $this->entityService->getTranslator()->trans("School added"));
            return $this->redirectToRoute("schools_schools_add");
        }

        return $this->render('schools_implantations/schools-add.html.twig', [
            'schoolForm' => $form
        ]);
    }


    /**
     * @Route("/implantations/add", name="implantations_add")
     * @IsGranted("ROLE_IMPLANTATION_CREATE", statusCode=404, message="Not found")
     * @return RedirectResponse|Response
     */
    public function addImplantation()
    {
        list($result, $form) = $this->entityService->addForm(ImplantationType::class,new Implantation(), 'implantations-add');

        if(!is_null($result) && $result) {
            $this->addFlash('success', $this->entityService->getTranslator()->trans("Implantation added"));
            return $this->redirectToRoute("schools_implantations_add");
        }

        return $this->render('schools_implantations/implantations-add.html.twig', [
            'implantationForm' => $form
        ]);
    }


    /**
     * @Route("/schools/edit", name="schools_edit")
     * @IsGranted("ROLE_ADMIN", statusCode=404, message="Not found")
     * @param EcoleRepository $repository
     * @return RedirectResponse|Response
     */
    public function editSchool(EcoleRepository $repository)
    {
        $schools = $this->entityService->setRepository($repository)->findAll();
        $editForms = array();

        foreach($schools as $school) {
            list($sResult, $formEdit) = $this->entityService->editForm(EcoleType::class, $school, 'school-edit');
            $editForms[$school->getId()] = $formEdit;

            if(!is_null($sResult) && $sResult) {
                $this->addFlash('success', $this->entityService->getTranslator()->trans("School updated"));
                return $this->redirectToRoute("schools_schools_edit");
            }
        }
        return $this->render('users/edit.html.twig', [
            'schools' => $schools,
            'editForms' => $editForms,
        ]);
    }


    /**
     * @Route("/implantations/edit", name="implantations_edit")
     * @IsGranted("ROLE_IMPLANTATION_EDIT", statusCode=404, message="Not found")
     */
    public function editImplantation()
    {

    }


    /**
     * @Route("/schools/delete", name="schools_delete")
     * @IsGranted("ROLE_ADMIN", statusCode=404, message="Not found")
     */
    public function deleteSchool()
    {

    }


    /**
     * @Route("/implantations/delete", name="implantations_delete")
     * @IsGranted("ROLE_IMPLANTATION_DELETE", statusCode=404, message="Not found")
     */
    public function deleteImplantation()
    {

    }
}
