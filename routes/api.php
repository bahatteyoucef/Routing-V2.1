<?php

use App\Http\Controllers\CategorieController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ClientTempoController;
use App\Http\Controllers\CompetitorAnalysisController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\JourneeController;
use App\Http\Controllers\JourneyPlanController;
use App\Http\Controllers\MarqueController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\RouteImportController;
use App\Http\Controllers\RouteImportTempoController;
use App\Http\Controllers\StockCheckController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\UserController;
use App\Models\ClientTempo;
use App\Models\JourneyPlan;
use App\Models\RouteImport;
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
    
    // RTM WIllaya Cite

    Route::post('/rtm_willayas/rtm_cites/details'           ,   function()  { 

        $willayas   =   DB::table("RTM_Willaya")->orderByRaw('CAST(DistrictNo AS SIGNED INTEGER)')->get();

        foreach ($willayas as $willaya) {

            $willaya->cites =   DB::table("RTM_City")->where('DistrictNo', $willaya->DistrictNo)->orderByRaw('CAST(CityNo AS SIGNED INTEGER)')->get();
        }

        return $willayas;
    });

    Route::post('/rtm_willayas'                             ,   function()  { 

        return DB::table("RTM_Willaya")->orderByRaw('CAST(DistrictNo AS SIGNED INTEGER)')->get();
    });

    Route::post('/rtm_willayas/{DistrictNo}/rtm_cites'      ,   function($DistrictNo)  { 

        return DB::table("RTM_City")->where('DistrictNo', $DistrictNo)->orderByRaw('CAST(CityNo AS SIGNED INTEGER)')->get();
    });

    Route::post('/rtm_cites'                                ,   function()  { 

        return DB::table("RTM_City")->orderByRaw('CAST(CityNo AS SIGNED INTEGER)')->get();
    });

    //

    Route::post('/indexedDB/sync'                                                                       ,   [RouteImportController::class           , 'sync'                                    ]);

    // Users

    Route::post('/users'                                                                                ,   [UserController::class                  , 'index'                                   ]);
    Route::post('/users/combo'                                                                          ,   [UserController::class                  , 'combo'                                   ]);
    Route::post('/users/{id}/show'                                                                      ,   [UserController::class                  , 'show'                                    ]);

    Route::post('/users/store'                                                                          ,   [UserController::class                  , 'store'                                   ]);
    Route::post('/users/{id}/update'                                                                    ,   [UserController::class                  , 'update'                                  ]);

    Route::post('/users/{id}/update/password'                                                           ,   [UserController::class                  , 'changePassword'                          ]);

    //

    // Produits

    Route::post('/produits'                                                                             ,   [ProduitController::class               , 'index'                                   ]);
    Route::post('/produits/combo'                                                                       ,   [ProduitController::class               , 'combo'                                   ]);
    Route::post('/produits/{id}/show'                                                                   ,   [ProduitController::class               , 'show'                                    ]);

    Route::post('/produits/store'                                                                       ,   [ProduitController::class               , 'store'                                   ]);
    Route::post('/produits/{id}/update'                                                                 ,   [ProduitController::class               , 'update'                                  ]);

    Route::post('/route_import/{id_route_import}/products/stock'                                        ,   [ProduitController::class               , 'stockProducts'                           ]);
    Route::post('/route_import/{id_route_import}/products/competitors'                                  ,   [ProduitController::class               , 'competitorsProducts'                     ]);

    Route::post('/route_import/{id_route_import}/products/{id_produit}/prices'                          ,   [ProduitController::class               , 'produitPrices'                           ]);

    //

    // Categorie

    Route::post('/categories'                                                                           ,   [CategorieController::class             , 'index'                                   ]);
    Route::post('/categories/combo'                                                                     ,   [CategorieController::class             , 'combo'                                   ]);
    Route::post('/categories/{id}/show'                                                                 ,   [CategorieController::class             , 'show'                                    ]);

    Route::post('/categories/store'                                                                     ,   [CategorieController::class             , 'store'                                   ]);
    Route::post('/categories/{id}/update'                                                               ,   [CategorieController::class             , 'update'                                  ]);

    //

    // Type

    Route::post('/types'                                                                                ,   [TypeController::class                  , 'index'                                   ]);
    Route::post('/types/combo'                                                                          ,   [TypeController::class                  , 'combo'                                   ]);
    Route::post('/types/{id}/show'                                                                      ,   [TypeController::class                  , 'show'                                    ]);

    Route::post('/types/store'                                                                          ,   [TypeController::class                  , 'store'                                   ]);
    Route::post('/types/{id}/update'                                                                    ,   [TypeController::class                  , 'update'                                  ]);

    //

    // Marque

    Route::post('/marques'                                                                              ,   [MarqueController::class                , 'index'                                   ]);
    Route::post('/marques/combo'                                                                        ,   [MarqueController::class                , 'combo'                                   ]);
    Route::post('/marques/{id}/show'                                                                    ,   [MarqueController::class                , 'show'                                    ]);

    Route::post('/marques/store'                                                                        ,   [MarqueController::class                , 'store'                                   ]);
    Route::post('/marques/{id}/update'                                                                  ,   [MarqueController::class                , 'update'                                  ]);

    //

    Route::post('/route_import/{id}/categories'                                                         ,   [RouteImportController::class           , 'routeImportCategories'                   ]);
    Route::post('/categories/{id}/types'                                                                ,   [CategorieController::class             , 'categorieTypes'                          ]);


    //

    Route::post('/complaints'                                                                           ,   [ComplaintController::class             , 'index'                                   ]);
    Route::post('/complaints/combo'                                                                     ,   [ComplaintController::class             , 'combo'                                   ]);
    Route::post('/complaints/{id}/show'                                                                 ,   [ComplaintController::class             , 'show'                                    ]);

    Route::post('/complaints/store'                                                                     ,   [ComplaintController::class             , 'store'                                   ]);
    Route::post('/complaints/{id}/update'                                                               ,   [ComplaintController::class             , 'update'                                  ]);

    //

    // Route Import Tempo

    Route::post('/route_import_tempo/last'                                                              ,   [RouteImportTempoController::class      , 'lastTempo'                               ]);
    Route::post('/route_import_tempo/store'                                                             ,   [RouteImportTempoController::class      , 'store'                                   ]);
    Route::post('/route_import_tempo/file'                                                              ,   [RouteImportTempoController::class      , 'getFile'                                 ]);

    //

    Route::post('/route_import_tempo/{id_route_import_tempo}/clients_tempo'                             ,   [ClientTempoController::class           , 'clients'                                 ]);
    Route::post('/route_import_tempo/{id_route_import_tempo}/clients_tempo/store'                       ,   [ClientTempoController::class           , 'storeClients'                            ]);
    Route::post('/route_import_tempo/{id_route_import_tempo}/clients_tempo/{id}/update'                 ,   [ClientTempoController::class           , 'updateClient'                            ]);
    Route::post('/route_import_tempo/{id_route_import_tempo}/clients_tempo/{id}/delete'                 ,   [ClientTempoController::class           , 'deleteClient'                            ]);

    Route::post('/route_import_tempo/{id_route_import_tempo}/clients_tempo/update'                      ,   [ClientTempoController::class           , 'updateClients'                           ]);

    Route::post('/route_import_tempo/{id_route_import_tempo}/clients_tempo/doubles'                     ,   [ClientTempoController::class           , 'getDoublesClients'                       ]);
    Route::post('/route_import_tempo/{id_route_import_tempo}/clients_tempo/doubles/Tel'                 ,   [ClientTempoController::class           , 'getDoublesTelClients'                    ]);
    Route::post('/route_import_tempo/{id_route_import_tempo}/clients_tempo/doubles/CustomerCode'        ,   [ClientTempoController::class           , 'getDoublesCustomerCodeClients'           ]);
    Route::post('/route_import_tempo/{id_route_import_tempo}/clients_tempo/doubles/CustomerNameE'       ,   [ClientTempoController::class           , 'getDoublesCustomerNameEClients'          ]);
    Route::post('/route_import_tempo/{id_route_import_tempo}/clients_tempo/doubles/GPS'                 ,   [ClientTempoController::class           , 'getDoublesGPSClients'                    ]);

    //

    // Competitor Analysis

    Route::post('/competitors_analysis/all/store'                                                       ,   [CompetitorAnalysisController::class    , 'storeListeCompetitorAnalysis'            ]);
    Route::post('/stock_check/all/store'                                                                ,   [StockCheckController::class            , 'storeListeStockCheck'                    ]);
    Route::post('/orders/all/store'                                                                     ,   [OrderController::class                 , 'storeListeOrders'                        ]);

    //

    // Route Import

    Route::post('/route_import/set_willayas_cites'                                                      ,   [RouteImportController::class           , 'setWillayasCites'                        ]);    

    Route::post('/route_import'                                                                         ,   [RouteImportController::class           , 'index'                                   ]);
    Route::post('/route_import/store'                                                                   ,   [RouteImportController::class           , 'store'                                   ]);
    Route::post('/route_import/{id}/update'                                                             ,   [RouteImportController::class           , 'update'                                  ]);
    Route::post('/route_import/{id}/delete'                                                             ,   [RouteImportController::class           , 'delete'                                  ]);
    Route::post('/route_import/{id}/show'                                                               ,   [RouteImportController::class           , 'show'                                    ]);

    Route::post('/route_import/{id}/clients'                                                            ,   [RouteImportController::class           , 'clients'                                 ]);

    //

    Route::post('/route_import/{id_route_import}/clients/doubles'                                       ,   [ClientController::class                , 'getDoublesClients'                       ]);
    Route::post('/route_import/{id_route_import}/clients/doubles/Tel'                                   ,   [ClientController::class                , 'getDoublesTelClients'                    ]);
    Route::post('/route_import/{id_route_import}/clients/doubles/CustomerCode'                          ,   [ClientController::class                , 'getDoublesCustomerCodeClients'           ]);
    Route::post('/route_import/{id_route_import}/clients/doubles/CustomerNameE'                         ,   [ClientController::class                , 'getDoublesCustomerNameEClients'          ]);
    Route::post('/route_import/{id_route_import}/clients/doubles/GPS'                                   ,   [ClientController::class                , 'getDoublesGPSClients'                    ]);

    //

    Route::post('/route_import/{id}/journey_plan'                                                       ,   [RouteImportController::class           , 'journeyPlan'                             ]);
    Route::post('/route_import/{id}/journey_plan/util'                                                  ,   [RouteImportController::class           , 'journeyPlanUtil'                         ]);

    Route::post('/route_import/{id}/journees'                                                           ,   [RouteImportController::class           , 'journees'                                ]);
    Route::post('/route_import/{id}/journees/util'                                                      ,   [RouteImportController::class           , 'journeesUtil'                            ]);

    Route::post('/route_import/{id}/journey_plan/store'                                                 ,   [JourneyPlanController::class           , 'storeJourneyPlan'                        ]);
    Route::post('/route_import/{id}/journey_plan/{id_journey_plan}/update'                              ,   [JourneyPlanController::class           , 'updateJourneyPlan'                       ]);
    Route::post('/route_import/{id}/journey_plan/{id_journey_plan}/delete'                              ,   [JourneyPlanController::class           , 'deleteJourneyPlan'                       ]);

    Route::post('/route_import/{id}/journees/store'                                                     ,   [JourneeController::class               , 'storeJournee'                            ]);
    Route::post('/route_import/{id}/journees/{id_journee}/update'                                       ,   [JourneeController::class               , 'updateJournee'                           ]);
    Route::post('/route_import/{id}/journees/{id_journee}/delete'                                       ,   [JourneeController::class               , 'deleteJournee'                           ]);

    Route::post('/route_import/{id_route_import}/clients/{id}/validate'                                 ,   [ClientController::class                , 'validateClient'                          ]);
    Route::post('/route_import/{id_route_import}/clients/{id}/show'                                     ,   [ClientController::class                , 'showClient'                              ]);

    Route::post('/route_import/{id_route_import}/clients/store'                                         ,   [ClientController::class                , 'storeClient'                             ]);
    Route::post('/route_import/{id_route_import}/clients/{id}/update'                                   ,   [ClientController::class                , 'updateClient'                            ]);
    Route::post('/route_import/{id_route_import}/clients/{id}/delete'                                   ,   [ClientController::class                , 'deleteClient'                            ]);

    Route::post('/route_import/{id_route_import}/clients/change_route'                                  ,   [ClientController::class                , 'changeRouteClients'                      ]);
    Route::post('/route_import/{id_route_import}/clients/update'                                        ,   [ClientController::class                , 'updateClients'                           ]);

    Route::post('/route/obs/route_import/{id}/details'                                                  ,   [RouteImportController::class           , 'obsDetailsRouteImport'                   ]);

    //

});
