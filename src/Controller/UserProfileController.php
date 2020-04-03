<?php

namespace App\Controller;

use App\Form\UserProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserProfileController extends AbstractController
{
    /**
     * @Route("/user/profile", name="user_profile")
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function index(EntityManagerInterface $entityManager, Request $request)
    {
        $userForm = $this->createForm(UserProfileType::class, $this->getUser());

        try {
            $userForm->handleRequest($request);

            if ($userForm->isSubmitted() && $userForm->isValid())
            {
                $entityManager->persist($this->getUser());
                $entityManager->flush();
                $this->addFlash('success', 'user.self-updated');
                return $this->redirectToRoute("user_profile");
            }
        }
        catch(\Exception $e) {
            $this->addFlash('danger', 'user.self-update-error');
        }

        return $this->render('user_profile/index.html.twig', [
            'userProfileForm' => $userForm->createView(),
        ]);
    }
}
