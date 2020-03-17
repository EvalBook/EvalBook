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

use App\Helper\CommonUserHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/install", name="install_")
 * @package App\Controller
 */
class InstallController extends AbstractController
{

    /**
     * @Route("/", name="index")
     * @param EntityManagerInterface $em
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param TranslatorInterface $translator
     * @return Response
     */
    public function index(EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder, TranslatorInterface $translator)
    {
        /**
         * Exemple pour créer une secrétaire.
         */

        // Création d'une secrétaire.
        $helper = new CommonUserHelper($em, $passwordEncoder);
        $result = $helper->createSecretary("firstfake", "lastNamefake", "admin22654891321@evalbook.dev", "Dev007!!");
        if($result)
            $this->addFlash('success', $translator->trans('User created'));
        else
            $this->addFlash('danger', $translator->trans('User creation error'));


        return $this->render('install/index.html.twig', [
            'controller_name' => 'InstallController',
        ]);
    }
}
