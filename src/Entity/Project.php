<?php

namespace App\Entity;


class Project 
{
    private $id;

    

    private $pathToDbFile;

    private $name;

    private $description;

    /**
     * Get the value of pathToDbFile
     */ 
    public function getPathToDbFile()
    {
        return $this->pathToDbFile;
    }

    /**
     * Set the value of pathToDbFile
     *
     * @return  self
     */ 
    public function setPathToDbFile($pathToDbFile)
    {
        $this->pathToDbFile = $pathToDbFile;

        return $this;
    }

    /**
     * Get the value of name
     */ 
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */ 
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of description
     */ 
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set the value of description
     *
     * @return  self
     */ 
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }
}

