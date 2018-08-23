<?php
/** 
 * @package Kekere Framework (23/08/2018)
 * @version 0.0.1
 * @license https://opensource.org/licenses/MIT
 * @author Tochukwu Nwachukwu <truetochukz@gmail.com> 
 * @link http://kekere.tochukwu.xyz 
 */

namespace App\Models;

use \PDO;

/**
 * The model abstract class defines basic CRUD operation on database table.
 */
abstract class Model
{
    /**
     * Name of the database table associated with the model.
     * 
     * @var string table name
     */
    protected $table;

     /**
     * @var resource database connection
     */
    protected $connection;


    /**
     * The databse connection created duing class instatiation.
     * @return void
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
     * Select a record whose id is $id from a the table whose name is $table .
     * 
     * @param int $id
     * @return stdClass
     */
    public function find(int $id)
    {
       $this->setTableName();
       $table = $this->table;
       $query = "SELECT * FROM $table WHERE id = :id LIMIT 1";
       $statement = $this->connection->prepare($query);
       $statement->execute(['id'=>$id]);
       $resultArray =  $statement->fetchAll(PDO::FETCH_ASSOC);      
       $result = ($resultArray)? (object) $resultArray[0] : null;  
       return $result;
    }

    /**
     * Select all the records from the table specified by the $table property.
     * 
     * @return array
     */
    public function get(int $x = 0)
    {
       $this->setTableName();
       $table = $this->table;
       if($x == 0){
           $query = "SELECT * FROM $table"; 
       }else{
           $query = "SELECT * FROM $table LIMIT $x"; 
       }
         
       $statement = $this->connection->prepare($query);
       $statement->execute();
       $resultArray =  $statement->fetchAll(PDO::FETCH_ASSOC);
       $results = array_map(function($record){
           return (object) $record;
       }, $resultArray);

       return $results;
    }


    /**
     * Retuns a number of  record from a database table specified by the $table property.
     * 
     * @return \stdClass
     */
    public function take(int $x){
        return $this->get($x);    
    }
    
    public function getWhere(string $key, string $value)
    {
        $this->setTableName();
        $table = $this->table;
        $query = "SELECT * FROM $table WHERE `$key` = :inputVal";
        $statement = $this->connection->prepare($query);
        $statement->execute(['inputVal'=>$value]);
        $resultArray =  $statement->fetchAll(PDO::FETCH_ASSOC);
        $results = array_map(function($record){
            return (object) $record;
        }, $resultArray);
 
        return $results;
    }

    public function update()
    {

    }
    
    /**
     * Sets the $table property if it is not defined in the derived/child class.
     * 
     * @return void
     */
    protected function setTableName()
    {
        if(isset($this->table)){
            return true;
        }
        $classFullName = get_class($this);
        $upperCases = ['A','B','C','D','E','F','G','H','I','J','K','L','M',
        'N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
        $lowerCases = array_map(function($letter){ 
            return '_'.strtolower($letter); 
        }, $upperCases);
        $segments= explode("\\", $classFullName);
        $className = $segments[count($segments)-1];    
        $tableName = trim(str_replace($upperCases, $lowerCases, $className), "\\_") . "s";    
        $this->table = $tableName;      
    }
     /**
     * Free the connection resource.
     */
    public function __destruct()
    {
        $this->connection = null;
    }

}