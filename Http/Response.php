<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http;

/**
 * Description of Response
 *
 * @author chucks
 */
class Response {
    public static function view($template){           
        $view = appDir('Views/'.$template.'.php');    
        $fileHandle = fopen($view, 'r');
        $layout = null;
         do{
               $line = fgets($fileHandle);
               if(stristr($line, "@Layout")){
                   $start = 1+strpos($line, "(");
                   $end = strpos($line, ")");
                   $length = $end - $start;
                   $layoutChain = trim(substr($line, $start, $length), "'");
                   $layout = str_replace('.', '/', $layoutChain);
                   return $layout;
               }
               $firstLine =  true;
         }while(!feof($fileHandle) && $firstLine == false);             
         return  $layout;                        
    }        
}
