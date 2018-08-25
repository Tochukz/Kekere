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
     * @var array Selected field for table selection operation
     */
     protected $selectedFields;
     
     /**
      *
      * @var string Order for sorting of results returned   from table selection operation
      */
     protected $order;

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
      * Adds an order for sorting of records to be used in the get method.
      * 
      * @param string $field
      * @param string $order
      * @return $this
      * 
      */
     public function orderBy(string $field, string $order)
     {
         $this->order = "ORDER BY $field $order";
         return $this;
     }
     
    /**
     * Select  records from the table specified by the $table property.
     * 
     * @return array
     */
    public function get(int $limit = 0)
    {           
       $query= $this->buildSelectQuery($limit);
       $statement = $this->connection->prepare($query);
       $statement->execute();     
       $results = [];
       while($resultArray = $statement->fetch(PDO::FETCH_ASSOC)){
            $model = clone $this;
            foreach($resultArray as $key=>$value){
                $model->$key = $value;
            }
            $results[] =  $model;
       }
        return $results;
    }

    /**
     * Builds a query for database table selection operation.
     * 
     * @return string
     */
    public function buildSelectQuery(int $limit)
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
         $order = " ";
         if(isset($this->order)){
             $order = $this->order;
        }
         if($limit == 0){
            $query = "SELECT  $selectedFields  FROM $table $order"; 
        }else{
            $query = "SELECT $selectedFields   FROM $table $order LIMIT $limit"; 
        }     
        return $query;
    }
    
    /**
     * Return an array of entities of the table specified by the $table property.
     * 
     * @return array
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
   
     /**
      * Inserts a record into the database table associated with the current model.
      * 
      * @param string $paramArray
      * @return void
      */
     public function create(string $paramArray)
    {
        $table = $this->table;
        $selectQuery = "SELECT * FROM information_schema.columns WHERE table_schema = DATABASE() AND table_name =  '$table'";
        $selectStatement = $this->connection->prepare($selectQuery);
        $selectStatement->execute();
        $keyStr = "";
        $placeHolderStr = "";
        $tableColumns = $selectStatement->fetchAll(PDO::FETCH_ASSOC);         
        foreach($tableColumns as $column){
            if($column['COLUMN_KEY'] == 'PRI' || $column ['EXTRA'] == 'auto_increment'){
                continue;
            }           
            $keyStr .= "`".$column['COLUMN_NAME']."`,";
            $placeHolderStr .= ":".$column['COLUMN_NAME'].",";  
        }        
        $keys = chop($keyStr, ','); 
        $placeHolders = chop($placeHolderStr, ',');            

        $insertQuery = "INSERT INTO $table($keys) VALUES($placeHolders)";
        $insertStatement = $this->connection->prepare($insertQuery);               
        $insertStatement->execute($paramArray);     
     }
     
    /**
     * Updates a single record in the database table
     * 
     * @param array $paramArray
     * @return void
     */
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
     * 
     * @return void 
     */
    public function __destruct()
    {
        $this->connection = null;
    }

}