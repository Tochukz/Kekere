<?php
namespace App\Database\Migrations;

class OrderMigration extends Migration
{
    /**
     * Creation of the users table.
     * @return boolean
     */
    public function createOrdersable()
    {
        $query = "CREATE TABLE IF NOT EXISTS order (
          id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,           
          product_name VARCHAR(30) NOT NULL,
          description VARCHAR(100) NOT NULL,
          price FLOAT(6) NOT NULL, 
          user_id INT(11) UNSINGED NOT NULL,
          FOREIGN KEY (user_id) REFERENCES users(id)
        )";
     
        $this->execQuery($query);
        return true;
    }
    
}
