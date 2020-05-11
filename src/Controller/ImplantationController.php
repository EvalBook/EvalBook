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
use App\Entity\Periode;
use App\Form\ImplantationType;
use App\Form\PeriodeType;
use App\Repository\ImplantationRepository;
use App\Repository\PeriodeRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
     * @IsGranted("ROLE_IMPLANTATION_LIST_ALL", statusCode=404, message="Not found")
     *
     * @param ImplantationRepository $repository
     * @return Response
     */
    public function index(ImplantationRepository $repository)
    {
        return $this->render('implantation/index.html.twig', [
            'implantations' => $repository->findAll()
        ]);
    }


    /**
     * @Route("/implantation/add", name="implantation_add")
     * @IsGranted("ROLE_IMPLANTATION_CREATE", statusCode=404, message="Not found")
     *
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function add(Request $request)
    {
        $implantation = new Implantation();
        $form = $this->createForm(ImplantationType::class, $implantation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($implantation);
            $entityManager->flush();
            $this->addFlash('success', 'Successfully added');

            return $this->redirectToRoute('implantations');
        }

        return $this->render('implantation/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/implantation/edit/{id}", name="implantation_edit")
     * @IsGranted("ROLE_IMPLANTATION_EDIT", statusCode=404, message="Not found")
     *
     * @param Request $request
     * @param Implantation $implantation
     * @return RedirectResponse|Response
     */
    public function edit(Implantation $implantation, Request $request)
    {
        $form = $this->createForm(ImplantationType::class, $implantation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Successfully updated');

            return $this->redirectToRoute('implantations');
        }

        return $this->render('implantation/form.html.twig', [
            'implantation' => $implantation,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/implantation/delete/{id}", name="implantation_delete", methods={"POST"})
     * @IsGranted("ROLE_IMPLANTATION_DELETE", statusCode=404, message="Not found")
     *
     * @param Implantation $implantation
     * @param Request $request
     * @return JsonResponse
     */
    public function delete(Implantation $implantation, Request $request)
    {
        $jsonRequest = json_decode($request->getContent(), true);
        if( !isset($jsonRequest['csrf']) || !$this->isCsrfTokenValid('implantation_delete'.$implantation->getId(), $jsonRequest['csrf'])) {
            return $this->json(['message' => 'Invalid csrf token'], 201);
        }

        $entityManager = $this->getDoctrine()->getManager();

        // Deleting activities and classes. Detach attributed notes from classes activities.
        foreach($implantation->getClasses() as $classe) {
            foreach($classe->getActivites() as $activite) {
                $activite->detachNotes();
                $entityManager->remove($activite);
            }
            $entityManager->remove($classe);
        }
        $entityManager->flush();

        foreach($implantation->getPeriodes() as $periode) {
            $entityManager->remove($periode);
        }

        $entityManager->remove($implantation);
        $entityManager->flush();

        return $this->json(['message' => 'Implantation deleted'], 200);
    }


    /**
     * @Route("/implantation/view/classes/{id}", name="implantation_view_classes")
     *
     * @param Implantation $implantation
     * @return Response
     */
    public function viewClasses(Implantation $implantation)
    {
        return $this->render('classe/index.html.twig', [
            'classes' => $implantation->getClasses(),
        ]);
    }


    /**
     * @Route("/implantation/period/list/{id}", name="implantation_period_list")
     * @IsGranted("ROLE_IMPLANTATION_EDIT", statusCode=404, message="Not found")
     *
     * @param Implantation $implantation
     * @param PeriodeRepository $repository
     * @return Response
     */
    public function viewPeriods(Implantation $implantation, PeriodeRepository $repository)
    {
        return $this->render('implantation/periode-index.html.twig', [
            'periods' => $repository->findBy(
                ['implantation' => $implantation->getId()],
                ['dateStart' => 'ASC']
            ),
            'implantation' => $implantation,
        ]);
    }


    /**
     * @Route("/implantation/period/add/{id}", name="implantation_period_add")
     * @IsGranted("ROLE_IMPLANTATION_EDIT", statusCode=404, message="Not found")
     *
     * @param Implantation $implantation
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function addPeriod(Implantation $implantation, Request $request)
    {
        $period = new Periode();
        $period->setImplantation($implantation);

        $form = $this->createForm(PeriodeType::class, $period);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($period);
            $entityManager->flush();
            $this->addFlash('success', 'Successfully added');

            return $this->redirectToRoute('implantations');
        }

        return $this->render('implantation/periode-form.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/implantation/period/edit/{id}", name="implantation_period_edit")
     * @IsGranted("ROLE_IMPLANTATION_EDIT", statusCode=404, message="Not found")
     *
     * @param Periode $periode
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function editPeriod(Periode $periode, Request $request)
    {
        $form = $this->createForm(PeriodeType::class, $periode);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Successfully updated');

            return $this->redirectToRoute('implantation_period_list', [
                'id' => $periode->getImplantation()->getId(),
            ]);
        }

        return $this->render('implantation/periode-form.html.twig', [
            'periode' => $periode,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/implantation/period/delete/{id}", name="implantation_periode_delete", methods={"POST"})
     * @IsGranted("ROLE_IMPLANTATION_EDIT", statusCode=404, message="Not found")
     *
     * @param Periode $periode
     * @param Request $request
     * @return JsonResponse
     */
    public function deletePeriod(Periode $periode, Request $request)
    {
        $jsonRequest = json_decode($request->getContent(), true);
        if( !isset($jsonRequest['csrf']) || !$this->isCsrfTokenValid('implantation_periode_delete'.$periode->getId(), $jsonRequest['csrf'])) {
            return $this->json(['message' => 'Invalid csrf token'], 201);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($periode);
        $entityManager->flush();

        return $this->json(['message' => 'Period deleted'], 200);
    }

}
