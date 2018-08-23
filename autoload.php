<?php
spl_autoload_register(function($className){    
   if(stristr($className, 'pdo')){
      return true;
   }  
   $classDir = str_replace("\\", "/", str_replace("App\\", "", $className));   
   require_once($classDir.'.php');
});

/**
 * Loading all the helper functions defined in the files under the Helpers directory.
 * This will make the functions accessible throughout the application.
 */
function loadHelpers()
{
    $helperDir = __DIR__."/Helpers";
    $dirHandle = opendir($helperDir);    
    while($fileOrDir = readdir($dirHandle)){
        if(!is_dir($fileOrDir)){        
            $fileDir = $helperDir.'/'.$fileOrDir;
            require_once($fileDir);
        }
    }

}

loadHelpers();

