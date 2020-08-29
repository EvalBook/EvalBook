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

namespace App\Controller\Api;

header("Access-Control-Allow-Origin: *");

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;



/**
 * Manage user roles types, return right information about predefined roles.
 */
class RoleTypeApi extends AbstractController
{

    /**
     * @Route("/api/roles-predefined/get", name="api_roles_predefined")
     * @param Request $request
     * @return JsonResponse
     */
    public function getPredefinedRoles(Request $request)
    {
        $jsonRequest = json_decode($request->getContent(), true);
        // Send missing parameter if role set id was not specified.
        if(!isset($jsonRequest['roleSetId'])) {
            return $this->json(['message' => 'Missing parameter'], 201);
        }

        $predefinedRoles = array_keys(User::getPredefinedRoleSet());
        $source = $predefinedRoles[intval($jsonRequest['roleSetId'])];

        return $this->json([
            'roles' => array_values(User::getPredefinedRoleSet()[$source]),
        ]);
    }

}