<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\ClientTempoController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\JourneeController;
use App\Http\Controllers\JourneyPlanController;
use App\Http\Controllers\RouteImportController;
use App\Http\Controllers\RouteImportTempoController;
use App\Http\Controllers\StatisticController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserTerritoryController;

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
Route::middleware('auth:api')->group(function () {

    Route::post('/logout', function() {

        $user   =   Auth::user()->token();
        $user->revoke();
    });
});

// Get Authentificated User Info
Route::middleware(['auth:api', 'isEnabledUser'])->group(function () {

    Route::post('/user', function(Request $request) {

        return Auth::user();
    });
});

// Check if Authentificated
Route::post('/user/isAuthentificated', [UserController::class, 'isAuthentificated']);

//  //  //  //  //  //  //  //  //  //  // //  //  //  //  //  //  //  //

// Dashboard
Route::middleware(['auth:api', 'isEnabledUser'])->group(function () {

    // Get Districts
    Route::post('/route_import/{id_route_import}/districts'     ,   function($id_route_import)  { 

        return  DB::table("RTM_Willaya")
                    ->join("route_import_districts" , "RTM_Willaya.DistrictNo"          , "route_import_districts.DistrictNo")
                    ->where('route_import_districts.id_route_import', $id_route_import)
                    ->orderBy('RTM_Willaya.DistrictNameE')
                    ->get();
    });

    // 
    Route::post('/rtm_willayas/rtm_cites/details/indexedDB'     ,   function()  { 

        $willayas   =   DB::table("RTM_Willaya")->orderBy('DistrictNameE')->get();

        foreach ($willayas as $willaya) {

            $willaya->cites =   DB::table("RTM_City")->where('DistrictNo', $willaya->DistrictNo)->orderBy('CityNameE')->get();
        }

        return $willayas;
    });
    Route::post('/rtm_willayas/rtm_cites/details'               ,   function()  { 

        $willayas   =   DB::table("RTM_Willaya")->orderBy('DistrictNameE')->get();

        foreach ($willayas as $willaya) {

            $willaya->cites =   DB::table("RTM_City")->where('DistrictNo', $willaya->DistrictNo)->orderBy('CityNameE')->get();
        }

        return $willayas;
    });
    Route::post('/rtm_willayas'                                                         ,   function()                  { return DB::table("RTM_Willaya")->orderBy('DistrictNameE')->get();                             })->middleware('BackOffice');
    Route::post('/rtm_willayas/{DistrictNo}/rtm_cites'                                  ,   function($DistrictNo)       { return DB::table("RTM_City")->where('DistrictNo', $DistrictNo)->orderBy('CityNameE')->get();  });
    Route::post('/rtm_willayas/{DistrictNo}/rtm_cites/expected_clients/update'          ,   [DistrictController::class  , 'updateExpectedClients'                                                                       ])->middleware('BUManager');

    //

    // Users

    Route::post('/users'                                                                                ,   [UserController::class                  , 'index'                                   ])->middleware('BUManager');
    Route::post('/users/combo'                                                                          ,   [UserController::class                  , 'combo'                                   ])->middleware('BUManager');
    Route::post('/users/{id}/show'                                                                      ,   [UserController::class                  , 'show'                                    ]);

    Route::post('/users/store'                                                                          ,   [UserController::class                  , 'store'                                   ])->middleware('BUManager');
    Route::post('/users/{id}/update'                                                                    ,   [UserController::class                  , 'update'                                  ])->middleware('BUManager');

    Route::post('/users/pointings'                                                                      ,   [UserController::class                  , 'pointings'                               ])->middleware('BUManager');

    //

    // Route Import Tempo

    Route::post('/route_import_tempo/last'                                                              ,   [RouteImportTempoController::class      , 'lastTempo'                               ])->middleware('BUManager');
    Route::post('/route_import_tempo/store'                                                             ,   [RouteImportTempoController::class      , 'store'                                   ])->middleware('BUManager');
    Route::post('/route_import_tempo/file'                                                              ,   [RouteImportTempoController::class      , 'getFile'                                 ])->middleware('BUManager');

    //

    Route::post('/route_import_tempo/{id_route_import_tempo}/clients_tempo'                             ,   [ClientTempoController::class           , 'clients'                                 ])->middleware('BUManager');
    Route::post('/route_import_tempo/{id_route_import_tempo}/clients_tempo/store'                       ,   [ClientTempoController::class           , 'storeClients'                            ])->middleware('BUManager');
    Route::post('/route_import_tempo/{id_route_import_tempo}/clients_tempo/{id}/update'                 ,   [ClientTempoController::class           , 'updateClient'                            ])->middleware('BUManager');
    Route::post('/route_import_tempo/{id_route_import_tempo}/clients_tempo/{id}/delete'                 ,   [ClientTempoController::class           , 'deleteClient'                            ])->middleware('BUManager');

    Route::post('/clients_tempo/resume/update'                                                          ,   [ClientTempoController::class           , 'updateResumeClients'                     ])->middleware('BUManager');

    Route::post('/route_import_tempo/{id_route_import_tempo}/clients_tempo/doubles'                     ,   [ClientTempoController::class           , 'getDoublesClients'                       ])->middleware('BUManager');
    Route::post('/route_import_tempo/{id_route_import_tempo}/clients_tempo/doubles/Tel'                 ,   [ClientTempoController::class           , 'getDoublesTelClients'                    ])->middleware('BUManager');
    Route::post('/route_import_tempo/{id_route_import_tempo}/clients_tempo/doubles/CustomerCode'        ,   [ClientTempoController::class           , 'getDoublesCustomerCodeClients'           ])->middleware('BUManager');
    Route::post('/route_import_tempo/{id_route_import_tempo}/clients_tempo/doubles/CustomerNameE'       ,   [ClientTempoController::class           , 'getDoublesCustomerNameEClients'          ])->middleware('BUManager');
    Route::post('/route_import_tempo/{id_route_import_tempo}/clients_tempo/doubles/GPS'                 ,   [ClientTempoController::class           , 'getDoublesGPSClients'                    ])->middleware('BUManager');

    //

    // Route Import
    Route::post('/route_import'                                                                         ,   [RouteImportController::class           , 'index'                                   ])->middleware('Viewer');
    Route::post('/route_import/combo'                                                                   ,   [RouteImportController::class           , 'combo'                                   ])->middleware('Viewer');
    Route::post('/route_import/store'                                                                   ,   [RouteImportController::class           , 'store'                                   ])->middleware('BUManager');
    Route::post('/route_import/{id}/update'                                                             ,   [RouteImportController::class           , 'update'                                  ])->middleware('BackOffice');
    Route::post('/route_import/{id}/delete'                                                             ,   [RouteImportController::class           , 'delete'                                  ])->middleware('SuperAdmin');
    
    Route::post('/route_import/{id}/show'                                                               ,   [RouteImportController::class           , 'show'                                    ])->middleware('BUManager');
    Route::post('/route_import/{id}/indexedDB/show'                                                     ,   [RouteImportController::class           , 'indexedDBShow'                           ])->middleware('BUManager');

    Route::post('/route_import/{id}/clients'                                                            ,   [RouteImportController::class           , 'clients'                                 ])->middleware('Viewer');

    Route::post('/route_import/header'                                                                  ,   [RouteImportController::class           , 'headerRouteImports'                      ])->middleware('Viewer');
    Route::post('/route_import/index'                                                                   ,   [RouteImportController::class           , 'indexRouteImports'                       ])->middleware('Viewer');

    Route::post('/route_import/{id}/users/frontOffice'                                                  ,   [RouteImportController::class           , 'frontOffice'                             ])->middleware('BackOffice');
    Route::post('/route_import/{id}/users'                                                              ,   [RouteImportController::class           , 'users'                                   ])->middleware('BackOffice');

    //

    Route::post('/route_import/{id_route_import}/clients/doubles'                                       ,   [ClientController::class                , 'getDoublesClients'                       ])->middleware('BackOffice');
    Route::post('/route_import/{id_route_import}/clients/doubles/Tel'                                   ,   [ClientController::class                , 'getDoublesTelClients'                    ])->middleware('BackOffice');
    Route::post('/route_import/{id_route_import}/clients/doubles/CustomerCode'                          ,   [ClientController::class                , 'getDoublesCustomerCodeClients'           ])->middleware('BackOffice');
    Route::post('/route_import/{id_route_import}/clients/doubles/CustomerNameE'                         ,   [ClientController::class                , 'getDoublesCustomerNameEClients'          ])->middleware('BackOffice');
    Route::post('/route_import/{id_route_import}/clients/doubles/GPS'                                   ,   [ClientController::class                , 'getDoublesGPSClients'                    ])->middleware('BackOffice');

    //

    Route::post('/route_import/{id}/user_territories'                                                   ,   [RouteImportController::class           , 'userTerritory'                           ])->middleware('Viewer');
    Route::post('/route_import/{id}/user_territories/util'                                              ,   [RouteImportController::class           , 'userTerritoryUtil'                       ])->middleware('Viewer');

    Route::post('/route_import/{id}/user_territories/store'                                             ,   [UserTerritoryController::class         , 'storeUserTerritory'                      ])->middleware('Viewer');
    Route::post('/route_import/{id}/user_territories/{id_user_territory}/update'                        ,   [UserTerritoryController::class         , 'updateUserTerritory'                     ])->middleware('Viewer');
    Route::post('/route_import/{id}/user_territories/{id_user_territory}/delete'                        ,   [UserTerritoryController::class         , 'deleteUserTerritory'                     ])->middleware('Viewer');

    //

    Route::post('/route_import/{id}/journey_plan'                                                       ,   [RouteImportController::class           , 'journeyPlan'                             ])->middleware('Viewer');
    Route::post('/route_import/{id}/journey_plan/util'                                                  ,   [RouteImportController::class           , 'journeyPlanUtil'                         ])->middleware('Viewer');

    Route::post('/route_import/{id}/journey_plan/store'                                                 ,   [JourneyPlanController::class           , 'storeJourneyPlan'                        ])->middleware('Viewer');
    Route::post('/route_import/{id}/journey_plan/{id_journey_plan}/update'                              ,   [JourneyPlanController::class           , 'updateJourneyPlan'                       ])->middleware('Viewer');
    Route::post('/route_import/{id}/journey_plan/{id_journey_plan}/delete'                              ,   [JourneyPlanController::class           , 'deleteJourneyPlan'                       ])->middleware('Viewer');

    //

    Route::post('/route_import/{id}/journees'                                                           ,   [RouteImportController::class           , 'journees'                                ])->middleware('Viewer');
    Route::post('/route_import/{id}/journees/util'                                                      ,   [RouteImportController::class           , 'journeesUtil'                            ])->middleware('Viewer');

    Route::post('/route_import/{id}/journees/store'                                                     ,   [JourneeController::class               , 'storeJournee'                            ])->middleware('Viewer');
    Route::post('/route_import/{id}/journees/{id_journee}/update'                                       ,   [JourneeController::class               , 'updateJournee'                           ])->middleware('Viewer');
    Route::post('/route_import/{id}/journees/{id_journee}/delete'                                       ,   [JourneeController::class               , 'deleteJournee'                           ])->middleware('Viewer');

    //

    Route::post('/route_import/{id_route_import}/clients/{id}/show'                                     ,   [ClientController::class                , 'showClient'                              ]);

    Route::post('/route_import/{id_route_import}/clients/store'                                         ,   [ClientController::class                , 'storeClient'                             ]);
    Route::post('/route_import/{id_route_import}/clients/{id}/update'                                   ,   [ClientController::class                , 'updateClient'                            ]);
    Route::post('/route_import/{id_route_import}/clients/{id}/delete'                                   ,   [ClientController::class                , 'deleteClient'                            ])->middleware('SuperAdmin');

    Route::post('/route_import/{id_route_import}/clients/change_route'                                  ,   [ClientController::class                , 'changeRouteClients'                      ])->middleware('BackOffice');
    Route::post('/route_import/{id_route_import}/clients/delete'                                        ,   [ClientController::class                , 'deleteClients'                           ])->middleware('SuperAdmin');

    Route::post('/clients/resume/update'                                                                ,   [ClientController::class                , 'updateResumeClients'                     ])->middleware('BackOffice');

    Route::post('/route/obs/route_import/{id}/details'                                                  ,   [RouteImportController::class           , 'obsDetailsRouteImport'                   ])->middleware('BackOffice');
    Route::post('/route/obs/route_import/{id}/details/for_front_office'                                 ,   [RouteImportController::class           , 'obsDetailsRouteImportFrontOffice'        ])->middleware('FrontOffice');

    //

    Route::post('/route_import/{id_route_import}/clients/by_status'                                     ,   [RouteImportController::class           , 'clientsByStatus'                         ])->middleware('FrontOffice');

    //

    Route::post('/route_import/stats'                                                                   ,   [RouteImportController::class           , 'statsRouteImports'                       ])->middleware('Viewer');

    //

    Route::post('/statistics/standard'                                                                  ,   [StatisticController::class             , 'statsDetails'                            ])->middleware('Viewer');
    Route::post('/statistics/self-service'                                                              ,   [StatisticController::class             , 'statisticsDetails'                       ])->middleware('Viewer');

    //

    Route::post('/route_import/all_data'                                                                ,   [RouteImportController::class           , 'downloadData'                            ])->middleware('Viewer');
    Route::post('/route_import/all_data/images'                                                         ,   [RouteImportController::class           , 'downloadImages'                          ])->middleware('Viewer');

    Route::post('/route_import/all_data/images/customer_code'                                           ,   [RouteImportController::class           , 'downloadCustomerCodeImages'              ])->middleware('Viewer');
    Route::post('/route_import/all_data/images/facade'                                                  ,   [RouteImportController::class           , 'downloadFacadeImages'                    ])->middleware('Viewer');
    Route::post('/route_import/all_data/images/in_store'                                                ,   [RouteImportController::class           , 'downloadInStoreImages'                   ])->middleware('Viewer');
});