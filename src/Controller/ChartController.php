<?php

namespace App\Controller;


use Symfony\Component\Routing\Annotation\Route;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;



class ChartController extends Controller
{
    /**
     * @Route("/google-charts",name="google-charts")
     * Method({"GET"})
     */
    public function index()
    {

        return $this->render('chart/chart.html.twig');
    }

}


