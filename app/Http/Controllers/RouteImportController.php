<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\RouteImport;
use App\Models\RTMWillaya;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class RouteImportController extends Controller
{
    
    public function index()
    {

        try {

            $liste_route_import     =   RouteImport::indexRouteImport();
            return User::filterRouteImport($liste_route_import);
        }

        catch(Throwable $erreur) {

            return response()->json([
                'errors'    =>  [$erreur->getMessage()],
            ],422);
        }
    }

    public function combo()
    {

        try {

            $liste_route_import     =   RouteImport::comboRouteImport();
            return User::filterRouteImport($liste_route_import);
        }

        catch(Throwable $erreur) {

            return response()->json([
                'errors'    =>  [$erreur->getMessage()],
            ],422);
        }
    }

    public function store(Request $request)
    {

        try {

            //
            DB::beginTransaction();
            //

            // validate
            $validator      =   RouteImport::validateStore($request);
            
            if ($validator->fails()) {
                return response()->json([
                    'errors'    =>  $validator->errors(),
                ],422);
            }

            // store 
            $route_import   =   RouteImport::storeRouteImport($request);

            //
            DB::commit();
            //

            return response()->json([
                "header"            =>  "Route Added !"                             ,
                "message"           =>  "a new route has been added successfuly!"   ,
                "route_import"      =>  $route_import
            ]);
        }

        catch(Throwable $erreur) {

            //
            DB::rollBack();
            //

            return response()->json([
                'errors'    =>  [$erreur->getMessage()],
            ],422);
        }
    }

    public function update(Request $request, int $id)
    {

        try {

            //
            DB::beginTransaction();
            //

            // validate
            $validator      =   RouteImport::validateUpdate($request);
            
            if ($validator->fails()) {
                return response()->json([
                    'errors'    =>  $validator->errors(),
                ],422);
            }

            // store 
            $clients    =   RouteImport::updateRouteImport($request, $id);

            //
            DB::commit();
            //

            return response()->json([
                "header"            =>  "Route Updated !"                           ,
                "message"           =>  "a route has been updated successfuly!"     
            ]);
        }

        catch(Throwable $erreur) {

            //
            DB::rollBack();
            //

            return response()->json([
                'errors'    =>  [$erreur->getMessage()],
            ],422);
        }
    }

    public function show(int $id)
    {

        try {

            $route_import   =   RouteImport::showRouteImport($id);
            return $route_import;
        }

        catch(Throwable $erreur) {

            return response()->json([
                'errors'    =>  [$erreur->getMessage()],
            ],422);
        }
    }

    public function indexedDBShow(int $id)
    {

        try {

            $route_import   =   RouteImport::indexedDBShowRouteImport($id);
            return $route_import;
        }

        catch(Throwable $erreur) {

            return response()->json([
                'errors'    =>  [$erreur->getMessage()],
            ],422);
        }
    }

    public function delete(int $id)
    {

        try {

            //
            DB::beginTransaction();
            //
            
            // store 
            RouteImport::deleteRouteImport($id);

            //
            DB::commit();
            //

            return response()->json([
                "header"            =>  "Route Deleted !"             ,
                "message"           =>  "a route has been deleted successfuly!" 
            ]);
        }

        catch(Throwable $erreur) {

            //
            DB::rollBack();
            //

            return response()->json([
                'errors'    =>  [$erreur->getMessage()],
            ],422);
        }
    }

    //

    public function userTerritory(int $id)
    {

        try {

            //
            DB::beginTransaction();
            //

            // store 
            $liste_user_territory =   RouteImport::userTerritory($id);

            //
            DB::commit();
            //

            return $liste_user_territory;
        }

        catch(Throwable $erreur) {

            //
            DB::rollBack();
            //

            return response()->json([
                'errors'    =>  [$erreur->getMessage()],
            ],422);
        }
    }

    public function userTerritoryUtil(Request $request, int $id)
    {

        try {

            //
            DB::beginTransaction();
            //

            // store 
            $liste_user_territory   =   RouteImport::userTerritoryUtil($request, $id);

            //
            DB::commit();
            //

            return $liste_user_territory;
        }

        catch(Throwable $erreur) {

            //
            DB::rollBack();
            //

            return response()->json([
                'errors'    =>  [$erreur->getMessage()],
            ],422);
        }
    }

    //

    public function journeyPlan(int $id)
    {

        try {

            //
            DB::beginTransaction();
            //

            // store 
            $liste_journey_plan =   RouteImport::journeyPlan($id);

            //
            DB::commit();
            //

            return $liste_journey_plan;
        }

        catch(Throwable $erreur) {

            //
            DB::rollBack();
            //

            return response()->json([
                'errors'    =>  [$erreur->getMessage()],
            ],422);
        }
    }

    public function journeyPlanUtil(Request $request, int $id)
    {

        try {

            //
            DB::beginTransaction();
            //

            // store 
            $liste_journey_plan =   RouteImport::journeyPlanUtil($request, $id);

            //
            DB::commit();
            //

            return $liste_journey_plan;
        }

        catch(Throwable $erreur) {

            //
            DB::rollBack();
            //

            return response()->json([
                'errors'    =>  [$erreur->getMessage()],
            ],422);
        }
    }

    //

    public function journees(int $id)
    {

        try {

            //
            DB::beginTransaction();
            //

            // store 
            $journees   =   RouteImport::journees($id);

            //
            DB::commit();
            //

            return $journees;
        }

        catch(Throwable $erreur) {

            //
            DB::rollBack();
            //

            return response()->json([
                'errors'    =>  [$erreur->getMessage()],
            ],422);
        }
    }
    public function journeesUtil(Request $request, int $id)
    {

        try {

            //
            DB::beginTransaction();
            //

            // store 
            $journees   =   RouteImport::journeesUtil($request, $id);

            //
            DB::commit();
            //

            return $journees;
        }

        catch(Throwable $erreur) {

            //
            DB::rollBack();
            //

            return response()->json([
                'errors'    =>  [$erreur->getMessage()],
            ],422);
        }
    }

    //

    public function setWillayasCites(Request $request)
    {

        try {

            //
            DB::beginTransaction();
            //

            // store 
            $clients    =   RouteImport::setWillayasCites($request);

            //
            DB::commit();
            //

            return $clients;
        }

        catch(Throwable $erreur) {

            //
            DB::rollBack();
            //

            return response()->json([
                'errors'    =>  [$erreur->getMessage()],
            ],422);
        }
    }

    //

    public function obsDetailsRouteImport(int $id)
    {

        try {

            $route_import       =   RouteImport::obsDetailsRouteImport($id);
            $willayas           =   RTMWillaya::index();

            return response()->json([
                "route_import"  =>  $route_import,
                "willayas"      =>  $willayas
            ]);

            return $route_import;
        }

        catch(Throwable $erreur) {

            return response()->json([
                'errors'    =>  [$erreur->getMessage()],
            ],422);
        }
    }

    //

    public function clients(int $id)
    {

        try {

            $clients        =   RouteImport::clients($id);
            $route_import   =   RouteImport::find($id);

            return response()->json([
                'clients'           =>  $clients,
                'route_import'      =>  $route_import
            ],422);
        }

        catch(Throwable $erreur) {

            return response()->json([
                'errors'    =>  [$erreur->getMessage()],
            ],422);
        }
    }

    //

    public function sync(Request $request)
    {
    
        try {

            //
            DB::beginTransaction();
            //

            // store 
            RouteImport::sync($request);

            //
            DB::commit();
            //

            return response()->json([
                "header"            =>  "Synchronisation Perfomed !"                        ,
                "message"           =>  "a synchronisation has been performed successfuly!"     
            ]);
        }

        catch(Throwable $erreur) {

            //
            DB::rollBack();
            //

            return response()->json([
                'errors'    =>  [$erreur->getMessage()],
            ],422);
        }

    }

    //

    public function headerRouteImports()
    {

        try {

            $liste_route_import     =   RouteImport::headerRouteImports();
            return User::filterRouteImport($liste_route_import);
        }

        catch(Throwable $erreur) {

            return response()->json([
                'errors'    =>  [$erreur->getMessage()],
            ],422);
        }
    }

    public function indexRouteImports()
    {

        try {

            $liste_route_import     =   RouteImport::indexRouteImports();
            return User::filterRouteImport($liste_route_import);
        }

        catch(Throwable $erreur) {

            return response()->json([
                'errors'    =>  [$erreur->getMessage()],
            ],422);
        }
    }

    //

    public static function frontOffice(int $id_route_import)
    {

        try {

            $users      =   RouteImport::frontOffice($id_route_import);
            return $users;
        }

        catch(Throwable $erreur) {

            return response()->json([
                'errors'    =>  [$erreur->getMessage()],
            ],422);
        }
    }
}