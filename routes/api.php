<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\JourneeController;
use App\Http\Controllers\JourneyPlanController;
use App\Http\Controllers\RouteImportController;
use App\Http\Controllers\UserController;
use App\Models\JourneyPlan;
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
    
    //

    Route::post('/rtm_willayas'         ,   function()  { 

        return DB::table("RTM_Willaya")->get();
    });

    Route::post('/rtm_willayas/{DistrictNo}/rtm_cites'         ,   function($DistrictNo)  { 

        return DB::table("RTM_City")->where('DistrictNo', $DistrictNo)->get();
    });

    Route::post('/rtm_cites'            ,   function()  { 

        return DB::table("RTM_City")->get();
    });

    //

    Route::post('/route_import/set_willayas_cites'                          ,   [RouteImportController::class           , 'setWillayasCites'                        ]);    

    Route::post('/route_import'                                             ,   [RouteImportController::class           , 'index'                                   ]);
    Route::post('/route_import/store'                                       ,   [RouteImportController::class           , 'store'                                   ]);
    Route::post('/route_import/{id}/update'                                 ,   [RouteImportController::class           , 'update'                                  ]);
    Route::post('/route_import/{id}/delete'                                 ,   [RouteImportController::class           , 'delete'                                  ]);

    Route::post('/route_import/{id}/journey_plan'                           ,   [RouteImportController::class           , 'journeyPlan'                             ]);
    Route::post('/route_import/{id}/journees'                               ,   [RouteImportController::class           , 'journees'                                ]);

    Route::post('/route_import/{id}/journey_plan/store'                     ,   [JourneyPlanController::class           , 'storeJourneyPlan'                        ]);
    Route::post('/route_import/{id}/journey_plan/{JPlan}/delete'            ,   [JourneyPlanController::class           , 'deleteJourneyPlan'                       ]);

    Route::post('/route_import/{id}/journees/store'                         ,   [JourneeController::class               , 'storeJournee'                            ]);
    Route::post('/route_import/{id}/journees/{Journee}/delete'              ,   [JourneeController::class               , 'deleteJournee'                           ]);

    Route::post('/route_import/{id_route_import}/clients/store'             ,   [ClientController::class                , 'storeClient'                             ]);
    Route::post('/route_import/{id_route_import}/clients/{id}/update'       ,   [ClientController::class                , 'updateClient'                            ]);
    Route::post('/route_import/{id_route_import}/clients/{id}/delete'       ,   [ClientController::class                , 'deleteClient'                            ]);
    Route::post('/route_import/{id_route_import}/clients/change_route'      ,   [ClientController::class                , 'changeRouteClients'                      ]);

    Route::post('/route/obs/route_import/{id}/details'                      ,   [RouteImportController::class           , 'obsDetailsRouteImport'                   ]);
});
