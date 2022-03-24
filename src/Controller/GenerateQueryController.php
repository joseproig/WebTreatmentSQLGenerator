<?php

namespace App\Controller;

use App\Entity\Question;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\QuestionFormType;
use Symfony\Component\HttpFoundation\Request;
use App\Logic\RestAPIController;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

use DateTime;

class GenerateQueryController extends AbstractController
{
    const EXTENSION_OF_SQLITE = 'db';

    #[Route('/generatequery', name: 'app_generate_query')]
    public function generateQuery(Request $request): Response
    {
        $query = new Question();
        //Generem el formulari
        $form = $this->createForm(QuestionFormType::class,$query);
        
        $errorInFile = false;
        $errorInTemplate = false;

        //Obtenim la petició
        $form->handleRequest($request);

    
        //Comprovem si el formulari s'ha entregat i es valid, en cas contrariu es que haura entrat a la pàgina només.
        if ($form->isSubmitted() && $form->isValid()) {
            $question = $form->getData();
            $fileDB = $form->get('pathToDbFile')->getData();
            
            $extension = $fileDB->getClientOriginalExtension();
            //Movem fitxer al servidor, sols si té la extensió .db que es la que llegeix el servidor
            if (strcmp($extension,self::EXTENSION_OF_SQLITE) == 0) {
                //Agafem la datetime per a ficar-li el nom al fitxer que es moura al servidor
                $d1 = new Datetime();
                $fileName =  $d1->format('U') . '.' . $extension;
                $pathDirectory = $this->getParameter('kernel.project_dir') . '/public/dbs'; 
                $fileDB -> move(
                    $pathDirectory,
                    $fileName
                );
                $restAPIController = new RestAPIController();
                $question->setPathToDBFile($pathDirectory . '/' . $fileName);
                
    

                $responseToPetition = $restAPIController->getPossibilities($question);
                
                

                if ($responseToPetition->getStatusCode() == 200) {
                    return $this->renderForm("managegeneratedqueries.html.twig",[
                        'possibleQueries' => $responseToPetition->getResponse()->getPossibleQueries(),
                    ]);
                } 

            } else {
                $errorInFile = true;
            }
            
        }

        return $this->renderForm("generatequerymenu.html.twig",[
            'form' => $form,
            'errorInFile'=>$errorInFile
        ]);
    }

}
