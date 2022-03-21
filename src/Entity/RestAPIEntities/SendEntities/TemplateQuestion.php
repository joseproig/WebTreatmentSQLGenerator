<?php

namespace App\Entity\RestAPIEntities\SendEntities;

class TemplateQuestion 
{
   
    private $question;
    private $deactivateLogos;
    private $deactivateEQSPlain;


    public function __construct(string $question){
        $this->deactivateLogos = true;
        $this->deactivateEQSPlain = true;
        $this->question = $question;
    }


    public function getQuestion() {
        return $this->question;
    }

    public function setQuestion (string $question) {
        $this->question = $question;
    }

    public function getDeactivateLogos() {
        return $this->deactivateLogos;
    }

    public function setDeactivateLogos (bool $deactivateLogos) {
        $this->deactivateLogos = $deactivateLogos;
    }

    public function getDeactivateEQSPlain() {
        return $this->deactivateEQSPlain;
    }

    public function setDeactivateEQSPlain (bool $deactivateEQSPlain) {
        $this->deactivateEQSPlain = $deactivateEQSPlain;
    }
}

