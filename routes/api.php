<?php

use App\Http\Controllers\RouteImportController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

//  //  //  //  //  //  //  //  //  //  // //  //  //  //  //  //  //  //

// Login
Route::middleware('guestApi:api')->group(function () {

    Route::post('/login', [UserController::class, 'login']);
});

// Logout
Route::middleware("auth:api")->group(function () {

    Route::post('/logout', function() {

        $user   =   Auth::user()->token();
        $user->revoke();
    });
});

// Get Authentificated User Info
Route::middleware('auth:api')->group(function () {

    Route::post('/user', function(Request $request) {

        return Auth::user();
    });
});

// Check if Authentificated
Route::post('/user/isAuthentificated', [UserController::class, 'isAuthentificated']);

//  //  //  //  //  //  //  //  //  //  // //  //  //  //  //  //  //  //

// Dashboard
Route::middleware('auth:api')->group(function () {
    
    Route::post('/rtm_willayas'         ,   function()  { 

        return DB::table("RTM_Willaya")->get();
    });

    Route::post('/rtm_willayas/{DistrictNo}/rtm_cites'         ,   function($DistrictNo)  { 

        return DB::table("RTM_City")->where('DistrictNo', $DistrictNo)->get();
    });

    Route::post('/rtm_cites'            ,   function()  { 

        return DB::table("RTM_City")->get();
    });

    Route::post('/route_import'                                             ,   [RouteImportController::class           , 'index'                                   ]);
    Route::post('/route_import/store'                                       ,   [RouteImportController::class           , 'store'                                   ]);
    Route::post('/route_import/{id_route_import}/update'                    ,   [RouteImportController::class           , 'update'                                  ]);
    Route::post('/route_import/{id_route_import}/delete'                    ,   [RouteImportController::class           , 'delete'                                  ]);

    Route::post('/route/obs/route_import/{id_route_import}/details'         ,   [RouteImportController::class           , 'obsDetailsRouteImport'                   ]);
});
