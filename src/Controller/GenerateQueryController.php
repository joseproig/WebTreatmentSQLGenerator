<?php

namespace App\Controller;

use App\Entity\Question;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\QuestionFormType;
use Symfony\Component\HttpFoundation\Request;
use DateTime;

class GenerateQueryController extends AbstractController
{
    #[Route('/generatequery', name: 'app_generate_query')]
    public function generateQuery(Request $request): Response
    {
        $query = new Question();
        $form = $this->createForm(QuestionFormType::class,$query);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $movie = $form->getData();
            $image = $form->get('pathToDbFile')->getData();
            $d1 = new Datetime();
            $fileName =  $d1->format('U') . '.' . $image->guessExtension();
            $image -> move(
                $this->getParameter('kernel.project_dir') . '/public/dbs',
                $fileName
            );
            $movie->setPathToDBFile($this->getParameter('kernel.project_dir') . '/public/dbs/' . $fileName);
            
            return $this->redirectToRoute('app_generate_query');
        }

        return $this->renderForm("generatequerymenu.html.twig",[
            'form' => $form
        ]);
    }

}
