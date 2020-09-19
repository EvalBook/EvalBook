<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\ActivityThemeDomain;
use App\Entity\ActivityThemeDomainSkill;
use App\Entity\Classroom;
use App\Form\ActivityThemeDomainSkillType;
use App\Form\ActivityThemeDomainType;
use App\Repository\ActivityRepository;
use App\Repository\ActivityThemeDomainRepository;
use App\Repository\ActivityThemeRepository;
use App\Repository\NoteTypeRepository;
use App\Service\ConfigurationService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\TransactionRequiredException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;


class DashboardController extends AbstractController
{

    private $activityRepository;
    private $activityThemeRepository;
    private $activityThemeDomainRepository;
    private $noteTypeRepository;
    private $em;

    public function __construct(ActivityRepository $activityRepository, ActivityThemeRepository $themeRepository,
                                ActivityThemeDomainRepository $domainRepository, NoteTypeRepository $nTypeRepository,
                                EntityManagerInterface $em)
    {
        $this->activityRepository = $activityRepository;
        $this->activityThemeRepository = $themeRepository;
        $this->activityThemeDomainRepository = $domainRepository;
        $this->noteTypeRepository = $nTypeRepository;
        $this->em = $em;
    }


    /**
     * @Route("/")
     * @Route("/dashboard", name="dashboard")
     * @param ConfigurationService $configurationService
     * @param TranslatorInterface $translator
     * @return Response
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function getDashboard(ConfigurationService $configurationService, TranslatorInterface $translator)
    {
        try {
            $needNotesActivities = [];
            $withAbsActivities = [];
            $needNotesActivities = $this->activityRepository->getUserActivitiesToNote($this->getUser());
            $withAbsActivities = $this->activityRepository->getActivitiesWithSickStudents($this->getUser());
        }
        catch (OptimisticLockException $e) {}
        catch (TransactionRequiredException $e) {}
        catch (ORMException $e) {}

        $useConfigurationDefaultDomains = $configurationService->load($this->getUser())->getUsePredefinedActivitiesValues();

        // Populating themes and domains if no default domain was found.
        if($useConfigurationDefaultDomains) {
            $domainsCheck = $this->activityThemeDomainRepository->findBy([
                'type' => ActivityThemeDomain::TYPE_GENERIC_DEFAULT,
            ]);
            // Checking if theme domains were already pushed.
            if(count($domainsCheck) === 0) {
                $this->activityThemeRepository->populate($translator);
                $this->activityThemeDomainRepository->populate($translator);
            }
        }

        $noteTypes = $this->noteTypeRepository->findAll();
        // Populating note type if no data exists.
        if(count($noteTypes) === 0) {
            $this->noteTypeRepository->populate();
            $noteTypes = $this->noteTypeRepository->findAll();
        }


        $activityThemeDomains = [];
        foreach($this->getUser()->getClassrooms()->toArray() as $userClassroom) {
            $domains = [];
            if(!is_null($userClassroom->getOwner())) {
                $activityDomains = [];
                if($useConfigurationDefaultDomains) {
                    $activityDomains = $this->activityThemeDomainRepository->findBy([
                        'type' => ActivityThemeDomain::TYPE_GENERIC_DEFAULT,
                    ]);
                }

                $activityDomains = array_merge(
                    $activityDomains,
                    $this->activityThemeDomainRepository->findByTypeAndClassroom(ActivityThemeDomain::TYPE_GENERIC, $userClassroom, true)
                );

            }
            else {
                $activityDomains = $this->activityThemeDomainRepository->findByTypeAndClassroom(ActivityThemeDomain::TYPE_SPECIAL_CLASSROOM, $userClassroom, true);
            }

            foreach($activityDomains as $domain) {
                $domains[$domain->getDisplayName()] = [
                    "skills" => $domain->getActivityThemeDomainSkills($userClassroom->getId())->toArray(),
                    "domain" => $domain,
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
            'myActivities' => $this->activityRepository->getUserLastActivities($this->getUser()->getId(), 5),
            'needNotesActivities' => $needNotesActivities,
            'withAbsActivities' => $withAbsActivities,
            'activityThemeDomainsSkills' => $activityThemeDomains,
            'noteTypes' => $noteTypes,
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
        // Checking if classroom is used by the current logged in user.
        if(!$this->isUserClassroomCRUDAllowed($classroom)) {
            $this->addFlash('error', 'You are not allowed to edit other classroom information');
            return $this->redirectToRoute('dashboard');
        }

        // Adding theme domains is not allowed for special classrooms / masters.
        if(is_null($classroom->getOwner())) {
            $this->addFlash('error', 'You can not add a domain as your classtoom is a special classroom');
        }
        else {

            $domain = new ActivityThemeDomain();
            $form = $this->createForm(ActivityThemeDomainType::class, $domain);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                // Setting domain basic informations.
                $domain->setClassroom($classroom);
                // in uppercase to ease track user changes in db.
                $domain->setName(strtoupper($domain->getDisplayName()));

                if (is_null($domain->getClassroom()->getOwner())) {
                    $domain->setType(ActivityThemeDomain::TYPE_SPECIAL_CLASSROOM);
                }
                else {
                    $domain->setType(ActivityThemeDomain::TYPE_GENERIC);
                }

                // Redirect to the form in case the domain name already taken.
                if($this->domainExists($domain)) {
                    $this->addFlash('error', 'This domain already exists, please, choose an other name');
                    return $this->render('dashboard/form-theme-domain.html.twig', [
                        'form' => $form->createView(),
                    ]);
                }

                $this->em->persist($domain);
                $this->em->flush();

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
        // Checking if classroom is used by the current logged in user.
        if(!$this->isUserClassroomCRUDAllowed($domain->getClassroom())) {
            $this->addFlash('error', 'You are not allowed to edit other classroom information');
            return $this->redirectToRoute('dashboard');
        }

        $form = $this->createForm(ActivityThemeDomainType::class, $domain);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Redirect to the form in case the domain name already taken.
            if($this->domainExists($domain)) {
                $this->addFlash('error', 'This domain already exists, please, choose an other name');
                return $this->render('dashboard/form-theme-domain.html.twig', [
                    'form' => $form->createView(),
                ]);
            }

            $this->em->persist($domain);
            $this->em->flush();

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
     * @return JsonResponse
     */
    public function deleteThemeDomain(Request $request, ActivityThemeDomain $domain)
    {
        // Checking if classroom is used by the current logged in user.
        if(!$this->isUserClassroomCRUDAllowed($domain->getClassroom())) {
            $this->addFlash('error', 'You are not allowed to edit other classroom information');
            return $this->json(['message' => 'You are not allowed to edit other classroom information'], 201);
        }

        // Only possible if domain is editable, will return an error if the domain has activities in it.
        if(!in_array($domain->getType(),[ActivityThemeDomain::TYPE_GENERIC_DEFAULT, ActivityThemeDomain::TYPE_SPECIAL_CLASSROOM ])) {
            $skills = $domain->getActivityThemeDomainSkills()->toArray();
            foreach($skills as $skill) {
                if($skill->getActivities()->count() > 0) {
                    return $this->json(['message' => 'You can not delete domains with activities in it, but you can still edit them'], 201);
                }
            }

            $jsonRequest = json_decode($request->getContent(), true);
            if( !isset($jsonRequest['csrf']) || !$this->isCsrfTokenValid('domain_delete'.$domain->getId(), $jsonRequest['csrf'])) {
                return $this->json(['message' => 'Invalid csrf token'], 201);
            }
            $em = $this->em;
            array_map(function(ActivityThemeDomainSkill $skill) use($em) {
                $em->remove($skill);
            }, $skills);

            $this->em->remove($domain);
            $this->em->flush();

            return $this->json(['message' => 'Domain deleted'], 200);
        }
        else {
            return $this->json(['You cannot delete the special classroom domain or the default ones'], 201);
        }
    }


