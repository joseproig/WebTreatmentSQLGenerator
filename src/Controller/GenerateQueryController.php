<?php

namespace App\Controller;

use App\Entity\Question;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\QuestionFormType;

class GenerateQueryController extends AbstractController
{
    #[Route('/generatequery', name: 'app_generate_query')]
    public function index(): Response
    {
        $query = new Question();
        $form = $this->createForm(QuestionFormType::class,$query);

        return $this->render("generatequerymenu.html.twig",[
            'form' => $form->createView()
        ]);
    }

}
