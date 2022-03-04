<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GenerateQueryController extends AbstractController
{
    #[Route('/generatequery', name: 'app_generate_query')]
    public function index(): Response
    {
        return $this->render("generatequerymenu.html.twig");
    }

}
