<?php
use App\Http\Route;

Route::get("/", "HomeController@index");
Route::get("quick-start", "HomeController@quickStart");
Route::get("docs", "HomeController@docs");
Route::get("help", "HomeController@help");
