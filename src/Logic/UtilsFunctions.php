<?php

namespace App\Logic;

use DateTime;


class UtilsFunctions
{
    private static $instance;

    public function __construct()
    {
    }

    //Singleton Pattern
    public static function getInstance():UtilsFunctions
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function moveFileFromTmpToPublic($baseDir,$fileToMove,string $extension, string $type): string
    {
       //Agafem la datetime per a ficar-li el nom al fitxer que es moura al servidor
       $d1 = new Datetime();
       $fileName =  $d1->format('U') . '.' . $extension;
       $pathDirectory = $baseDir . '/public/' . $type; 
       $fileToMove -> move(
           $pathDirectory,
           $fileName
       );
       

       return $pathDirectory . '/' . $fileName;
    }
}