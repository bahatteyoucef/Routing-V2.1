<?php

namespace App\Http\Controllers;

use App\Models\RTMWillaya;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class RTMWillayaController extends Controller
{
    public static function index(Request $request) {

        try {

            //
            $willayas  =   RTMWillaya::index($request);

            //
            return $willayas;
        }

        catch(Throwable $erreur) {

            return response()->json([
                'errors'    =>  [$erreur->getMessage()],
            ],422);
        }
    } 

    public static function willayasCities(Request $request) {

        try {

            //
            $willayas  =   RTMWillaya::willayasCities($request);

            //
            return $willayas;
        }

        catch(Throwable $erreur) {

            return response()->json([
                'errors'    =>  [$erreur->getMessage()],
            ],422);
        }
    }

    public static function cities(int $DistrictNo, Request $request) {

        try {

            //
            $cities  =   RTMWillaya::cities($DistrictNo);

            //
            return $cities;
        }

        catch(Throwable $erreur) {

            return response()->json([
                'errors'    =>  [$erreur->getMessage()],
            ],422);
        }
    }

    //  //  //  //  //
    //  //  //  //  //
    //  //  //  //  //

    public static function updateExpectedClients(int $DistrictNo, Request $request) {

        try {

            //
            DB::beginTransaction();
            //

            // update 
            RTMWillaya::updateExpectedClients($DistrictNo, $request);
            
            //
            DB::commit();
            //

            return response()->json([
                "header"            =>  "Expected Clients Updated !"                                        ,
                "message"           =>  "the expected number of clients have been updated successfully !"
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