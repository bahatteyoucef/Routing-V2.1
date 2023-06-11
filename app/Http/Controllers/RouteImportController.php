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
                "header"            =>  "Route Ajoutée !"                   ,
                "message"           =>  "une nouvelle route a été ajouté !" ,
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

    public function update(Request $request, int $id_route_import)
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
            RouteImport::updateRouteImport($request, $id_route_import);

            //
            DB::commit();
            //

            return response()->json([
                "header"            =>  "Route Modifiée !"                   ,
                "message"           =>  "une nouvelle route a été modifié !"
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

    public function obsDetailsRouteImport(string $id_route_import)
    {

        try {

            $route_import       =   RouteImport::obsDetailsRouteImport($id_route_import);
            return $route_import;
        }

        catch(Throwable $erreur) {

            return response()->json([
                'errors'    =>  [$erreur->getMessage()],
            ],422);
        }
    }
}
