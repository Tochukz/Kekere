<?php
namespace App\Database\Seeders;

use PDO;
use PDOException;

abstract class Seeder
{
    /**
     * Database connection
     */
    protected $connection;

    public function __construct()
    {
        $database = getDbCredentials();
        $dbhost = $database['dbhost'];
        $dbname = $database['dbname'];
        $dbuser = $database['dbuser'];
        $dbpass = $database['dbpass'];
        $conn = null;
        try{
            $conn =  new PDO("mysql:host=$dbhost; dbname=$dbname", $dbuser, $dbpass);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }catch(PDOException $ex){
            echo "Databse connection failed ". $ex->getMessage();
        }
        $this->connection = $conn;
    }

    /**
     * Seeds a given table with a data record.
     * 
     * @param string $tableName
     * @param array $inputArray
     * @return void
     */
    public function seedTable(string $tableName, array $inputArray)
    {      
        $query = "SELECT * FROM information_schema.columns WHERE table_schema = DATABASE() AND table_name =  '$tableName'";
        $statement = $this->connection->prepare($query);
        $statement->execute();
        $keyStr = "";
        $placeHolderStr = "";
        //$tableColumnInfo = $statement->fetchAll(PDO::FETCH_ASSOC);        
        while($column = $statement->fetch(PDO::FETCH_ASSOC)){
            if($column['COLUMN_KEY'] == 'PRI' || $column ['EXTRA'] == 'auto_increment'){
                continue;
            }           
            $keyStr .= "`".$column['COLUMN_NAME']."`,";
            $placeHolderStr .= ":".$column['COLUMN_NAME'].",";             
        }
        $keys = chop($keyStr, ','); 
        $placeHolders = chop($placeHolderStr, ',');            

        $query = "INSERT INTO $tableName($keys) VALUES($placeHolders)";
        $statement = $this->connection->prepare($query); 
        //var_dump($keys, $placeHolders, $inputArray); exit;           
        $statement->execute($inputArray);       
    }

    /**
     * 
     */
    public function __destruct()
    {
        $this->connection = null;

    }
}