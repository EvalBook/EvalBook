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
use App\Repository\EcoleRepository;
use App\Repository\ImplantationRepository;
use App\Service\EcoleService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;


/**
 * @Route("/schools", name="schools_")
 * Class SchoolsImplantationsController
 * @package App\Controller
 */
class SchoolsImplantationsController extends AbstractController
{
    private $translator;

    /**
     * SchoolsImplantationsController constructor.
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }


    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        return $this->render('schools_implantations/index.html.twig', []);
    }


    /**
     * @Route("/schools/list", name="schools_list")
     * @IsGranted("ROLE_ADMIN", statusCode=404, message="Not found")
     *
     * @param EcoleRepository $ecoleRepository
     * @return Response
     */
    public function schoolsList(EcoleRepository $ecoleRepository)
    {
        return $this->render('schools_implantations/schools-list.html.twig', [
            'schools' => $ecoleRepository->findAll()
        ]);
    }


    /**
     * @Route("/implantations/list", name="implantations_list")
     * @IsGranted("ROLE_IMPLANTATIONS_LIST", statusCode=404, message="Not found")
     * @param ImplantationRepository $implantationRepository
     * @return Response
     */
    public function implantationList(ImplantationRepository $implantationRepository)
    {
        return $this->render('schools_implantations/implantations-list.html.twig', [
            'implantations' => $implantationRepository->findAll()
        ]);
    }


    /**
     * @Route("/schools/add", name="schools_add")
     * @IsGranted("ROLE_ADMIN", statusCode=404, message="Not found")
     * @param EcoleService $ecoleService
     * @return RedirectResponse|Response
     */
    public function addSchool(EcoleService $ecoleService)
    {
        list($result, $form) = $ecoleService->addForm(new Ecole());

        if(!is_null($result) && $result) {
            $this->addFlash('success', $this->translator->trans("School added"));
            return $this->redirectToRoute("schools_schools_add");
        }

        return $this->render('schools/schools-add.html.twig', [
            'schoolForm' => $form
        ]);
    }


    /**
     * @Route("/implantations/add", name="implantations_add")
     * @IsGranted("ROLE_IMPLANTATION_CREATE", statusCode=404, message="Not found")
     */
    public function addImplantation()
    {

    }


    /**
     * @Route("/schools/edit", name="schools_edit")
     * @IsGranted("ROLE_ADMIN", statusCode=404, message="Not found")
     */
    public function editSchool()
    {

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
