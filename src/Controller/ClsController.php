<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ClsController extends AbstractController
{
    /**
     * @Route("/cls", name="cls")
     */
    public function index()
    {
        return $this->render('cls/index.html.twig', [
            'controller_name' => 'ClsController',
        ]);
    }
}
