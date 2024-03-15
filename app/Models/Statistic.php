<?php

namespace App\Models;

use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use stdClass;

class Statistic extends Model
{
    use HasFactory;

    //

    public static function dailyReports(Request $request) {

        $route_links    =   json_decode($request->get("route_links"));

        //
        $users          =   DB::table("users")
                                ->select("users.*")
                                ->join("users_route_import", "users.id", "users_route_import.id_user")
                                ->whereIn('users_route_import.id_route_import', $route_links)
                                ->distinct("users.id")
                                ->get();
        //

        //
        $startDate      =   Carbon::parse($request->get("start_date")); // Replace with your start date
        $endDate        =   Carbon::parse($request->get("end_date"));   // Replace with your end date
        //

        //
        $allDays        =   [];

        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {

            $allDays[]  =   $date->format('Y-m-d');
        }
        //

        //
        $datasets       =   [];

        foreach ($users as $user) {

            //
            $dataset            =   new stdClass();

            // Set Chart Label
            $dataset->label     =   $user->nom . " (".$user->id.")";
            $dataset->data      =   [];

            // Set data
            foreach ($allDays as $day) {

                $count          =   DB::table("clients")
                                        ->where('owner', $user->id)
                                        ->whereRaw('STR_TO_DATE(created_at, "%d %M %Y") = ?', [$day]) // Use Y-m-d format for comparison
                                        ->count();

                array_push($dataset->data, $count);
            }
            
            // Push Data to datasets Array
            array_push($datasets, $dataset);
        }
        //

        //
        $daily_reports              =   new stdClass();

        $daily_reports->labels      =   $allDays;
        $daily_reports->datasets    =   $datasets;
        //

        //
        return $daily_reports;
    }
}