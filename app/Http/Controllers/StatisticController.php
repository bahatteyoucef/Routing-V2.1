<?php

namespace App\Http\Controllers;

use App\Models\Statistic;
use Illuminate\Http\Request;
use Throwable;

use App\Services\StatisticsService;
use Illuminate\Support\Facades\Log;

class StatisticController extends Controller {

    public function __construct(private StatisticsService $statisticsService) {}

    //  //  //  //  //
    //  //  //  //  //
    //  //  //  //  //

    public static function standardStatistics(Request $request) {

        try {

            //
            $stats_details  =   Statistic::standardStatistics($request);

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

    public function selfServiceStatistics(Request $request) {

        try {
            $result = $this->statisticsService->selfServiceStatistics($request);
            return response()->json(['stats_details' => $result]);
        } catch (Throwable $e) {
            Log::error('stats controller failure', ['exception' => $e]);
            return response()->json(['errors' => [$e->getMessage()]], 422);
        }
    }
}