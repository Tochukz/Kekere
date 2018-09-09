<?php
namespace App\Controllers;

class HomeController    
{
    public function index()
    {       
       return view('index');        
    }

    public function quickStart()
    {
        return view('quick-start');
    }

    public function docs()
    {
        return view('docs');
    }

    public function help()
    {
        return view('help');        
    }
    
}