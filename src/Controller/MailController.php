<?php

namespace App\Controller;

use App\Entity\StudentContact;
use App\Entity\User;
use App\Form\MailType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MailController extends AbstractController
{
    /**
     * @Route("/mail/send/{user}/{studentContact}", name="send_mail", defaults={"user"=null, "studentContact"=null})
     * @ParamConverter("user", class="App\Entity\User")
     * @ParamConverter("studentContact", class="App\Entity\StudentContact")
     *
     * @param User $user
     * @param StudentContact $studentContact
     * @return Response
     */
    public function sendMail(User $user, StudentContact $studentContact)
    {
        $mailForm = $this->createForm(MailType::class, null, [
            'from' => $user->getEmail(),
            'to' => $studentContact->getEmail(),
        ]);

        return $this->render('mail/index.html.twig', [
            'mailForm' => $mailForm->createView(),
        ]);
    }
}
