<?php

namespace App\Controller;

use App\Entity\Activity;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\TransactionRequiredException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    /**
     * @Route("/dashboard", name="dashboard")
     * @return Response
     */
    public function getDashboard()
    {
        $doctrine = $this->getDoctrine();
        $activityRepository  = $doctrine->getRepository(Activity::class);
        try {
            $needNotesActivities = [];
            $withAbsActivities = [];
            $needNotesActivities = $activityRepository->getUserActivitiesToNote($this->getUser());
            $withAbsActivities = $activityRepository->getActivitiesWithSickStudents($this->getUser());
        }
        catch (OptimisticLockException $e) {}
        catch (TransactionRequiredException $e) {}
        catch (ORMException $e) {}

        return $this->render('dashboard/index.html.twig', [
            'classrooms'   => $this->getUser()->getClassrooms()->toArray(),
            'myActivities' => $activityRepository->getUserLastActivities($this->getUser()->getId(), 5),
            'needNotesActivities' => $needNotesActivities,
            'withAbsActivities' => $withAbsActivities,
        ]);
    }
}
