<?php

namespace App\Http\Controllers;

use App\Models\Statistic;
use Illuminate\Http\Request;
use Throwable;

class StatisticController extends Controller
{
    //

    public static function statsDetails(Request $request) {

        try {

            //
            $stats_details  =   Statistic::statsDetails($request);

            //
            return response()->json([

                "stats_details" =>  $stats_details
            ]);
        }

        catch(Throwable $erreur) {

            return response()->json([
                'errors'    =>  [$erreur->getMessage()],
            ],422);
        }
    } 
}
