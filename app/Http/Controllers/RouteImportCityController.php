<?php

namespace App\Http\Controllers;

use App\Models\RouteImportCity;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class RouteImportCityController extends Controller
{

    //
    public static function setRouteImportCities(int $id_route_import, string $DistrictNo, Request $request)
    {

        try {

            //
            DB::beginTransaction();
            //

            // store 
            RouteImportCity::setRouteImportCities($id_route_import, $DistrictNo, $request);

            //
            DB::commit();
            //

            return response()->json([
                "header"            =>  "Route Import Cities Added !"                           ,
                "message"           =>  "new route import cities has been added successfuly!"
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
}
