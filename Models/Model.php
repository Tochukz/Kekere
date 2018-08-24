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
    protected $connection = null;


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
       try{
           $conn = new PDO("mysql:host=$dbhost; dbname=$dbname", $dbuser, $dbpass);
           $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
       }catch(PDOException $ex){
          echo "Connection failed: ".$ex->getMessage();
       }
       
       $this->connection = $conn;
       $this->setTableName();
    }  

    
    /**
     *  Defines an array property for the desired table fields.
     *  
     * @param array $fields
     * @return $this
     */
    public function select(array $fields)
    {
         $this->selectedFields = $fields;
         return $this;    
    }
    
    /**
     * Select a record whose id is $id from a the table whose name is $table .
     * 
     * @param int $id
     * @return stdClass
     */
    public function find(int $id)
    {
       $table = $this->table;
       $selectedFields = "*";
       if(isset($this->selectedFields)){  
           $selected = $this->selectedFields;
           if(!in_array('id', $selected)){
                $selected[] = 'id';
           } 
           $selectedFields = implode(',',  $selected );
       }
       $query = "SELECT $selectedFields FROM $table WHERE id = :id LIMIT 1";
       $statement = $this->connection->prepare($query);
       $statement->execute(['id'=>$id]);
       $resultArray =  $statement->fetchAll(PDO::FETCH_ASSOC);             
       $results = array_map(function($fields){         
           $model = clone $this;
           foreach($fields as $key=>$value){
               $model->$key = $value;
           }
           return $model;
       }, $resultArray);
       $result = ($results)?  $results [0] : null;  
       return $result;
    }

    /**
     * Select all the records from the table specified by the $table property.
     * 
     * @return array
     */
    public function get(int $limit = 0)
    {     
       $table = $this->table;
       $selectedFields = "*";
       if(isset($this->selectedFields)){
           $selected = $this->selectedFields;
           if(!in_array('id', $selected)){
                $selected[] = 'id';
           } 
           $selectedFields = implode(',',  $selected );
       }
       if($x == 0){
           $query = "SELECT  $selectedFields  FROM $table"; 
       }else{
           $query = "SELECT $selectedFields   FROM $table LIMIT $limit"; 
       }         
       $statement = $this->connection->prepare($query);
       $statement->execute();
       $resultArray =  $statement->fetchAll(PDO::FETCH_ASSOC);
       $results = array_map(function($fields){         
           $model = clone $this;
           foreach($fields as $key=>$value){
               $model->$key = $value;
           }
           return $model;
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
    
    /**
     * Query the database for records that match the given key value pair
     * 
     * @param string $key
     * @param string $value
     * @return array An array of App\Models\Model objects
     */
    public function getWhere(string $key, string $value)
    {       
        $table = $this->table;
        $query = "SELECT * FROM $table WHERE `$key` = :inputVal";
        $statement = $this->connection->prepare($query);
        $statement->execute(['inputVal'=>$value]);
        $resultArray =  $statement->fetchAll(PDO::FETCH_ASSOC);        
        $results = array_map(function($fields){
            $model = clone $this;
            foreach($fields as $key=>$value){
                $model->$key = $value;
           }
           return $model;         
        }, $resultArray);
 
        return $results;
    }

    public function update(array $paramArray)
    {        
        $keys = array_keys($paramArray);
        $keyString = " ";
        foreach($keys as $key){
             $keyString .= "$key = :$key,";
        }
        $keyStr = trim($keyString, ',');
        $table = $this->table;    
        $id = $this->id;
        $query = "UPDATE $table SET $keyStr WHERE id = $id ";        
        $statement = $this->connection->prepare($query);   
        $statement->execute($paramArray);
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