<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Project;
use App\Entity\Question;
use App\Entity\User;
use App\Logic\RestAPIController;
use App\Form\ProjectFormType;
use App\Form\QuestionFormType;
use Symfony\Component\HttpFoundation\Request;
use App\Logic\UtilsFunctions;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Collections\ArrayCollection;

class ProjectsController extends AbstractController
{

    private $entmanager;

    public function __construct(EntityManagerInterface $entmanager)
    {
        $this->entmanager = $entmanager;
    }

    #[Route('/projects', name: 'app_projects')]
    public function index(): Response
    {
        $projectManager = $this->entmanager->getRepository(Project::class);
        $projects = $projectManager->findAll();
        return $this->render('projects/index.html.twig', [
            'projects' => $projects,
        ]);
    }

    const EXTENSION_OF_PNG = 'png';
    const EXTENSION_OF_JPG = 'jpg';
    const EXTENSION_OF_JPEG = 'jpeg';
    const EXTENSION_OF_DB = 'db';


    #[Route('/projects/create', name: 'app_newproject')]
    public function createProject(Request $request): Response
    {
        $project = new Project();
        //Generem el formulari
        $form = $this->createForm(ProjectFormType::class, $project);

        $errorInFile = false;

        //Obtenim la petició
        $form->handleRequest($request);


        //Comprovem si el formulari s'ha entregat i es valid, en cas contrariu es que haura entrat a la pàgina només.
        if ($form->isSubmitted() && $form->isValid()) {
            $project = $form->getData();
            $fileDB = $form->get('pathToDbFile')->getData();
            $extension = $fileDB->getClientOriginalExtension();
            if (strcmp($extension, self::EXTENSION_OF_DB) == 0) {
                $project->setPathToDBFile(UtilsFunctions::getInstance()->moveFileFromTmpToPublic($this->getParameter('kernel.project_dir'), $fileDB, $extension, 'dbs'));
                $this->entmanager->persist($project);
                $this->entmanager->flush();
                return $this->redirectToRoute("app_projects");
            } else {
                $errorInFile = true;
            }
        }

        return $this->renderForm("projects/newprojectform.html.twig", [
            'form' => $form,
            'errorInFile' => $errorInFile
        ]);
    }

    #[Route('/projects/{id}', name: 'app_project_info')]
    public function projectInfo($id): Response
    {
        $projectManager = $this->entmanager->getRepository(Project::class);
        $project = $projectManager->findOneBy(['id' => $id], []);

        return $this->render('projects/projectDetails.html.twig', [
            'project' => $project
        ]);
    }

    #[Route('/projects/{id}/templates/create', name: 'app_generate_query')]
    public function generateQuery(Request $request, $id): Response
    {
        $query = new Project();

        $query->setTemplateQuestions(new ArrayCollection([""]));
        //Generem el formulari
        $form = $this->createForm(QuestionFormType::class, $query);

        $errorInFile = false;

        //Obtenim la petició
        $form->handleRequest($request);

        //Comprovem si el formulari s'ha entregat i es valid, en cas contrariu es que haura entrat a la pàgina només.
        if ($form->isSubmitted() && $form->isValid()) {
            $newQuestions = $form->getData();
            $projectManager = $this->entmanager->getRepository(Project::class);
            $project = $projectManager->findOneBy(['id' => $id], []);
            //Creem un auxiliar per garantitzar així que a l'hora de mostrar el resultat al usuari sols es mostraran les templates creades al moment
            $projectAux = clone $project;
            $projectAux->setTemplateQuestions(new ArrayCollection());
            foreach ($newQuestions->getTemplateQuestions() as $newQuestionString) {
                $question = new Question();
                $userManager = $this->entmanager->getRepository(User::class);
                $user = $userManager->findOneBy(['username' => 'josep.roig'], []);
                $question->setCreator($user);
                $question->setProject($project);
                $question->setTemplateQuestion($newQuestionString);

                $projectAux->addTemplateQuestion($question);
                $project->addTemplateQuestion($question);
            }

            $restAPIController = new RestAPIController();

            $responseToPetition = $restAPIController->getPossibilities($projectAux);

            if ($responseToPetition->getStatusCode() == 200) {
                $this->entmanager->persist($project);
                $this->entmanager->flush();

                return $this->renderForm("projects/templates/editgeneratedtemplates.html.twig", [
                    'possibleQueries' => $responseToPetition->getResponse()->getPossibleQueries(),
                ]);
            }
        }

        return $this->renderForm("projects/templates/createnewtemplates.html.twig", [
            'form' => $form,
            'errorInFile' => $errorInFile
        ]);
    }
}
