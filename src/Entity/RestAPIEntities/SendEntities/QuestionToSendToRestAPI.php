<?php

namespace App\Entity\RestAPIEntities\SendEntities;

class QuestionToSendToRestAPI 
{
    private $filterParams;

    

    
    public function __construct(FilterParamsRestAPI $filterParams){
        $this->filterParams = $filterParams;
    }

    public function getFilterParams() {
        return $this->filterParams;
    }

    public function setFilterParams (FilterParamsRestAPI $filterParams) {
        $this->filterParams = $filterParams;
    }

}

