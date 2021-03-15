<?php

namespace App\Controller\Panel;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/panel", name="panel_index")
     * @Route("/panel/home", name="panel_home")
     */
    public function index(): Response
    {
        return $this->render('panel/home/index.html.twig');
    }
}
