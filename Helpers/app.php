<?php
/** 
 * @package Kekere Framework (23/08/2018)
 * @version 0.0.1
 * @license https://opensource.org/licenses/MIT
 * @author Tochukwu Nwachukwu <truetochukz@gmail.com> 
 * @link http://kekere.tochukwu.xyz 
 */


/**
 * Requires a specified template for display.
 * The elements of the array passed to the template becomes individual variables accessable in the template.
 * 
 * @param string $templateChain
 * @param array $array
 * @return void
 */
function view($templateChain, $array = null){  
    if($array != null){
        foreach($array as $key=>$value){          
            $$key = $value;
        }
    }
    $template = str_replace('.', '/', $templateChain);
    require_once(__DIR__.'/../Views/'.$template.'.php');
}


/**
 * Build an array of all the files in a given directory.
 * 
 * @param string $dirName
 * @return array
 */
function getAllFiles($dirName){
    $files = [];
    try{
        $dirHandle = opendir( __DIR__.'/../'.$dirName);
        while($fileOrDir = readdir($dirHandle)){
            if(is_dir($fileOrDir)){
                continue;
            }
            $files[] = $dirName.'/'.$fileOrDir;
        }
        closedir($dirHandle);
    }catch(\Exception $ex){
       echo $ex->getMessage();
       exit;
    }
    return $files;
}

/**
 * Build an array of all the class defined in a given diretory
 * It is assumed that your class name and the name of the file holding the class is the same e.g User and User.php.
 * 
 * @param string $dirName
 * @return array
 */
function getAllClasses(string $dirName){
    $files = getAllFiles($dirName);
    $classes = array_map(function($file){
        return str_replace("/", "\\", substr($file, 0, strlen($file)-4));
    }, $files);
    return $classes;
}

/**
 * Returns the settings.json file content as an array
 * @return array
 */
function getSettings()
{
    $settings = __DIR__."/../settings.json";
    $settingsArray = json_decode(file_get_contents($settings), true);
    return $settingsArray;
}

/**
 * Accessesses the database connection credential by reading the setting.conf file.
 * returns an associative array with the connection credetions.
 * 
 * @return array
 */
function getDbCredentials()
{    
    return getSettings()['database'];
}

/**
 * Returns an array of the commands registered in the settings.json file.
 * 
 * @return array
 */
function getCommands()
{
    return getSettings()['commands'];
}

/**
 * Returns the application's home directory
 * 
 * @return tring
 */
function appDir()
{
    return __DIR__."/../";
}

/**
 * Provides help for a given cli command.
 */
function getHelp($command)
{
    $message = "";
    switch($command){
        case 'migrate':
            $message .= "Syntax:\n \t php console.php migrate MigrationClassName \n";
            $message .= "Details:\n";
            $message .= "\t Runs a specific migration class defined in Database/Migrations directory\n";
            $message .= "\t The migration class must extend the  \Database\Migrations\Migration abstract class\n";
            $message .= "\t The table creation method in a migration class must follow the naming convention: create[tableName]Table\n";            
            break;
        case 'migrate-all':
            $message .= "Syntax:\n \t php console.php migrate-all \n";
            $message .= "Details:\n";
            $message .= "\t Runs all the migration classes defined in Database/Migrations directory\n";
            $message .= "\t A migration class must extend the  \Database\Migrations\Migration abstract class\n";
            $message .= "\t The table creation method in a migration class must follow the naming convention: create[tableName]Table\n";            
            break;
        case 'seed':
           $message .= "Syntax:\n \t php console.php seed SeederClassName\n";
           $message .= "Details:\n";
           $message .= "\t Runs the seeder class specified by the name [SeederClassName]\n";
           $message .= "\t A Seeder class must extend Database\Seeders\Seeder abstract class\n";
           $message .= "\t A seeder class must defined a method following the namiing convention seed[tableName]Table\n";  
           break;         
        case 'seed-all':
           $message .= "Syntax:\n \t php console.php seed-all\n";
           $message .= "Details:\n";
           $message .= "\t Runs all the Seeder classes defined in the Database/Migration/Seeders directory\n";
           break;
        case 'drop-table':
            $message .= "Syntax:\n \t php console.php drop-table tableName\n"; 
            $message .= "Details:\n";   
            $message .= "\t Drops the specified table\n"; 
            break;
        case 'truncate-table':
            $message .= "Syntax:\n \t php console.php drop-table tableName\n"; 
            $message .= "Details:\n";   
            $message .= "\t Drops the specified table\n"; 
            break;
        default:
            $message .= "No help found\n"; 
    }
    return $message;
}

/**
 * A wrapper over PHP var_dump with pretty prints.
 * 
 * @param mixed $var
 */
function dump($var)
{
    echo '<br /><pre>';
    var_dump($var);
    echo'</pre><hr />';
}

/**
 *   A wrapper over PHP var_dump with pretty prints.
 * Unlike dump(), dumpX() terminates the script.
 * 
 * @param type $var
 */
function dumpX($var)
{
      dump($var);
      exit;
}