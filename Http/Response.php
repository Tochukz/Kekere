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
    public static function view($template)
    {                   
        $layout = static::getLayout($template);
        if(!$layout){
            return appDir('Views/'.$template.'.php');    
        }        
        $partsAndInludes = static::getIncludes($layout); 
        return $partsAndInludes;         
    }   
    
    protected static function getLayout(string $template)
    {
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
    
    protected static function getIncludes(string $layout)
    {
        $fileContent = file_get_contents(appDir('Views/'.$layout).'.php');
        $parts = explode("@", $fileContent);
        $includes = [];               
        foreach($parts as $part){            
             if(stristr($part, "render(") || stristr($part, "partial(")){
                 $start = 2+stripos($part, "(");
                 $end = stripos($part, ")") - 1;
                 $length = $end - $start;
                 $include = substr($part, $start, $length);
                 $includes[] = $include;
             }                         
        }     
        return ['includes'=>$includes, 'parts'=>$parts]; 
    }
}
