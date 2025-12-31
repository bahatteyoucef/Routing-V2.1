<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\ClientTempoController;

use App\Http\Controllers\JourneeTerritoryController;
use App\Http\Controllers\JourneyPlanTerritoryController;
use App\Http\Controllers\UserTerritoryController;

use App\Http\Controllers\RouteImportController;
use App\Http\Controllers\RouteImportTempoController;
use App\Http\Controllers\RTMWillayaController;
use App\Http\Controllers\StatisticController;

use App\Http\Controllers\UserController;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

// Check if Authentificated
Route::post('/users/is-authentificated', [UserController::class, 'isAuthentificated']);

//  //  //  //  //  //  //  //  //  //  // //  //  //  //  //  //  //  //

// Dashboard
Route::middleware(['auth:api', 'isEnabledUser'])->group(function () {

    //  //  //  //  //
    //  //  //  //  //  RTM Willayas
    //  //  //  //  //

    // Listings
    Route::post('/rtm-willayas'                                                                         ,   [RTMWillayaController::class, 'index'                   ])->middleware('Viewer');
    Route::post('/rtm-willayas/rtm-cities/details'                                                      ,   [RTMWillayaController::class, 'willayasCities'          ])->middleware('Viewer');

    // Cities
    Route::post('/rtm-willayas/{DistrictNo}/rtm-cities'                                                 ,   [RTMWillayaController::class, 'cities'                  ])->middleware('Viewer');
  
    // Set Expected
    Route::post('/rtm-willayas/{DistrictNo}/rtm-cities/expected_clients/update'                         ,   [RTMWillayaController::class, 'updateExpectedClients'   ])->middleware('BUManager');

    //  //  //  //  //
    //  //  //  //  //  Users
    //  //  //  //  //

    // Listings
    Route::post('/users'                                                                                ,   [UserController::class                  , 'index'                                   ])->middleware('BUManager');

    // Combo
    Route::post('/users/combo'                                                                          ,   [UserController::class                  , 'combo'                                   ])->middleware('BUManager');
    Route::post('/users/combo/backoffice'                                                               ,   [UserController::class                  , 'comboBackOffice'                         ])->middleware('BUManager');

    // CRUD
    Route::post('/users/store'                                                                          ,   [UserController::class                  , 'store'                                   ])->middleware('BUManager');
    Route::post('/users/{id_user}/update'                                                               ,   [UserController::class                  , 'update'                                  ])->middleware('BUManager');
    Route::post('/users/{id_user}/show'                                                                 ,   [UserController::class                  , 'show'                                    ]);

    // Pointings
    Route::post('/users/pointings'                                                                      ,   [UserController::class                  , 'pointings'                               ])->middleware('BUManager');

    // is Auth
    Route::post('/users'                                                                                ,   function() { return Auth::user();                                                   })->middleware('Viewer');

    //  //  //  //  //
    //  //  //  //  //  Route Imports
    //  //  //  //  //

    // Listings
    Route::post('/route-imports'                                                                        ,   [RouteImportController::class           , 'index'                                   ])->middleware('Viewer');
    Route::post('/route-imports/details'                                                                ,   [RouteImportController::class           , 'details'                                 ])->middleware('Viewer');

    // Combo
    Route::post('/route-imports/combo'                                                                  ,   [RouteImportController::class           , 'combo'                                   ])->middleware('Viewer');

    // CRUD
    Route::post('/route-imports/store'                                                                  ,   [RouteImportController::class           , 'store'                                   ])->middleware('BUManager');
    Route::post('/route-imports/{id_route_import}/update'                                               ,   [RouteImportController::class           , 'update'                                  ])->middleware('BackOffice');
    Route::post('/route-imports/{id_route_import}/delete'                                               ,   [RouteImportController::class           , 'delete'                                  ])->middleware('SuperAdmin');    
    Route::post('/route-imports/{id_route_import}/show'                                                 ,   [RouteImportController::class           , 'show'                                    ])->middleware('BUManager');

    // Clients
    Route::post('/route-imports/{id_route_import}/clients'                                              ,   [RouteImportController::class           , 'clients'                                 ])->middleware('Viewer');
    Route::post('/route-imports/{id_route_import}/clients/by-status'                                    ,   [RouteImportController::class           , 'clientsByStatus'                         ])->middleware('FrontOffice');

    // Users
    Route::post('/route-imports/{id_route_import}/users/frontOffice'                                    ,   [RouteImportController::class           , 'frontOffice'                             ])->middleware('BackOffice');
    Route::post('/route-imports/{id_route_import}/users'                                                ,   [RouteImportController::class           , 'users'                                   ])->middleware('BackOffice');

    // Districts
    Route::post('/route-imports/{id_route_import}/districts'                                            ,   [RouteImportController::class           , 'routeImportDistricts'                    ])->middleware('Viewer');

    // Cities
    Route::post('/route-imports/{id_route_import}/cities'                                               ,   [RouteImportController::class           , 'cities'                                  ])->middleware('Viewer');

    // Export Data and Images
    Route::post('/route-imports/all_data'                                                               ,   [RouteImportController::class           , 'downloadData'                            ])->middleware('Viewer');
    Route::post('/route-imports/all_data/images'                                                        ,   [RouteImportController::class           , 'downloadImages'                          ])->middleware('Viewer');
    Route::post('/route-imports/all_data/images/customer-code'                                          ,   [RouteImportController::class           , 'downloadCustomerCodeImages'              ])->middleware('Viewer');
    Route::post('/route-imports/all_data/images/facade'                                                 ,   [RouteImportController::class           , 'downloadFacadeImages'                    ])->middleware('Viewer');
    Route::post('/route-imports/all_data/images/in-store'                                               ,   [RouteImportController::class           , 'downloadInStoreImages'                   ])->middleware('Viewer');

    // Journey Plan Territories
    Route::post('/route-imports/{id_route_import}/journey-plan-territories'                             ,   [RouteImportController::class           , 'journeyPlan'                             ])->middleware('Viewer');
    Route::post('/route-imports/{id_route_import}/journey-plan-territories/util'                        ,   [RouteImportController::class           , 'journeyPlanUtil'                         ])->middleware('Viewer');

    // Journee Territories
    Route::post('/route-imports/{id_route_import}/journee-territories'                                  ,   [RouteImportController::class           , 'journeeTerritories'                      ])->middleware('Viewer');
    Route::post('/route-imports/{id_route_import}/journee-territories/util'                             ,   [RouteImportController::class           , 'journeeTerritoriesUtil'                  ])->middleware('Viewer');

    // User Territories
    Route::post('/route-imports/{id_route_import}/user-territories'                                     ,   [RouteImportController::class           , 'userTerritory'                           ])->middleware('Viewer');
    Route::post('/route-imports/{id_route_import}/user-territories/util'                                ,   [RouteImportController::class           , 'userTerritoryUtil'                       ])->middleware('Viewer');

    // OBS
    Route::post('/route/obs/route-imports/{id_route_import}/details'                                    ,   [RouteImportController::class           , 'obsDetailsRouteImport'                   ])->middleware('BackOffice');
    Route::post('/route/obs/route-imports/{id_route_import}/details/for-front-office'                   ,   [RouteImportController::class           , 'obsDetailsRouteImportFrontOffice'        ])->middleware('FrontOffice');

    //  //  //  //  //
    //  //  //  //  //  Route Imports Tempo
    //  //  //  //  //

    // Show/Store
    Route::post('/route-imports-tempo/last'                                                             ,   [RouteImportTempoController::class      , 'lastTempo'                               ])->middleware('BUManager');
    Route::post('/route-imports-tempo/store'                                                            ,   [RouteImportTempoController::class      , 'store'                                   ])->middleware('BUManager');

    // Route::post('/route-imports-tempo/file'                                                              ,   [RouteImportTempoController::class      , 'getFile'                                 ])->middleware('BUManager');

    //  //  //  //  //
    //  //  //  //  //  Clients
    //  //  //  //  //

    // Resume
    Route::post('/clients/resume/update'                                                                ,   [ClientController::class                , 'updateResumeClients'                     ])->middleware('BackOffice');

    // Multi Update/Multi Delete
    Route::post('/route-imports/{id_route_import}/clients/update'                                       ,   [ClientController::class                , 'updateClients'                           ])->middleware('BackOffice');
    Route::post('/route-imports/{id_route_import}/clients/delete'                                       ,   [ClientController::class                , 'deleteClients'                           ])->middleware('SuperAdmin');

    // CRUD
    Route::post('/route-imports/{id_route_import}/clients/store'                                        ,   [ClientController::class                , 'storeClient'                             ]);
    Route::post('/route-imports/{id_route_import}/clients/{id_client}/update'                           ,   [ClientController::class                , 'updateClient'                            ]);
    Route::post('/route-imports/{id_route_import}/clients/{id_client}/delete'                           ,   [ClientController::class                , 'deleteClient'                            ])->middleware('SuperAdmin');
    Route::post('/route-imports/{id_route_import}/clients/{id_client}/show'                             ,   [ClientController::class                , 'showClient'                              ]);

    // Doubles
    Route::post('/route-imports/{id_route_import}/clients/doubles'                                      ,   [ClientController::class                , 'getDoublesClients'                       ])->middleware('BackOffice');
    Route::post('/route-imports/{id_route_import}/clients/doubles/Tel'                                  ,   [ClientController::class                , 'getDoublesTelClients'                    ])->middleware('BackOffice');
    Route::post('/route-imports/{id_route_import}/clients/doubles/CustomerCode'                         ,   [ClientController::class                , 'getDoublesCustomerCodeClients'           ])->middleware('BackOffice');
    Route::post('/route-imports/{id_route_import}/clients/doubles/CustomerNameE'                        ,   [ClientController::class                , 'getDoublesCustomerNameEClients'          ])->middleware('BackOffice');
    Route::post('/route-imports/{id_route_import}/clients/doubles/GPS'                                  ,   [ClientController::class                , 'getDoublesGPSClients'                    ])->middleware('BackOffice');

    //  //  //  //  //
    //  //  //  //  //  Client Tempo
    //  //  //  //  //

    // Resume
    Route::post('/clients-tempo/resume/update'                                                          ,   [ClientTempoController::class           , 'updateResumeClients'                     ])->middleware('BUManager');

    // CRUD
    Route::post('/route-imports-tempo/{id_route_import_tempo}/clients-tempo'                            ,   [ClientTempoController::class           , 'clients'                                 ])->middleware('BUManager');
    Route::post('/route-imports-tempo/{id_route_import_tempo}/clients-tempo/store'                      ,   [ClientTempoController::class           , 'storeClients'                            ])->middleware('BUManager');
    Route::post('/route-imports-tempo/{id_route_import_tempo}/clients-tempo/{id_client_tempo}/update'   ,   [ClientTempoController::class           , 'updateClient'                            ])->middleware('BUManager');
    Route::post('/route-imports-tempo/{id_route_import_tempo}/clients-tempo/{id_client_tempo}/delete'   ,   [ClientTempoController::class           , 'deleteClient'                            ])->middleware('BUManager');

    // Doubles
    Route::post('/route-imports-tempo/{id_route_import_tempo}/clients-tempo/doubles'                    ,   [ClientTempoController::class           , 'getDoublesClients'                       ])->middleware('BUManager');
    Route::post('/route-imports-tempo/{id_route_import_tempo}/clients-tempo/doubles/Tel'                ,   [ClientTempoController::class           , 'getDoublesTelClients'                    ])->middleware('BUManager');
    Route::post('/route-imports-tempo/{id_route_import_tempo}/clients-tempo/doubles/CustomerCode'       ,   [ClientTempoController::class           , 'getDoublesCustomerCodeClients'           ])->middleware('BUManager');
    Route::post('/route-imports-tempo/{id_route_import_tempo}/clients-tempo/doubles/CustomerNameE'      ,   [ClientTempoController::class           , 'getDoublesCustomerNameEClients'          ])->middleware('BUManager');
    Route::post('/route-imports-tempo/{id_route_import_tempo}/clients-tempo/doubles/GPS'                ,   [ClientTempoController::class           , 'getDoublesGPSClients'                    ])->middleware('BUManager');

    //  //  //  //  //
    //  //  //  //  //  JourneyPlan Territories
    //  //  //  //  //

    // CRUD
    Route::post('/route-imports/{id_route_import}/journey-plan-territories/store'                               ,   [JourneyPlanTerritoryController::class  , 'storeJourneyPlan'                        ])->middleware('Viewer');
    Route::post('/route-imports/{id_route_import}/journey-plan-territories/{id_journey_plan_territory}/update'  ,   [JourneyPlanTerritoryController::class  , 'updateJourneyPlan'                       ])->middleware('Viewer');
    Route::post('/route-imports/{id_route_import}/journey-plan-territories/{id_journey_plan_territory}/delete'  ,   [JourneyPlanTerritoryController::class  , 'deleteJourneyPlan'                       ])->middleware('Viewer');

    //  //  //  //  //
    //  //  //  //  //  Journee Territories
    //  //  //  //  //

    // CRUD
    Route::post('/route-imports/{id_route_import}/journee-territories/store'                                    ,   [JourneeTerritoryController::class      , 'storeJourneeTerritory'                   ])->middleware('Viewer');
    Route::post('/route-imports/{id_route_import}/journee-territories/{id_journee_territory}/update'            ,   [JourneeTerritoryController::class      , 'updateJourneeTerritory'                  ])->middleware('Viewer');
    Route::post('/route-imports/{id_route_import}/journee-territories/{id_journee_territory}/delete'            ,   [JourneeTerritoryController::class      , 'deleteJourneeTerritory'                  ])->middleware('Viewer');

    //  //  //  //  //
    //  //  //  //  //  User Territories
    //  //  //  //  //

    // CRUD
    Route::post('/route-imports/{id_route_import}/user-territories/store'                                       ,   [UserTerritoryController::class         , 'storeUserTerritory'                      ])->middleware('Viewer');
    Route::post('/route-imports/{id_route_import}/user-territories/{id_user_territory}/update'                  ,   [UserTerritoryController::class         , 'updateUserTerritory'                     ])->middleware('Viewer');
    Route::post('/route-imports/{id_route_import}/user-territories/{id_user_territory}/delete'                  ,   [UserTerritoryController::class         , 'deleteUserTerritory'                     ])->middleware('Viewer');

    //  //  //  //  //
    //  //  //  //  //  Statistics
    //  //  //  //  //

    Route::post('/statistics/standard'                                                                  ,   [StatisticController::class             , 'standardStatistics'                      ])->middleware('Viewer');
    Route::post('/statistics/self-service'                                                              ,   [StatisticController::class             , 'selfServiceStatistics'                   ])->middleware('Viewer');
});