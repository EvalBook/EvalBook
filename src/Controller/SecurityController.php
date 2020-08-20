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

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     *
     * @param AuthenticationUtils $authenticationUtils
     * @param UserRepository $uRepository
     * @param UserPasswordEncoderInterface $encoder
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils, UserRepository $uRepository, UserPasswordEncoderInterface $encoder): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('dashboard');
        }

        // Create admin if no admin detected.
        if(!$uRepository->findByRole('ROLE_ADMIN')) {
            $admin = new User();
            $admin
                ->setEmail('admin@evalbook.dev')
                ->setLastName('Super')
                ->setFirstName('Admin')
                ->setRoles(["ROLE_ADMIN"])
                ->setPassword($encoder->encodePassword($admin, 'admin'))
            ;
            $em = $this->getDoctrine()->getManager();
            $em->persist($admin);
            $em->flush();
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('users/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }


    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(){}
}