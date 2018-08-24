<?php
use App\Http\Route;

Route::get("/", "HomeController@index");
Route::get("/app-test", "TestController@test");
