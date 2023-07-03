<?php

namespace App\Http\Controllers;

use App\Models\RouteImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class RouteImportController extends Controller
{
    
    public function index()
    {

        try {

            $liste_route_import     =   RouteImport::indexRouteImport();
            return $liste_route_import;
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
            RouteImport::updateRouteImport($request, $id);

            //
            DB::commit();
            //

            return response()->json([
                "header"            =>  "Route Updated !"                          ,
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
            return $route_import;
        }

        catch(Throwable $erreur) {

            return response()->json([
                'errors'    =>  [$erreur->getMessage()],
            ],422);
        }
    }
}
