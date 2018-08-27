<?php
namespace App\Console;


class SeederCommand
{
  /**
   * This will get all the seeder method defined in the seeder classes and run them to seed the database.
   * Seeder method must be defined using the naming convention: seed{TableName}Table.
   * 
   * @return void
   */
  public function seedAll()
  {
    $seederClassNames = getAllClasses("Database/Seeders");
    foreach($seederClassNames as $seederClassName){
      if($seederClassName == 'Database\Seeders\Seeder'){
        //Seeder is an abstract class, can  not be instantiated and therefore skiped
        continue;
      }
      $fullClassName = "App\\".$seederClassName;
      $this->runSeed($fullClassName);
    }
  }


  /**
   * Runs all the seeder methods of a specified seeder class.
   * The seeder method must be defined with a naming convention e.g seedUsersTable, seedOrdersTable etc.
   * 
   * @param string $seederClassName
   * @return void
   */
  public function runSeed($seederClassName)
  {    
    $seederObj = new $seederClassName();
    $seederMethods = get_class_methods($seederClassName);
    foreach($seederMethods as $seederMethod){
      //Only method that following the naming convention will be called.
      if(preg_match("/^(seed)[a-zA-Z0-9_]+(Table)$/", $seederMethod)){
        $seederObj->$seederMethod();
      }      
    }
  }

  /**
   * Runs the seeder method of a given class.
   * 
   * @param string $seederClassName
   * @return void
   */
  public function seed($seederClassName)
  {
    if(!$seederClassName){
      echo "Please specify the seeder class name\n";
    }
    $fullClassName = "App\\Database\\Seeders\\".$seederClassName;
    $this->runSeed($fullClassName);
  }

}