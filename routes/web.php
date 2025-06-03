<?php

use App\Http\Controllers\WebController;
use Illuminate\Support\Facades\Route;

// If Authentificated
Route::get('/'      , [WebController::class, "indexPage"])->name('index');
// 

// If Guest
Route::get('/login' , [WebController::class, "loginPage"])->name('login');
// 

// If Randome Link (Case Auth)
Route::any('{slug}', function () {

    return view('welcome');
    
})->where('slug', '.*');
