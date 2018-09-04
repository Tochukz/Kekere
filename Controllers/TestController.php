<?php

namespace App\Controllers;

use App\Models\DB;

/**
 * Description of TestController
 *
 * @author chucks
 */
class TestController {
    public function test(){
        $books = DB::table('books')->select(['id', 'author', 'price'])->orderBy('price', 'ASC')->take(4);
//        //$books = DB::table('books')->select(['title', 'author', 'id'])->take(2);   
//        dump($book)  ;    
          return view('index', ['books'=>$books]);        
        
    }
    
}
