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

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
        return $this->render('schools_implantations/index.html.twig', []);
    }


    /**
     * @Route("/schools/list", name="schools_list")
     */
    public function schoolsList()
    {

    }


    /**
     * @Route("/implantations/list", name="implantations_list")
     */
    public function implantationList()
    {

    }


    /**
     * @Route("/schools/add", name="schools_add")
     */
    public function addSchool()
    {

    }


    /**
     * @Route("/implantations/add", name="implantations_add")
     */
    public function addImplantation()
    {

    }


    /**
     * @Route("/schools/edit", name="schools_edit")
     */
    public function editSchool()
    {

    }


    /**
     * @Route("/implantations/edit", name="implantations_edit")
     */
    public function editImplantation()
    {

    }


    /**
     * @Route("/schools/delete", name="schools_delete")
     */
    public function deleteSchool()
    {

    }


    /**
     * @Route("/implantations/delete", name="implantations_delete")
     */
    public function deleteImplantation()
    {

    }
}
