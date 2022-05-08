<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Project;
use App\Entity\Question;
use App\Entity\Answer;
use App\Entity\User;
use App\Entity\Password;
use App\Form\AnswersFormType;
use App\Form\PasswordFormType;
use App\Logic\RestAPIController;
use App\Form\ProjectFormType;
use App\Form\QuestionFormType;
use Symfony\Component\HttpFoundation\Request;
use App\Logic\UtilsFunctions;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class ProjectsController extends AbstractController
{

    private $entmanager;
    #private $DEFAULT_PASSWORD = "elCarbassotFaElQuePot*2021/2022";

    public function __construct(EntityManagerInterface $entmanager)
    {
        $this->entmanager = $entmanager;
    }

    #[Route('/projects', name: 'app_projects')]
    public function index(SessionInterface $session): Response
    {
        $pass = $session->get('password');

        if ($pass == true) {
            $projectManager = $this->entmanager->getRepository(Project::class);
            $projects = $projectManager->findAll();
            return $this->render('projects/index.html.twig', [
                'projects' => $projects,
            ]);
        } else {
            return $this->redirectToRoute('app_password_protection');
        }
    }

    const EXTENSION_OF_PNG = 'png';
    const EXTENSION_OF_JPG = 'jpg';
    const EXTENSION_OF_JPEG = 'jpeg';
    const EXTENSION_OF_DB = 'db';


    #[Route('/projects/create', name: 'app_newproject')]
    public function createProject(Request $request, SessionInterface $session): Response
    {
        $pass = $session->get('password');

        if ($pass == true) {
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
        } else {
            return $this->redirectToRoute('app_password_protection');
        }
    }

    #[Route('/projects/{id}', name: 'app_project_info')]
    public function projectInfo($id, SessionInterface $session): Response
    {
        $pass = $session->get('password');

        if ($pass == true) {
            $projectManager = $this->entmanager->getRepository(Project::class);
            $project = $projectManager->findOneBy(['id' => $id], []);

            return $this->render('projects/projectDetails.html.twig', [
                'project' => $project
            ]);
        } else {
            return $this->redirectToRoute('app_password_protection');
        }
    }


    #[Route('/password', name: 'app_password_protection')]
    public function password(Request $request, SessionInterface $session): Response
    {

        $password = new Password();
        //Generem el formulari
        $form = $this->createForm(PasswordFormType::class, $password);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $form->getData()->getPassword();


            if (strcmp($password, $this->getParameter('app.secretPass')) == 0) {
                $session->set('password', true);
                return $this->redirectToRoute('app_projects');
            }
        }

        return $this->renderForm('security/password.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/projects/{id}/templates/create/answers', name: 'app_generate_query_answers')]
    public function newTemplateAnswers(Request $request, $id, SessionInterface $session): Response
    {

        $pass = $session->get('password');

        if ($pass == true) {
            $question = $session->get('question');

            $formAnswer = $this->createForm(AnswersFormType::class, $question);


            $formAnswer->handleRequest($request);

            if ($formAnswer->isSubmitted() && $formAnswer->isValid()) {
                $questToUpdate = $this->entmanager->getRepository(Question::class)->findOneBy(['id' => $question->getId()], []);

                foreach ($question->getAnswers() as $ans) {
                    $questToUpdate->addAnswer($ans);
                }

                $this->entmanager->flush();

                $session->remove('question');

                $restAPIController = new RestAPIController();

                $result = $restAPIController->downloadXML($questToUpdate);



                $response = new Response();
                $file_name = 'estudy.xml';

                // Set headers
                $response->headers->set('Content-Disposition', 'attachment; filename="' . $file_name . '";');
                $response->headers->set('Content-Type', 'application/x-download');
                $response->headers->set('Content-Transfer-Encoding', 'binary');
                // Send headers before outputting anything
                $response->sendHeaders();

                $response->setContent($result);


                return $response;
            }

            $restAPIController = new RestAPIController();

            $project = $question->getProject();

            $responseToPetition = $restAPIController->getSchemaOfProject($project);

            $schema = "";
            if ($responseToPetition->getStatusCode() == 200) {
                $schema = $responseToPetition->getResponse()->getSchemaString();
            }

            return $this->renderForm("projects/templates/editgeneratedtemplates.html.twig", [
                'form' => $formAnswer,
                'schema' => $schema
            ]);
        } else {
            return $this->redirectToRoute('app_password_protection');
        }
    }




    #[Route('/projects/{id}/templates/create', name: 'app_generate_query')]
    public function generateQuery(Request $request, $id, SessionInterface $session): Response
    {

        $pass = $session->get('password');

        if ($pass == true) {
            $quest = new Question();

            //$query->setTemplateQuestions(new ArrayCollection([""]));
            //Generem el formulari
            $form = $this->createForm(QuestionFormType::class, $quest);

            $errorInFile = false;

            //Obtenim la petició
            $form->handleRequest($request);

            //Comprovem si el formulari s'ha entregat i es valid, en cas contrariu es que haura entrat a la pàgina només.
            if ($form->isSubmitted() && $form->isValid()) {
                $newQuestion = $form->getData()->getTemplateQuestion();
                $projectManager = $this->entmanager->getRepository(Project::class);
                $project = $projectManager->findOneBy(['id' => $id], []);
                //Creem un auxiliar per garantitzar així que a l'hora de mostrar el resultat al usuari sols es mostraran les templates creades al moment
                $projectAux = clone $project;
                $projectAux->setTemplateQuestions(new ArrayCollection());

                $question = new Question();
                $userManager = $this->entmanager->getRepository(User::class);
                $user = $userManager->findOneBy(['username' => 'josep.roig'], []);
                $question->setCreator($user);
                $question->setProject($project);
                $question->setTemplateQuestion($newQuestion);

                $projectAux->addTemplateQuestion($question);
                $project->addTemplateQuestion($question);

                $this->entmanager->persist($project);
                $this->entmanager->flush();


                //$projectAux = $session->get('projectAux');
                //$question = $session->get('question');
                //$session->remove('projectAux');
                //$session->remove('question');


                $restAPIController = new RestAPIController();

                $responseToPetition = $restAPIController->getPossibilities($projectAux);

                if ($responseToPetition->getStatusCode() == 200) {
                    //Només enviarem una template, per tant fem un pop perque només tindrem en compte una template, no multiples
                    $possibleQueriesOfMultipleTemplates = $responseToPetition->getResponse()->getPossibleQueries();
                    $possibleQueries = array_pop($possibleQueriesOfMultipleTemplates);

                    //$answers = [];
                    foreach ($possibleQueries as $possibleQuery) {
                        $newAnswer = new Answer();
                        //dd($possibleQuery);
                        $newAnswer->setStatement(implode(",", $possibleQuery["templateQuestions"]));
                        $newAnswer->setQuery($possibleQuery["textOfQuery"]);
                        $newAnswer->setAnswer($possibleQuery["answer"]);
                        $newAnswer->setSelected(false);
                        $question->addAnswer($newAnswer);
                        //array_push($answers, $newAnswer);
                    }

                    $session->set('question', $question);

                    return $this->redirectToRoute('app_generate_query_answers', ['id' => $id]);
                }
            }

            return $this->renderForm("projects/templates/createnewtemplates.html.twig", [
                'form' => $form,
                'errorInFile' => $errorInFile
            ]);
        } else {
            return $this->redirectToRoute('app_password_protection');
        }
    }

    #[Route('/projects/{id}/templates/{id_template}/delete', name: 'app_delete_template')]
    public function deleteTemplate($id, $id_template,  SessionInterface $session): Response
    {

        $pass = $session->get('password');

        if ($pass == true) {
            $questionManager = $this->entmanager->getRepository(Question::class);
            $question = $questionManager->findOneBy(['id' => $id_template], []);

            $this->entmanager->remove($question);
            $this->entmanager->flush();

            return $this->redirectToRoute('app_project_info', ['id' => $id]);
        } else {
            return $this->redirectToRoute('app_password_protection');
        }
    }


    #[Route('/projects/{id}/templates/{id_template}/edit/answers', name: 'app_edit_query_answers')]
    public function editTemplateAnswers(Request $request, $id, $id_template,  SessionInterface $session): Response
    {

        $pass = $session->get('password');

        if ($pass == true) {
            $question = $this->entmanager->getRepository(Question::class)->findOneBy(['id' => $id_template], []);

            $formAnswer = $this->createForm(AnswersFormType::class, $question);

            $formAnswer->handleRequest($request);

            if ($formAnswer->isSubmitted() && $formAnswer->isValid()) {
                $this->entmanager->flush();

                $questToXML = clone $question;



                //dd(array_values($questToXML->getAnswers()->toArray()));

                $restAPIController = new RestAPIController();

                $result = $restAPIController->downloadXML($questToXML);


                $response = new Response();
                $file_name = 'estudy.xml';

                // Set headers
                $response->headers->set('Content-Disposition', 'attachment; filename="' . $file_name . '";');
                $response->headers->set('Content-Type', 'application/x-download');
                $response->headers->set('Content-Transfer-Encoding', 'binary');
                // Send headers before outputting anything
                $response->sendHeaders();

                $response->setContent($result);


                return $response;
            }

            $restAPIController = new RestAPIController();

            $project = $question->getProject();

            $responseToPetition = $restAPIController->getSchemaOfProject($project);

            $schema = "";
            if ($responseToPetition->getStatusCode() == 200) {
                $schema = $responseToPetition->getResponse()->getSchemaString();
            }
            $schema = "";
            if ($responseToPetition->getStatusCode() == 200) {
                $schema = $responseToPetition->getResponse()->getSchemaString();
            }

            return $this->renderForm("projects/templates/editgeneratedtemplates.html.twig", [
                'form' => $formAnswer,
                'schema' => $schema
            ]);
        } else {
            return $this->redirectToRoute('app_password_protection');
        }
    }


    #[Route('/projects/{id}/templates/{id_template}/edit', name: 'app_edit_template')]
    public function editTemplate(Request $request, $id, $id_template, SessionInterface $session): Response
    {

        $pass = $session->get('password');

        if ($pass == true) {

            $questionManager = $this->entmanager->getRepository(Question::class);
            $question = $questionManager->findOneBy(['id' => $id_template], []);
            $question->setAnswers(new ArrayCollection());
            $form = $this->createForm(QuestionFormType::class, $question);

            //Obtenim la petició
            $form->handleRequest($request);

            //Comprovem si el formulari s'ha entregat i es valid, en cas contrariu es que haura entrat a la pàgina només.
            if ($form->isSubmitted() && $form->isValid()) {
                $question->setTemplateQuestion($form->getData()->getTemplateQuestion());
                $this->entmanager->flush();
                //return $this->redirectToRoute('app_project_info', ['id' => $id]);


                $restAPIController = new RestAPIController();

                $project = $this->entmanager->getRepository(Project::class)->findOneBy(['id' => $id], []);
                $project->addTemplateQuestion($question);
                $this->entmanager->flush();
                //Creem un auxiliar per garantitzar així que a l'hora de mostrar el resultat al usuari sols es mostraran les templates creades al moment
                $projectAux = clone $project;
                $projectAux->setTemplateQuestions(new ArrayCollection());
                $projectAux->addTemplateQuestion($question);

                $responseToPetition = $restAPIController->getPossibilities($projectAux);


                if ($responseToPetition->getStatusCode() == 200) {
                    $possibleQueriesOfMultipleTemplates = $responseToPetition->getResponse()->getPossibleQueries();
                    $possibleQueries = array_pop($possibleQueriesOfMultipleTemplates);

                    //$answers = [];
                    foreach ($possibleQueries as $possibleQuery) {
                        $newAnswer = new Answer();
                        //dd($possibleQuery);
                        $newAnswer->setStatement(implode(",", $possibleQuery["templateQuestions"]));
                        $newAnswer->setQuery($possibleQuery["textOfQuery"]);
                        $newAnswer->setAnswer($possibleQuery["answer"]);
                        $newAnswer->setSelected(false);
                        $question->addAnswer($newAnswer);
                        //array_push($answers, $newAnswer);
                    }

                    $session->set('question', $question);

                    return $this->redirectToRoute('app_generate_query_answers', ['id' => $id]);
                }
            }

            return $this->renderForm("projects/templates/edittemplates.html.twig", [
                'form' => $form
            ]);
        } else {
            return $this->redirectToRoute('app_password_protection');
        }
    }

    #[Route('/projects/{id}/delete', name: 'app_delete_project')]
    public function deleteProject($id, SessionInterface $session): Response
    {

        $pass = $session->get('password');

        if ($pass == true) {
            $projectManager = $this->entmanager->getRepository(Project::class);
            $project = $projectManager->find($id);

            $this->entmanager->remove($project);
            $this->entmanager->flush();

            return $this->redirectToRoute('app_projects');
        } else {
            return $this->redirectToRoute('app_password_protection');
        }
    }
}
