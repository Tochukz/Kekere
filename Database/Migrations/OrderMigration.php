<?php
namespace App\Database\Migrations;

class OrderMigration extends Migration
{
    /**
     * Creation of the orders table.
     * 
     * @return boolean
     */
     public function createOrdersTable()
    {
        $query  = "CREATE TABLE IF NOT EXISTS orders(
             id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
             user_id INT(11) UNSIGNED NOT NULL,       
             date DATETIME,
             FOREIGN KEY(user_id) REFERENCES users(id)
         )";
        $this->execQuery($query);
        return true;
    }
    
}
