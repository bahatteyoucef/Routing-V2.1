<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WebController extends Controller
{
    
    public function loginPage(Request $request) {
        return view('welcome');
    }

    public function indexPage(Request $request) {
        return view('welcome');
    }

    public function slugPage(Request $request) {
        return view('welcome');
    }
}
