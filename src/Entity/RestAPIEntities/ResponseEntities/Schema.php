<?php

namespace App\Entity\RestAPIEntities\ResponseEntities;


class Schema
{
    private $schemaString;




    /**
     * Get the value of schemaString
     */
    public function getSchemaString()
    {
        return $this->schemaString;
    }

    /**
     * Set the value of schemaString
     *
     * @return  self
     */
    public function setSchemaString($schemaString)
    {
        $this->schemaString = $schemaString;

        return $this;
    }
}