    /**
     * @Route("/dashboard/add/skill/{domain}/{classroom}", name="dashboard_add_skill")
     * @ParamConverter("domain", class="App\Entity\ActivityThemeDomain")
     * @ParamConverter("classroom", class="App\Entity\Classroom")
     *
     * @param Request $request
     * @param ActivityThemeDomain $domain
     * @param Classroom $classroom
     * @return RedirectResponse|Response
     */
    public function addThemeDomainSkill(Request $request, ActivityThemeDomain $domain, Classroom $classroom)
    {
        // Checking if classroom is used by the current logged in user.
        if(!$this->isUserClassroomCRUDAllowed($classroom)) {
            $this->addFlash('error', 'You are not allowed to edit other classroom information');
            return $this->redirectToRoute('dashboard');
        }

        $skill = new ActivityThemeDomainSkill();

        // Display ALL note type if matière AND special classroom || Transversal skills.
        $activityTheme = $this->activityThemeRepository->findOneBy([
            'id' => $domain->getActivityTheme(),
        ]);

        // Fetching additional note types if it is a special classroom and not a behavior or other non numeric theme.
        if($domain->getType() === ActivityThemeDomain::TYPE_SPECIAL_CLASSROOM && $activityTheme->getIsNumericNotes()) {
            $noteTypes = $this->noteTypeRepository->findBy(['coefficient' => 1]);
        }
        else {
            // Fetching base note types.
            $noteTypes = $this->noteTypeRepository->findByType($activityTheme->getIsNumericNotes(), 1);
        }

        $form = $this->createForm(ActivityThemeDomainSkillType::class, $skill, [
            'noteTypes' => $noteTypes,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // If classroom is special ( gym, philosophy , ... ) then checking if a skill already exists.
            // Special classrooms / masters are limited to ONE skill that match ONE school report row.
            $count = 0;
            // FIXME
            if($domain->getType() == ActivityThemeDomain::TYPE_SPECIAL_CLASSROOM) {
                $repository = $this->getDoctrine()->getRepository(ActivityThemeDomainSkill::class);
                $count = $repository->count([
                    'user' => $this->getUser()->getId(),
                    'activityThemeDomain' => $domain->getId(),
                    'classroom' => $classroom->getId(),
                ]);
            }

            if($count === 0) {
                // Storing information so I can check if this skill already exists in database.
                $skill->setClassroom($classroom);
                $skill->setActivityThemeDomain($domain);

                // Redirect to the form in case the domain name already taken.
                if($this->skillExists($skill)) {
                    $this->addFlash('error', 'This skill already exists, please, choose an other name');
                    return $this->render('dashboard/form-theme-domain-skill.html.twig', [
                        'form' => $form->createView(),
                    ]);
                }

                $skill->setUser($this->getUser());
                $this->em->persist($skill);
                $this->em->flush();

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
     *
     * @param Request $request
     * @param ActivityThemeDomainSkill $skill
     * @return RedirectResponse|Response
     */
    public function editThemeDomainSkill(Request $request, ActivityThemeDomainSkill $skill)
    {
        // Checking if classroom is used by the current logged in user.
        $classroom = $this->getDoctrine()->getRepository(Classroom::class)->findOneBy([
            'id' => $skill->getClassroom(),
        ]);

        // Checking if classroom is used by the current logged in user.
        if(!$this->isUserClassroomCRUDAllowed($classroom)) {
            $this->addFlash('error', 'You are not allowed to edit other classroom information');
            return $this->redirectToRoute('dashboard');
        }

        // Fetching Theme.
        $activityTheme = $this->activityThemeRepository->findOneBy([
            'id' => $skill->getActivityThemeDomain()->getActivityTheme(),
        ]);

        // Fetching domain.
        $domain = $this->activityThemeDomainRepository->findOneBy([
            'id' => $skill->getActivityThemeDomain(),
        ]);

        // Fetching additional note types if it is a special classroom and not a behavior or other non numeric theme.
        if($domain->getType() === ActivityThemeDomain::TYPE_SPECIAL_CLASSROOM && $activityTheme->getIsNumericNotes()) {
            $noteTypes = $this->noteTypeRepository->findBy(['coefficient' => 1]);
        }
        else {
            // Fetching base note types.
            $noteTypes = $this->noteTypeRepository->findByType($activityTheme->getIsNumericNotes(), 1);
        }

        $form = $this->createForm(ActivityThemeDomainSkillType::class, $skill, [
            'noteTypes' => $noteTypes,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Redirect to the form in case the domain name already taken.
            if($this->skillExists($skill)) {
                $this->addFlash('error', 'This skill already exists, please, choose an other name');
                return $this->render('dashboard/form-theme-domain-skill.html.twig', [
                    'form' => $form->createView(),
                ]);
            }

            $this->em->persist($skill);
            $this->em->flush();

            $this->addFlash('success', 'Successfully updated theme domain skill');
            return $this->redirectToRoute('dashboard');
        }

        return $this->render('dashboard/form-theme-domain-skill.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/dashboard/delete/skill/{skill}", name="dashboard_delete_skill")
     * @param Request $request
     * @param ActivityThemeDomainSkill $skill
     * @return JsonResponse
     */
    public function deleteThemeDomainSkill(Request $request, ActivityThemeDomainSkill $skill)
    {
        // Checking if classroom is used by the current logged in user.
        $classroom = $this->getDoctrine()->getRepository(Classroom::class)->findOneBy([
            'id' => $skill->getClassroom(),
        ]);

        if(!$this->isUserClassroomCRUDAllowed($classroom)) {
            $this->addFlash('error', 'You are not allowed to edit other classroom information');
            return $this->json(['message' => 'You are not allowed to edit other classroom information'], 201);
        }

        if($skill->getActivities()->count() > 0) {
            return $this->json(['message' => 'You can not delete domains with activities in it, but you can still edit them'], 201);
        }

        $jsonRequest = json_decode($request->getContent(), true);
        if( !isset($jsonRequest['csrf']) || !$this->isCsrfTokenValid('skill_delete'.$skill->getId(), $jsonRequest['csrf'])) {
            return $this->json(['message' => 'Invalid csrf token'], 201);
        }

        $this->em->remove($skill);
        $this->em->flush();

        return $this->json(['message' => 'Skill deleted'], 200);
    }


    /**
     * Check if a given domain exists in database.
     * @param ActivityThemeDomain $domain
     * @param string $type
     * @return bool
     */
    private function domainExists(ActivityThemeDomain $domain)
    {
        $domainExists = $this->activityThemeDomainRepository->findOneBy([
            'displayName' => $domain->getDisplayName(),
            'classroom' => $domain->getClassroom(),
            'activityTheme' => $domain->getActivityTheme(),
        ]);

        return is_null($domainExists) ? false : true;
    }


    /**
     * Check if a skill exists in database.
     * @param ActivityThemeDomainSkill $skill
     * @return bool
     */
    private function skillExists(ActivityThemeDomainSkill $skill)
    {
        $skillRepository = $this->getDoctrine()->getRepository(ActivityThemeDomainSkill::class);
        // Check if the edited skill already exists in database.
        $skillExists = $skillRepository->findOneBy([
            'activityThemeDomain' => $skill->getActivityThemeDomain(),
            'classroom' => $skill->getClassroom(),
            'name' => $skill->getName(),
            'description' => $skill->getDescription(),
        ]);

        return is_null($skillExists) ? false : true;
    }


    /**
     * Check if the target classroom is in the user classrooms list.
     * @param Classroom $classroom
     * @return bool
     */
    private function isUserClassroomCRUDAllowed(Classroom $classroom)
    {
        $map = function(User $user) {
            return $user->getId();
        };

        $users = $classroom->getUsers()->toArray();
        if(!is_null($classroom->getOwner())) {
            $users = array_merge($users, [$classroom->getOwner()]);
        }
        $users = array_map($map, $users);
        return in_array($this->getUser()->getId(), $users);
    }

}
