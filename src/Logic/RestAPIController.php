<?php

namespace App\Logic;

use Symfony\Component\HttpClient\HttpClient;
use App\Entity\Question;
use App\Entity\Project;
use App\Entity\RestAPIEntities\ResponseEntities\PossibleQueries;
use App\Entity\RestAPIEntities\ResponseEntities\ResponseOfAPI;
use App\Entity\RestAPIEntities\SendEntities\FilterParamsRestAPI;
use App\Entity\RestAPIEntities\SendEntities\QuestionToSendToRestAPI;
use App\Entity\RestAPIEntities\SendEntities\TemplateQuestion;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\Multipart\FormDataPart;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Contracts\HttpClient\ResponseInterface;

class RestAPIController
{
    private $client;

    public function __construct()
    {
        $this->client = HttpClient::create();
    }

    public function getPossibilities(Project $project): ResponseOfAPI
    {
        $filterParamsRestApi = new FilterParamsRestAPI();


        foreach ($project->getTemplateQuestions() as $questionTemplate) {
            $templateQuestion = new TemplateQuestion($questionTemplate->getTemplateQuestion());
            $filterParamsRestApi->addQuestion($templateQuestion);
        }

        $questionToSendToRestAPI = new QuestionToSendToRestAPI($filterParamsRestApi);

        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        $data = [
            'file' => DataPart::fromPath($project->getPathToDbFile()),
            'config' =>  $serializer->serialize($questionToSendToRestAPI, 'json'),
        ];
        $formData = new FormDataPart($data);

        $response = $this->client->request('POST', 'http://localhost:8086/answers', [
            'headers' => $formData->getPreparedHeaders()->toArray(),
            'body' => $formData->bodyToIterable(),
        ]);

        if ($response->getStatusCode() == 200) {
            $responseAdaptedObject = $serializer->deserialize($response->getContent(), PossibleQueries::class, 'json');
            $responseOfAPI = new ResponseOfAPI($responseAdaptedObject, $response->getStatusCode());
        } else {
            $responseOfAPI = new ResponseOfAPI(null, $response->getStatusCode());
        }

        return $responseOfAPI;
    }


    public function downloadXML(Question $questionXML): String
    {
        $opts = array(
            'http' =>
            array(
                'method'  => 'POST',
                'header'  => "Content-Type: application/json\r\n",
                'content' => json_encode($questionXML)
            )
        );

        //dd("question: " . json_encode($questToXML));

        $context  = stream_context_create($opts);
        $result = file_get_contents('http://localhost:8086/answers/download', false, $context);

        return $result;
    }
}
