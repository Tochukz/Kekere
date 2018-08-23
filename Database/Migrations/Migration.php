<?php
/** 
 * @package Kekere Framework (23/08/2018)
 * @version 0.0.1
 * @license https://opensource.org/licenses/MIT
 * @author Tochukwu Nwachukwu <truetochukz@gmail.com> 
 * @link http://kekere.tochukwu.xyz 
 */

namespace App\Database\Migrations;

use PDO;
use PDOException;

/**
 * Migration class defines methods for database operations.
 */
abstract class Migration 
{

    /**
     * @var database connection
     */
    protected $connection;


    /**
     * The database connection created during class instantiation.
     * @return void
     * 
     */
    public function __construct()
    {
       $cred = getDbCredentials();
       $dbhost = $cred['dbhost']; 
       $dbuser = $cred['dbuser'];
       $dbname = $cred['dbname'];
       $dbpass = $cred['dbpass'];         
       $conn = null;
       try{
           $conn = new PDO("mysql:host=$dbhost; dbname=$dbname", $dbuser, $dbpass);
           $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
       }catch(PDOException $ex){
          echo "Connection failed: ".$ex->getMessage();
       }
       
       $this->connection = $conn;
    }  
    


    /**
     * Runs a given query.
     * 
     * @param string $query
     * @return void;
     */
    public function execQuery(string $query)
    {
        try{
            $this->connection->exec($query);
        }catch(\PDOExeption $ex){
            echo $ex->getMessage();
        }
       
    }
   
    /**
     * Delete created tables.
     * 
     * @return void
     */
    public function rollBackTables($tableNames)
    {     
        foreach($tableNames as $tableName){
            echo "dropping $tableName ... \n";
            $query = "DROP TABLE $tableName";
            $this->execQuery($query);
            echo $tableName." dropped.\n";
        }
        exit;
    }
        

    /**
     * Free the connection resource.
     * 
     * @return void
     */
    public function __destruct()
    {
        $this->connection = null;
    }
}