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
use App\Service\FormService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/schools", name="schools_")
 * Class SchoolsImplantationsController
 * @package App\Controller
 */
class SchoolsImplantationsController extends AbstractController
{
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
            'schools' => $repository->findAll()
        ]);
    }


    /**
     * @Route("/implantations/list", name="implantations_list")
     * @IsGranted("ROLE_IMPLANTATION_LIST_ALL", statusCode=404, message="Not found")
     *
     * @param ImplantationRepository $repository
     * @return Response
     */
    public function implantationList(ImplantationRepository $repository)
    {
        return $this->render('schools_implantations/implantations-list.html.twig', [
            'implantations' => $repository->findAll()
        ]);
    }


    /**
     * @Route("/schools/add", name="schools_add")
     * @IsGranted("ROLE_ADMIN", statusCode=404, message="Not found")
     *
     * @param FormService $service
     * @return RedirectResponse|Response
     */
    public function addSchool(FormService $service)
    {
        // The form is a simple form that does not need any check, the name is uniq, lets use the FormService create method.
        list($result, $form) = $service->createSimpleForm('school-add', EcoleType::class, new Ecole());

        if(!is_null($result) && $result) {
            $this->addFlash('success', 'school.added');
            return $this->redirectToRoute("schools_schools_add");
        }

        return $this->render('schools_implantations/school-add-form.html.twig', [
            'schoolForm' => $form
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
            return $this->redirectToRoute("schools_implantations_add");
        }

        return $this->render('schools_implantations/implantation-add-form.html.twig', [
            'implantationForm' => $form
        ]);
    }


    /**
     * @Route("/schools/edit", name="schools_edit")
     * @IsGranted("ROLE_ADMIN", statusCode=404, message="Not found")
     *
     * @param FormService $service
     * @param EcoleRepository $repository
     * @return RedirectResponse|Response
     */
    public function editSchools(FormService $service, EcoleRepository $repository)
    {
        $schools = $repository->findAll();
        $editForms = array();

        foreach($schools as $school) {
            list($sResult, $formEdit) = $service->createSimpleForm('school-edit-' . $school->getId(), EcoleType::class, $school);
            $editForms[$school->getId()] = $formEdit;

            if(!is_null($sResult) && $sResult) {
                $this->addFlash('success', 'school.updated');
                return $this->redirectToRoute("schools_schools_edit");
            }
        }

        return $this->render('schools_implantations/schools-edit.html.twig', [
            'schools' => $schools,
            'schoolsForms' => $editForms,
        ]);
    }


    /**
     * @Route("/implantations/edit", name="implantations_edit")
     * @IsGranted("ROLE_IMPLANTATION_EDIT", statusCode=404, message="Not found")
     * @param ImplantationRepository $repository
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return RedirectResponse|Response
     */
    public function editImplantations(ImplantationRepository $repository, Request $request, EntityManagerInterface $em)
    {
        $implantations = $repository->findAll();
        $editForms = array();

        foreach($implantations as $implantation) {
            $implForm = $this->get('form.factory')->createNamed('implantation-edit-' . $implantation->getId(), ImplantationType::class, $implantation);
            try {
                $implForm->handleRequest($request);
                $editForms[$implantation->getId()] = $implForm->createView();

                if ($implForm->isSubmitted() && $implForm->isValid()) {
                    $em->persist($implantation);
                    $em->flush();

                    $this->addFlash('success', 'implantation.updated');
                    return $this->redirectToRoute("schools_implantations_edit");
                }
            } catch (\Exception $e) {
                $this->addFlash('danger', 'implantation.edit-error');
            }
        }

        return $this->render('schools_implantations/implantations-edit.html.twig', [
            'implantations' => $implantations,
            'implantationsForms' => $editForms
        ]);
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
