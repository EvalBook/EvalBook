<?php

namespace App\Controller;

use App\Entity\Activite;
use App\Entity\Implantation;
use App\Form\ActiviteType;
use App\Repository\ActiviteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ActiviteController extends AbstractController
{
    /**
     * @Route("/activites", name="activites")
     *
     * @param ActiviteRepository $activiteRepository
     * @return Response
     */
    public function index(ActiviteRepository $activiteRepository): Response
    {
        // Getting the user activities.
        return $this->render('activite/index.html.twig', [
            'classes' => $this->getUser()->getClasses(),
            'activites' => $activiteRepository->findBy([
                'user' => $this->getUser()->getId()]
            ),
        ]);
    }

    /**
     * @Route("/activite/add/{implantation}", defaults={"implantation"=null}, name="activite_add")
     *
     * @param Implantation|null $implantation
     * @param Request $request
     * @return Response
     */
    public function add(?Implantation $implantation, Request $request): Response
    {
        // If no implantation was specified, then go to the implantation / school pair selection.
        if(is_null($implantation)) {
            return $this->render('activite/select-class.html.twig', [
                'classes' => $this->getUser()->getClasses(),
            ]);
        }
        else {

            $activite = new Activite();
            $form = $this->createForm(ActiviteType::class, $activite);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($activite);
                $entityManager->flush();

                return $this->redirectToRoute('activites');
            }

            return $this->render('activite/form.html.twig', [
                'activite' => $activite,
                'form' => $form->createView(),
            ]);
        }
    }

    /**
     * @Route("/activite/view/{id}", name="activite_view")
     *
     * @param Activite $activite
     * @return Response
     */
    public function show(Activite $activite): Response
    {
        return $this->render('activite/show.html.twig', [
            'activite' => $activite,
        ]);
    }


    /**
     * @Route("/activite/edit/{id}", name="activite_edit")
     *
     * @param Request $request
     * @param Activite $activite
     * @return Response
     */
    public function edit(Request $request, Activite $activite): Response
    {
        $form = $this->createForm(ActiviteType::class, $activite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('activites');
        }

        return $this->render('activite/form.html.twig', [
            'activite' => $activite,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/activite/delete/{id}", name="activite_delete")
     *
     * @param Request $request
     * @param Activite $activite
     * @return Response
     */
    public function delete(Request $request, Activite $activite): Response
    {
        if ($this->isCsrfTokenValid('delete'.$activite->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($activite);
            $entityManager->flush();
        }

        return $this->redirectToRoute('activites');
    }
}
