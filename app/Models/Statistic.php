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
        $datasets                   =   [];

        //
        $total_by_day_object        =   new stdClass();
        $total_by_day_object->data  =   [];
        $total_by_day_object->total =   0;

        foreach ($users as $user) {

            //
            $dataset                    =   new stdClass();

            // Set Chart Label
            $dataset->label             =   $user->nom . " (".$user->id.")";
            $dataset->data              =   [];
            $dataset->total             =   0;

            // Set data
            foreach ($allDays as $index => $day) {

                //
                $count                      =   DB::table("clients")
                                                    ->where('owner', $user->id)
                                                    ->whereRaw('STR_TO_DATE(created_at, "%d %M %Y") = ?', [$day]) // Use Y-m-d format for comparison
                                                    ->count();

                // 
                $dataset->total             =   $dataset->total             +   $count;
                $total_by_day_object->total =   $total_by_day_object->total +   $count;

                //
                if (isset($total_by_day_object->data[$index])) {

                    $total_by_day_object->data[$index]     =   $total_by_day_object->data[$index] +   $count;
                }

                else {

                    array_push($total_by_day_object->data, $count);
                }

                //
                array_push($dataset->data, $count);
            }
            
            // Push Data to datasets Array
            array_push($datasets, $dataset);
        }
        //

        //
        $daily_reports                          =   new stdClass();

        $daily_reports->labels                  =   $allDays;
        $daily_reports->datasets                =   $datasets;
        $daily_reports->total_by_day_object     =   $total_by_day_object;

        //
        return $daily_reports;
    }

    public static function telAvailabilityReports(Request $request) {

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

        //  //  //  //  //

        // Set Y Data   //

        //
        $datasets   =   [];

        // Set Yes Data

        //
        $dataset_yes                    =   new stdClass();

        // Set Chart Label
        $dataset_yes->label             =   "Yes";
        $dataset_yes->data              =   [];

        foreach ($users as $user) {

            //
            $count                      =   DB::table("clients")
                                                ->where('owner', $user->id)
                                                ->where(function ($query) {
                                                    $query->whereNotNull('Tel')
                                                            ->where('Tel', '!=', '');
                                                })
                                                ->whereBetween(DB::raw('STR_TO_DATE(created_at, "%d %M %Y")'), [$startDate, $endDate]) // Use Y-m-d format for comparison
                                                ->count();

            //
            array_push($dataset_yes->data, $count);
        }

        // Push Data to datasets Array
        array_push($datasets, $dataset_yes);

        //  //  //  //  //

        //
        $dataset_no                     =   new stdClass();

        // Set Chart Label
        $dataset_no->label              =   "No";
        $dataset_no->data               =   [];

        // Set No Data
        foreach ($users as $user) {

            //
            $count                      =   DB::table("clients")
                                                ->where('owner', $user->id)
                                                ->where(function ($query) {
                                                    $query->whereNull('Tel')
                                                            ->orWhere('Tel', '');
                                                })
                                                ->whereBetween(DB::raw('STR_TO_DATE(created_at, "%d %M %Y")'), [$startDate, $endDate]) // Use Y-m-d format for comparison
                                                ->count();

            //
            array_push($dataset_no->data, $count);
        }

        // Push Data to datasets Array
        array_push($datasets, $dataset_no);

        //  //  //  //  //

        // Set X Labels //

        $labels = [];

        foreach ($users as $user) {

            $labels[]   =   $user->nom . " (" . $user->id . ")";
        }

        //  //  //  //  //

        $tel_availability_reports               =   new stdClass();

        $tel_availability_reports->labels       =   $labels;
        $tel_availability_reports->datasets     =   $datasets;

        //
        return $tel_availability_reports;
    }
}