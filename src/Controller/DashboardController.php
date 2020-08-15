<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Entity\ActivityTheme;
use App\Entity\ActivityThemeDomain;
use App\Entity\Classroom;
use App\Form\ActivityThemeDomainType;
use App\Repository\ActivityThemeRepository;
use App\Service\ConfigurationService;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\TransactionRequiredException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


class DashboardController extends AbstractController
{
    /**
     * @Route("/dashboard", name="dashboard")
     * @param ConfigurationService $configurationService
     * @return Response
     */
    public function getDashboard(ConfigurationService $configurationService)
    {
        $doctrine = $this->getDoctrine();
        $activityRepository  = $doctrine->getRepository(Activity::class);
        $activityDomainRepository = $doctrine->getRepository(ActivityThemeDomain::class);

        try {
            $needNotesActivities = [];
            $withAbsActivities = [];
            $needNotesActivities = $activityRepository->getUserActivitiesToNote($this->getUser());
            $withAbsActivities = $activityRepository->getActivitiesWithSickStudents($this->getUser());
        }
        catch (OptimisticLockException $e) {}
        catch (TransactionRequiredException $e) {}
        catch (ORMException $e) {}

        $useConfigurationDefaultDomains = $configurationService->load($this->getUser())->getUsePredefinedActivitiesValues();

        $activityThemeDomains = [];
        foreach($this->getUser()->getClassrooms()->toArray() as $userClassroom) {
            $domains = [];
            if(!is_null($userClassroom->getOwner())) {
                if($useConfigurationDefaultDomains) {
                    $activityDomains = $activityDomainRepository->findBy([
                        'type' => ActivityThemeDomain::TYPE_GENERIC_DEFAULT,
                    ]);
                }
                else {
                    $activityDomains = $activityDomainRepository->findByTypeAndClassroom(ActivityThemeDomain::TYPE_GENERIC, $userClassroom, true);
                }
            }
            else {
                $activityDomains = $activityDomainRepository->findByTypeAndClassroom(ActivityThemeDomain::TYPE_SPECIAL_CLASSROOM, $userClassroom, true);
            }

            foreach($activityDomains as $domain) {
                $domains[$domain->getDisplayName()] = [
                    "skills" => $domain->getActivityThemeDomainSkills()->toArray(),
                    "domainId" => $domain->getId(),
                    "editable" => !is_null($domain->getClassroom()),
                ];
            }

            $rArray = [
                'domains' => $domains,
                'classroomId' => $userClassroom->getId(),
            ];
            $activityThemeDomains = array_merge($activityThemeDomains, [$userClassroom->getName() => $rArray]);
        }

        return $this->render('dashboard/index.html.twig', [
            'classrooms'   => $this->getUser()->getClassrooms()->toArray(),
            'myActivities' => $activityRepository->getUserLastActivities($this->getUser()->getId(), 5),
            'needNotesActivities' => $needNotesActivities,
            'withAbsActivities' => $withAbsActivities,
            'activityThemeDomainsSkills' => $activityThemeDomains,
        ]);
    }


    /**
     * @Route("/dashboard/add/domain/{classroom}", name="dashboard_add_domain")
     * @param Request $request
     * @param Classroom $classroom
     * @return RedirectResponse|Response
     */
    public function addThemeDomain(Request $request, Classroom $classroom)
    {
        $domain = new ActivityThemeDomain();
        $form = $this->createForm(ActivityThemeDomainType::class, $domain);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $domain->setClassroom($classroom);
            if(is_null($domain->getClassroom()->getOwner())) {
                $domain->setType(ActivityThemeDomain::TYPE_SPECIAL_CLASSROOM);
            }
            else {
                $domain->setType(ActivityThemeDomain::TYPE_GENERIC);
            }
            $domain->setName(strtolower($domain->getDisplayName()));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($domain);
            $entityManager->flush();

            $this->addFlash('success', 'Successfully added theme domain');

            return $this->redirectToRoute('dashboard');
        }

        return $this->render('dashboard/form-theme-domain.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/dashboard/edit/domain/{domain}", name="dashboard_edit_domain")
     * @param Request $request
     * @param ActivityThemeDomain $domain
     * @return RedirectResponse|Response
     */
    public function editThemeDomain(Request $request, ActivityThemeDomain $domain)
    {
        $form = $this->createForm(ActivityThemeDomainType::class, $domain);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($domain);
            $entityManager->flush();

            $this->addFlash('success', 'Successfully updated theme domain');

            return $this->redirectToRoute('dashboard');
        }

        return $this->render('dashboard/form-theme-domain.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/dashboard/add/skill", name="dashboard_add_skill")
     */
    public function addThemeDomainSkill()
    {

    }


    /**
     * @Route("/dashboard/edit/skill", name="dashboard_edit_skill")
     */
    public function editThemeDomainSkill()
    {

    }

}
