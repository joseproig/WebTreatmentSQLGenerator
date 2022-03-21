<?php

namespace App\Entity\RestAPIEntities\ResponseEntities;


class PossibleQueries 
{
    private $possibleQueries;

    public function __construct(){
        $this->possibleQueries = array();
    }


    /**
     * Get the value of possibleQueries
     */ 
    public function getPossibleQueries()
    {
        return $this->possibleQueries;
    }

    /**
     * Set the value of possibleQueries
     *
     * @return  self
     */ 
    public function setPossibleQueries($possibleQueries)
    {
        $this->possibleQueries = $possibleQueries;

        return $this;
    }
}

