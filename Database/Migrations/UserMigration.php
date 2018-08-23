<?php
namespace App\Database\Migrations;

class User extends Migration
{
    
    /**
     * Creation of the users table.
     * @return boolean
     */
    public function createUsersTable()
    {
        $query = "CREATE TABLE IF NOT EXISTS users (
          id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
          firstname VARCHAR(30) NOT NULL,
          lastname VARCHAR(30) NOT NULL,
          email VARCHAR(40) NOT NULL,     
          UNIQUE(email)
        )";
     
        $this->execQuery($query);
        return true;
    }
}