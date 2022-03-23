<?php

namespace App\Logic;
use Symfony\Component\HttpClient\HttpClient;
use App\Entity\Question;
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

    public function getPossibilities(Question $question): ResponseOfAPI
    {
        $filterParamsRestApi = new FilterParamsRestAPI();
        foreach ($question->getTemplateQuestions() as $questionTemplate){
            $templateQuestion = new TemplateQuestion($questionTemplate);
            $filterParamsRestApi->addQuestion($templateQuestion);
        }
        $questionToSendToRestAPI = new QuestionToSendToRestAPI($filterParamsRestApi);

        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        $data = [
            'file' => DataPart::fromPath($question->getPathToDbFile()),
            'config' =>  $serializer->serialize($questionToSendToRestAPI,'json'),
        ];
        $formData = new FormDataPart($data);

        $response = $this->client->request('POST', 'http://localhost:8086/getPossibilities',[
            'headers' => $formData->getPreparedHeaders()->toArray(),
            'body' => $formData->bodyToIterable(),
        ]);

        if ($response->getStatusCode() == 200) {
            $responseAdaptedObject = $serializer->deserialize($response->getContent(), PossibleQueries::class, 'json');
            $responseOfAPI = new ResponseOfAPI($responseAdaptedObject,$response->getStatusCode());
        } else {
            $responseOfAPI = new ResponseOfAPI(null,$response->getStatusCode());
        }

        return $responseOfAPI;
    }
}