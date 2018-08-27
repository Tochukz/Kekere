<?php
namespace App\Database\Seeders;

class OrderSeeder extends Seeder
{
    public function seedOrdersTable()
    {          
        $orders = [
            ['user_id'=> "2", 'date'=>$this->currentTime()],
            ['user_id'=> "3", 'date'=>$this->currentTime()],
            ['user_id'=> "5", 'date'=>$this->currentTime()],
        ];
        foreach($orders as $order){
            $this->seedTable('orders', $order);
        }        
    }
    
    public function currentTime() 
   {
        return date("Y-m-d H:i:s");
    }
    
}