<?php
namespace App\Database\Seeders;

class UserSeeder extends Seeder
{
    public function seedUsersTable()
    {          
        $users = [
            ['firstname'=>'Musa', 'lastname'=>'Mphilo', 'email'=>'mphi@gmail.com', 'city'=>'Cape Town', 'zipcode'=>'7209'],
            ['firstname'=>'Micheal', 'lastname'=>'Mogale', 'email'=>'mogale@mike.me', 'city'=>'Durban', 'zipcode'=>'8965'],
            ['firstname'=>'Sekelela', 'lastname'=>'Briam', 'email'=>'seke@wits.ac.za', 'city'=>'Durban', 'zipcode'=>'6789'],
            ['firstname'=>'Kelvin', 'lastname'=>'Crust', 'email'=>'kelvin@yahoo.com', 'city'=>'JoBurg', 'zipcode'=>'6724'],
            ['firstname'=>'Macima', 'lastname'=>'Otega', 'email'=>'maxi@otega.unitysales.com', 'city'=>'Port Elizabeth', 'zipcode'=>'7897'],
        ];
        foreach($users as $user){
            $this->seedTable('users', $user);
        }        
    }
    
}