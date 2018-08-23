<?php
namespace App\Database\Migrations;

class TruncateOrDrop extends Migration
{
    /**
     * Drops a specified table.
     * 
     * @param  string $tableName
     * @return void
     */
    public function dropTable($tableName)
    {        
        $query = "DROP TABLE $tableName";    
        $this->execQuery($query);    
    }

    /**
     * Truncates a specified table.
     * 
     * @param stirng $tableName
     * @return void
     *
     */
    public function truncateTable($tableName)
    {

        $query = "TRUNCATE TABLE $tableName";
        $this->execQuery($query);
    }
}