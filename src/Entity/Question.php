<?php

namespace App\Entity;

class Question 
{
    private $id;

    private $templateQuestion;

    private $pathToDbFile;

    public function getId() {
        return $this->id;
    }

    public function setId (int $id) {
        $this->id = $id;
    }

    public function getTemplateQuestion() {
        return $this->templateQuestion;
    }

    public function setTemplateQuestion (string $templateQuestion) {
        $this->templateQuestion = $templateQuestion;
    }

    public function getPathToDbFile() {
        return $this->pathToDbFile;
    }

    public function setPathToDBFile (string $pathToDbFile) {
        $this->pathToDbFile = $pathToDbFile;
    }
}

