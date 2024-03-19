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

    public static function byTelAvailabilityReports(Request $request) {

        try {

            //
            $by_tel_availability_reports    =   Statistic::byTelAvailabilityReports($request);

            //
            return response()->json([

                "by_tel_availability_reports"   =>  $by_tel_availability_reports
            ]);
        }

        catch(Throwable $erreur) {

            return response()->json([
                'errors'    =>  [$erreur->getMessage()],
            ],422);
        }
    }

    public static function byCustomerTypeReports(Request $request) {

        try {

            //
            $by_customer_type_reports   =   Statistic::byCustomerTypeReports($request);

            //
            return response()->json([

                "by_customer_type_reports"  =>  $by_customer_type_reports
            ]);
        }

        catch(Throwable $erreur) {

            return response()->json([
                'errors'    =>  [$erreur->getMessage()],
            ],422);
        }
    }

    public static function bySourceAchatReports(Request $request) {

        try {

            //
            $by_source_achat_reports    =   Statistic::bySourceAchatReports($request);

            //
            return response()->json([

                "by_source_achat_reports"  =>  $by_source_achat_reports
            ]);
        }

        catch(Throwable $erreur) {

            return response()->json([
                'errors'    =>  [$erreur->getMessage()],
            ],422);
        }
    }

    public static function byBrandAvailabilityReports(Request $request) {

        try {

            //
            $by_brand_availability_reports  =   Statistic::byBrandAvailabilityReports($request);

            //
            return response()->json([

                "by_brand_availability_reports"  =>  $by_brand_availability_reports
            ]);
        }

        catch(Throwable $erreur) {

            return response()->json([
                'errors'    =>  [$erreur->getMessage()],
            ],422);
        }
    }

    public static function byCityReports(Request $request) {

        try {

            //
            $by_city_table    =   Statistic::byCityReports($request);

            //
            return response()->json([

                "by_city_table"   =>  $by_city_table
            ]);
        }

        catch(Throwable $erreur) {

            return response()->json([
                'errors'    =>  [$erreur->getMessage()],
            ],422);
        }
    }

    public static function dataCensusReports(Request $request) {

        try {

            //
            $data_census_table  =   Statistic::dataCensusReports($request);

            //
            return response()->json([

                "data_census_table"     =>  $data_census_table
            ]);
        }

        catch(Throwable $erreur) {

            return response()->json([
                'errors'    =>  [$erreur->getMessage()],
            ],422);
        }
    }
}
