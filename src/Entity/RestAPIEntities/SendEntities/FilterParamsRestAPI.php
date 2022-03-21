<?php

namespace App\Entity\RestAPIEntities\SendEntities;


class FilterParamsRestAPI 
{
    private $questions;

    public function __construct(){
        $this->questions = array();
    }

    public function getQuestions() {
        return $this->questions;
    }

    public function setQuestions (array $questions) {
        $this->questions = $questions;
    }

    public function addQuestion (TemplateQuestion $question) {
        array_push($this->questions,$question);
    }
}

