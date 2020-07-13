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

use App\Entity\StudentContact;
use App\Entity\User;
use App\Form\MailType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
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
     * @param Request $request
     * @param MailerInterface $mailer
     * @return Response
     */
    public function sendMail(User $user, StudentContact $studentContact, Request $request, MailerInterface $mailer)
    {
        $mailForm = $this->createForm(MailType::class, null, [
            'from' => $user->getEmail(),
            'to' => $studentContact->getEmail(),
        ]);

        $mailForm->handleRequest($request);

        if($mailForm->isSubmitted() && $mailForm->isValid()) {
            // Prepare email.
            $email = (new Email())
                ->from($mailForm->get('from')->getData())
                ->addReplyTo($mailForm->get('from')->getData())
                ->to(trim($mailForm->get('to')->getData()))
                ->subject($mailForm->get('subject')->getData())
                ->text($mailForm->get('message')->getData())
                ->priority(Email::PRIORITY_HIGHEST)
            ;

            // Add attachement if any.
            if($attachement = $mailForm->get('attachement')->getData()) {
                $email->attach($attachement->getPathName(), $attachement->getClientOriginalName());
            }

            // Add Carbon copies.
            if($carbonAddresses = $mailForm->get('carbonCopy')->getData()) {
                $email->cc(...array_map( function($address) {
                    return trim($address);
                }, explode(',', $carbonAddresses)));
            }

            // Send mail.
            try {
                $mailer->send($email);
                $this->addFlash('success', 'Your mail was sent !');
            }
            catch (TransportExceptionInterface $transportError) {
                $this->addFlash('error', 'Your mail was not sent, an error occurred.');
            }
        }

        return $this->render('mail/index.html.twig', [
            'mailForm' => $mailForm->createView(),
        ]);
    }
}
