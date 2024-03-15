<?php

namespace App\Http\Controllers;

use App\Models\Statistic;
use Illuminate\Http\Request;
use Throwable;

class StatisticController extends Controller
{
    //

    public static function dailyReports(Request $request) {

        try {

            //
            $daily_reports  =   Statistic::dailyReports($request);

            //
            return response()->json([

                "daily_reports"    =>  $daily_reports
            ]);
        }

        catch(Throwable $erreur) {

            return response()->json([
                'errors'    =>  [$erreur->getMessage()],
            ],422);
        }
    }
}
