<?php

namespace App\Controller;

use App\Form\UserProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class UserProfileController extends AbstractController
{
    /**
     * @Route("/user/profile", name="user_profile")
     * @param EntityManagerInterface $entityManager
     * @param TranslatorInterface $translator
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function index(EntityManagerInterface $entityManager, TranslatorInterface $translator, Request $request)
    {
        $userForm = $this->createForm(UserProfileType::class, $this->getUser());

        try {
            $userForm->handleRequest($request);

            if ($userForm->isSubmitted() && $userForm->isValid())
            {
                $entityManager->persist($this->getUser());
                $entityManager->flush();
                $this->addFlash('success', $translator->trans("Your information has been updated"));
                return $this->redirectToRoute("user_profile");
            }
        }
        catch(\Exception $e) {
            $this->addFlash('danger', $translator->trans("An error occured updating your informations"));
        }

        return $this->render('users/add.html.twig', [
            'userForm' => $userForm->createView(),
        ]);
    }
}
