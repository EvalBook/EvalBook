<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Entity\ActivityThemeDomain;
use App\Entity\ActivityThemeDomainSkill;
use App\Entity\Classroom;
use App\Entity\NoteType;
use App\Form\ActivityThemeDomainSkillType;
use App\Form\ActivityThemeDomainType;
use App\Service\ConfigurationService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\TransactionRequiredException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
                'classroom' => $userClassroom,
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
        if(is_null($classroom->getOwner())) {
            $this->addFlash('error', 'You can not add a domain as your classtoom is a special classroom');
        }
        else {

            $domain = new ActivityThemeDomain();
            $form = $this->createForm(ActivityThemeDomainType::class, $domain);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $domain->setClassroom($classroom);
                if (is_null($domain->getClassroom()->getOwner())) {
                    $domain->setType(ActivityThemeDomain::TYPE_SPECIAL_CLASSROOM);
                } else {
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

        return $this->redirectToRoute('dashboard');
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
     * @Route("/dashboard/delete/domain/{domain}", name="dashboard_delete_domain", methods={"POST"})
     * @IsGranted("ROLE_USER", statusCode=404, message="Not found")
     *
     * @param Request $request
     * @param ActivityThemeDomain $domain
     * @param EntityManagerInterface $entityManager
     * @return JsonResponse
     */
    public function deleteThemeDomain(Request $request, ActivityThemeDomain $domain, EntityManagerInterface $entityManager)
    {
        // Only possible if domain is editable, will return an error if the domain has activities in it.
        if(!in_array($domain->getType(),[ActivityThemeDomain::TYPE_GENERIC_DEFAULT, ActivityThemeDomain::TYPE_SPECIAL_CLASSROOM ])) {
            $skills = $domain->getActivityThemeDomainSkills();
            foreach($skills as $skill) {
                if($skill->getActivities()->count()) {
                    return $this->json(['message' => 'You can not delete domains with activities in it, but you can still edit them'], 201);
                }
            }

            $jsonRequest = json_decode($request->getContent(), true);
            if( !isset($jsonRequest['csrf']) || !$this->isCsrfTokenValid('domain_delete'.$domain->getId(), $jsonRequest['csrf'])) {
                return $this->json(['message' => 'Invalid csrf token'], 201);
            }

            array_map(function(ActivityThemeDomainSkill $skill) use($entityManager) {
                $entityManager->remove($skill);
            }, $skills);

            $entityManager->remove($domain);
            $entityManager->flush();

            return $this->json(['message' => 'Domain deleted'], 200);
        }
        else {
            return $this->json(['You cannot delete the special classroom domain or the default ones'], 201);
        }
    }


    /**
     * @Route("/dashboard/add/skill/{domain}", name="dashboard_add_skill")
     * @param Request $request
     * @param ActivityThemeDomain $domain
     * @return RedirectResponse|Response
     */
    public function addThemeDomainSkill(Request $request, ActivityThemeDomain $domain)
    {
        $noteTypesRepository = $this->getDoctrine()->getRepository(NoteType::class);
        $skill = new ActivityThemeDomainSkill();

        $form = $this->createForm(ActivityThemeDomainSkillType::class, $skill, [
            'noteTypes' => $noteTypesRepository->findByType($domain->getActivityTheme()->getIsNumericNotes()),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // If classroom is special ( gym, philosophy , ... ) then checking if a skill already exists.
            // Special classrooms / masters are limited to ONE skill that match ONE school report row.
            $count = 0;
            if($domain->getType() == ActivityThemeDomain::TYPE_SPECIAL_CLASSROOM) {
                $repository = $this->getDoctrine()->getRepository(ActivityThemeDomainSkill::class);
                $count = $repository->count([
                    'user' => $this->getUser()->getId(),
                    'activityThemeDomain' => $domain->getId(),
                ]);
            }

            if($count === 0) {
                $skill->setUser($this->getUser());
                $skill->setActivityThemeDomain($domain);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($skill);
                $entityManager->flush();

                $this->addFlash('success', 'Successfully added theme domain skill');
            }
            else {
                $this->addFlash('error', 'You cannot add new skill as you already have your special classroom skill');
            }
            return $this->redirectToRoute('dashboard');
        }

        return $this->render('dashboard/form-theme-domain-skill.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/dashboard/edit/skill/{skill}", name="dashboard_edit_skill")
     * @param Request $request
     * @param ActivityThemeDomainSkill $skill
     */
    public function editThemeDomainSkill(Request $request, ActivityThemeDomainSkill $skill)
    {

    }

}
