<?php

namespace App\Entity;

class TemplateQuestion 
{
   
    private $templateQuestion;

    public function getTemplateQuestion() {
        return $this->templateQuestion;
    }

    public function setTemplateQuestion (string $templateQuestion) {
        $this->templateQuestion = $templateQuestion;
    }

}

