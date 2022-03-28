<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Project;
use App\Form\ProjectFormType;
use Symfony\Component\HttpFoundation\Request;
use DateTime;

class ProjectsController extends AbstractController
{
    #[Route('/projects', name: 'app_projects')]
    public function index(): Response
    {
        return $this->render('projects/index.html.twig', [
            'controller_name' => 'ProjectsController',
        ]);
    }

    const EXTENSION_OF_SQLITE = 'db';

    #[Route('/projects/newproject', name: 'app_newproject')]
    public function generateQuery(Request $request): Response
    {
        $project = new Project();
        //Generem el formulari
        $form = $this->createForm(ProjectFormType::class,$project);
        
        $errorInFile = false;

        //Obtenim la petició
        $form->handleRequest($request);

    
        //Comprovem si el formulari s'ha entregat i es valid, en cas contrariu es que haura entrat a la pàgina només.
        if ($form->isSubmitted() && $form->isValid()) {
            $project = $form->getData();
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
                $project->setPathToDBFile($pathDirectory . '/' . $fileName);
            } else {
                $errorInFile = true;
            }
            
            return $this->redirectToRoute('app_newproject');
        }

        return $this->renderForm("projects/newprojectform.html.twig",[
            'form' => $form,
            'errorInFile'=>$errorInFile
        ]);
    }

}
