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

use App\Entity\Implantation;
use App\Form\ImplantationType;
use App\Repository\ImplantationRepository;
use App\Service\FormService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Class ImplantationController
 * @package App\Controller
 */
class ImplantationController extends AbstractController
{
    /**
     * @Route("/implantations", name="implantations")
     * @param ImplantationRepository $repository
     * @return Response
     */
    public function index(ImplantationRepository $repository)
    {
        return $this->render('implantations/index.html.twig', [
            'implantations' => $repository->findAll()
        ]);
    }


    /**
     * @Route("/implantations/add", name="implantations_add")
     * @IsGranted("ROLE_IMPLANTATION_CREATE", statusCode=404, message="Not found")
     *
     * @param FormService $service
     * @return RedirectResponse|Response
     */
    public function addImplantation(FormService $service)
    {
        list($result, $form) = $service->createSimpleForm('implantations-add', ImplantationType::class, new Implantation());

        if(!is_null($result) && $result) {
            $this->addFlash('success', 'implantation.added');
            return $this->redirectToRoute("implantations_add");
        }

        return $this->render('implantations/add-form.html.twig', [
            'implantationForm' => $form
        ]);
    }


    /**
     * @Route("/implantation/edit/{id}", name="implantation_edit")
     * @param Request $request
     * @param Implantation $implantation
     * @return RedirectResponse|Response
     */
    public function edit(Request $request, Implantation $implantation)
    {
        $form = $this->createForm(ImplantationType::class, $implantation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Successfully updated');
            return $this->redirectToRoute('implantations');
        }

        return $this->render('implantations/edit-form.html.twig', [
            'implantation' => $implantation,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/implantation/delete", name="implantations_delete")
     * @IsGranted("ROLE_IMPLANTATION_DELETE", statusCode=404, message="Not found")
     */
    public function deleteImplantation()
    {

    }
}