<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class NoteTypeController extends AbstractController
{
    /**
     * @Route("/note/type", name="note_type")
     */
    public function index()
    {
        return $this->render('note_type/index.html.twig', [
            'controller_name' => 'NoteTypeController',
        ]);
    }
}
