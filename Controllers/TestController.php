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
        $book = DB::table('books')->select(['title', 'author', 'price'])->find(3);
        //$books = DB::table('books')->select(['title', 'author', 'id'])->take(2);   
        dump($book);
        
        $book->update(['price'=>3799, 'title'=>'bbuild Coonstruct']);
        $bookX = DB::table('books')->select(['title', 'author', 'price'])->find(3);
        dump($bookX);
        
    }
}
