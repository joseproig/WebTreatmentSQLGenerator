<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Project;
use App\Form\ProjectFormType;
use Symfony\Component\HttpFoundation\Request;
use App\Logic\UtilsFunctions;
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

    const EXTENSION_OF_PNG = 'png';
    const EXTENSION_OF_JPG = 'jpg';
    const EXTENSION_OF_JPEG = 'jpeg';
    const EXTENSION_OF_DB = 'db';


    #[Route('/projects/newproject', name: 'app_newproject')]
    public function generateQuery(Request $request): Response
    {
        $project = new Project();
        //Generem el formulari
        $form = $this->createForm(ProjectFormType::class,$project);
        
        $errorInFile = false;
        $errorInImage = false;

        //Obtenim la petició
        $form->handleRequest($request);

    
        //Comprovem si el formulari s'ha entregat i es valid, en cas contrariu es que haura entrat a la pàgina només.
        if ($form->isSubmitted() && $form->isValid()) {
            $project = $form->getData();

            $logoFile = $form->get('pathToLogo')->getData();
            //Mirem si es vol utilitzar el logo per defecte
            if ($logoFile != null) {
                //Movem fitxer al servidor, sols si té la extensió .db que es la que llegeix el servidor
                $extension = $logoFile->getClientOriginalExtension();
                if ((strcmp($extension,self::EXTENSION_OF_PNG) == 0 || strcmp($extension,self::EXTENSION_OF_JPG) == 0 || strcmp($extension,self::EXTENSION_OF_JPEG) == 0)) {
                    $project->setPathToLogo(UtilsFunctions::getInstance()->moveFileFromTmpToPublic($this->getParameter('kernel.project_dir'),$logoFile,$extension, 'images'));
                } else {
                    $errorInImage = true;
                }
            } else {
                $project->setPathToLogo($this->getParameter('kernel.project_dir') . '/public/build/images/' . 'databaseIcon.8765e8fe.png');
            }

            $fileDB = $form->get('pathToDbFile')->getData();
            $extension = $fileDB->getClientOriginalExtension();
            if (strcmp($extension,self::EXTENSION_OF_DB) == 0) {
                $project->setPathToDBFile(UtilsFunctions::getInstance()->moveFileFromTmpToPublic($this->getParameter('kernel.project_dir'),$fileDB,$extension, 'dbs'));
            } else {
                $errorInFile = true;
            }
            
            //Si no hi ha errors de cap tipus, tornem a la pàgina de projectes
            if (!$errorInFile && !$errorInImage) {
                return $this->redirectToRoute("app_projects");
            }
        }

        return $this->renderForm("projects/newprojectform.html.twig",[
            'form' => $form,
            'errorInFile'=>$errorInFile,
            'errorInImage'=>$errorInImage
        ]);
    }

    #[Route('/projects/info/{id}', name: 'app_project_info')]
    public function projectInfo($id): Response
    {
        return $this->render('projects/projectDetails.html.twig', [
        ]);
    }

}
