<?php

namespace App\Entity\RestAPIEntities\ResponseEntities;


class PossibleSolution 
{
    private $logosQuestions;
    private $eqsplainQuestions;
    private $templateQuestions;
    private $textOfQuery;
    private $answer;

    public function __construct(){
        $this->logosQuestions = array();
        $this->eqsplainQuestions = array();
        $this->templateQuestions = array();
    }


    public function addLogosQuestion($logosQuestion) {
        array_push($logosQuestions,$logosQuestion);
    }

    public function addEQSplainQuestion($eqsplainQuestion) {
        array_push($eqsplainQuestions,$eqsplainQuestion);
    }


    public function addTemplateQuestion($templateQuestion) {
        array_push($templateQuestions,$templateQuestion);
    }

    /**
     * Get the value of logosQuestions
     */ 
    public function getLogosQuestions()
    {
        return $this->logosQuestions;
    }

    /**
     * Set the value of logosQuestions
     *
     * @return  self
     */ 
    public function setLogosQuestions($logosQuestions)
    {
        $this->logosQuestions = $logosQuestions;

        return $this;
    }

    /**
     * Get the value of eqsplainQuestions
     */ 
    public function getEqsplainQuestions()
    {
        return $this->eqsplainQuestions;
    }

    /**
     * Set the value of eqsplainQuestions
     *
     * @return  self
     */ 
    public function setEqsplainQuestions($eqsplainQuestions)
    {
        $this->eqsplainQuestions = $eqsplainQuestions;

        return $this;
    }

    /**
     * Get the value of templateQuestions
     */ 
    public function getTemplateQuestions()
    {
        return $this->templateQuestions;
    }

    /**
     * Set the value of templateQuestions
     *
     * @return  self
     */ 
    public function setTemplateQuestions($templateQuestions)
    {
        $this->templateQuestions = $templateQuestions;

        return $this;
    }

    /**
     * Get the value of textOfQuery
     */ 
    public function getTextOfQuery()
    {
        return $this->textOfQuery;
    }

    /**
     * Set the value of textOfQuery
     *
     * @return  self
     */ 
    public function setTextOfQuery($textOfQuery)
    {
        $this->textOfQuery = $textOfQuery;

        return $this;
    }

    /**
     * Get the value of answer
     */ 
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * Set the value of answer
     *
     * @return  self
     */ 
    public function setAnswer($answer)
    {
        $this->answer = $answer;

        return $this;
    }
}

