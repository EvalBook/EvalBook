<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class NoteBookController extends AbstractController
{
    /**
     * @Route("/note/book", name="note_book")
     */
    public function index()
    {
        return $this->render('note_book/index.html.twig', [
            'controller_name' => 'NoteBookController',
        ]);
    }
}
