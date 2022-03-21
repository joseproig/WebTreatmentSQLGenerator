<?php

namespace App\Entity;

class Question 
{
    private $id;

    

    private $pathToDbFile;

    private $templateQuestions;

    public function __construct(){
        $this->templateQuestions = array ('');
    }

    public function getId() {
        return $this->id;
    }

    public function setId (int $id) {
        $this->id = $id;
    }

    public function getTemplateQuestions() {
        return $this->templateQuestions;
    }

    public function setTemplateQuestions (array $templateQuestions) {
        $this->templateQuestions = $templateQuestions;
    }

    public function getPathToDbFile() {
        return $this->pathToDbFile;
    }

    public function setPathToDBFile (string $pathToDbFile) {
        $this->pathToDbFile = $pathToDbFile;
    }
}

