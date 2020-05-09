<?php

namespace App\Controller;

use App\Entity\Activite;
use App\Entity\Classe;
use App\Form\ActiviteType;
use App\Repository\ActiviteRepository;
use App\Repository\NoteTypeRepository;
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
        $activities = array();
        foreach($this->getUser()->getClasses() as $classe) {
            $activities = array_merge($activities, $classe->getActivites()->toArray());
        }
        // Getting the user activities.
        return $this->render('activite/index.html.twig', [
            'classes' => $this->getUser()->getClasses(),
            'activites' => $activities,
        ]);
    }

    /**
     * @Route("/activite/add/{classe}", defaults={"classe"=null}, name="activite_add")
     *
     * @param Classe|null $classe
     * @param Request $request
     * @param NoteTypeRepository $noteTypeRepository
     * @return Response
     */
    public function add(?Classe $classe, Request $request, NoteTypeRepository $noteTypeRepository): Response
    {
        // If no implantation was specified, then go to the implantation / school pair selection.
        if(is_null($classe)) {
            return $this->render('activite/select-class.html.twig', [
                'classes' => $this->getUser()->getClasses(),
            ]);
        }
        else {
            if($noteTypeRepository->count([]) === 0) {
                // No not type found, then populating database with the default ones.
                $noteTypeRepository->populate();
            }
            $activite = new Activite();
            $periodes = array();

            foreach( $classe->getImplantation()->getPeriodes() as $periode) {
                // dd($periode->getDateEnd(), date('now'));
                if($periode->getDateEnd() > new \DateTime('now')) {
                    $periodes[] = $periode;
                }
            }
            $form = $this->createForm(ActiviteType::class, $activite, [
                'periodes' => $periodes,
            ]);

            $form->handleRequest($request);
            // Make sur to override any other values sent by request.
            $activite->setClasse($classe);
            $activite->setUser($this->getUser());

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
