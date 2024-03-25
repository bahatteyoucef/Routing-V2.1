<?php

namespace App\Models;

use Carbon\Carbon;
use Exception;
use GuzzleHttp\Psr7\Request as Psr7Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\Static_;
use stdClass;

class Statistic extends Model
{
    use HasFactory;

    //

    public static function byCustomerTypeReport(Request $request) {

        $route_links    =   json_decode($request->get("route_links"));

        //
        $startDate      =   Carbon::parse($request->get("start_date")); // Replace with your start date
        $endDate        =   Carbon::parse($request->get("end_date"));   // Replace with your end date

        //
        $customer_types     =   DB::table("clients")
                                    ->select("clients.CustomerType")
                                    ->whereIn('clients.id_route_import', $route_links)
                                    ->whereBetween(DB::raw('STR_TO_DATE(created_at, "%d %M %Y")'), [$startDate, $endDate]) // Use Y-m-d format for comparison
                                    ->distinct("clients.CustomerType")
                                    ->pluck('CustomerType');

        //
        $datasets                   =   [];

        $dataset                    =   new stdClass();
        $dataset->data              =   [];

        foreach ($customer_types as $customer_type) {

            //
            $count                      =   DB::table("clients")
                                                ->where('clients.CustomerType', $customer_type)
                                                ->whereIn('clients.id_route_import', $route_links)
                                                ->whereBetween(DB::raw('STR_TO_DATE(created_at, "%d %M %Y")'), [$startDate, $endDate]) // Use Y-m-d format for comparison
                                                ->count();

            //
            array_push($dataset->data, $count);
        }

        //
        array_push($datasets, $dataset);

        //
        $by_customer_type_reports                           =   new stdClass();

        $by_customer_type_reports->labels                   =   $customer_types;
        $by_customer_type_reports->datasets                 =   $datasets;

        //
        return $by_customer_type_reports;
    }

    //

    public static function byBrandSourcePurchaseReport(Request $request) {

        $route_links        =   json_decode($request->get("route_links"));

        //
        $startDate          =   Carbon::parse($request->get("start_date")); // Replace with your start date
        $endDate            =   Carbon::parse($request->get("end_date"));   // Replace with your end date
        //

        //
        $source_achats      =   DB::table("clients")
                                    ->select("clients.BrandSourcePurchase")
                                    ->whereIn('clients.id_route_import', $route_links)
                                    ->whereBetween(DB::raw('STR_TO_DATE(created_at, "%d %M %Y")'), [$startDate, $endDate]) // Use Y-m-d format for comparison
                                    ->distinct("clients.BrandSourcePurchase")
                                    ->pluck('BrandSourcePurchase');
        //

        //
        $datasets                   =   [];

        $dataset                    =   new stdClass();
        $dataset->data              =   [];

        foreach ($source_achats as $source_achat) {

            //  //  //  //  //  //  //  //  //  //
            $count                      =   DB::table("clients")
                                                ->where('clients.BrandSourcePurchase', $source_achat)
                                                ->whereIn('clients.id_route_import', $route_links)
                                                ->whereBetween(DB::raw('STR_TO_DATE(created_at, "%d %M %Y")'), [$startDate, $endDate]) // Use Y-m-d format for comparison
                                                ->count();

            array_push($dataset->data, $count);
        }

        array_push($datasets, $dataset);
        //

        //
        $by_source_achat_reports                            =   new stdClass();

        $by_source_achat_reports->labels                    =   $source_achats;
        $by_source_achat_reports->datasets                  =   $datasets;
        //

        //
        return $by_source_achat_reports;
    }

    //

    public static function byBrandAvailabilityReport(Request $request) {

        $route_links        =   json_decode($request->get("route_links"));

        //
        $startDate          =   Carbon::parse($request->get("start_date")); // Replace with your start date
        $endDate            =   Carbon::parse($request->get("end_date"));   // Replace with your end date
        //

        //
        $datasets                   =   [];

        //  //  //  //  //  //  //  //  //  //

        $dataset                    =   new stdClass();
        $dataset->data              =   [];

        $count_yes                  =   DB::table("clients")
                                            ->where('clients.BrandAvailability', 1)
                                            ->whereIn('clients.id_route_import', $route_links)
                                            ->whereBetween(DB::raw('STR_TO_DATE(created_at, "%d %M %Y")'), [$startDate, $endDate]) // Use Y-m-d format for comparison
                                            ->count();

        array_push($dataset->data, $count_yes);

        //  //  //  //  //  //  //  //  //  //

        $count_no                   =   DB::table("clients")
                                            ->where('clients.BrandAvailability', 0)
                                            ->whereIn('clients.id_route_import', $route_links)
                                            ->whereBetween(DB::raw('STR_TO_DATE(created_at, "%d %M %Y")'), [$startDate, $endDate]) // Use Y-m-d format for comparison
                                            ->count();

        array_push($dataset->data, $count_no);

        //  //  //  //  //  //  //  //  //  //

        array_push($datasets, $dataset);

        //
        $by_brand_availability_reports                                      =   new stdClass();

        $by_brand_availability_reports->labels                              =   ["Yes", "No"];
        $by_brand_availability_reports->datasets                            =   $datasets;

        //
        return $by_brand_availability_reports;
    }

    //

    public static function dailyReport(Request $request) {

        $route_links    =   json_decode($request->get("route_links"));

        //
        $startDate      =   Carbon::parse($request->get("start_date")); // Replace with your start date
        $endDate        =   Carbon::parse($request->get("end_date"));   // Replace with your end date

        //
        $users          =   DB::table("users")
                                ->select("users.*")
                                ->join("users_route_import", "users.id", "users_route_import.id_user")
                                ->whereIn('users_route_import.id_route_import', $route_links)
                                ->distinct("users.id")
                                ->get();

        //
        $allDays        =   [];

        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {

            $allDays[]  =   $date->format('Y-m-d');
        }

        //
        $datasets                   =   [];

        //
        $total_by_day_object        =   new stdClass();
        $total_by_day_object->data  =   [];

        //
        foreach ($users as $user) {

            //
            $dataset                    =   new stdClass();

            // Set Chart Label
            $dataset->label             =   $user->nom . " (".$user->id.")";
            $dataset->data              =   [];

            // Set data
            foreach ($allDays as $index => $day) {

                //
                $count                      =   DB::table("clients")
                                                    ->where('owner', $user->id)
                                                    ->whereIn('clients.id_route_import', $route_links)
                                                    ->whereRaw('STR_TO_DATE(created_at, "%d %M %Y") = ?', [$day]) // Use Y-m-d format for comparison
                                                    ->count();

                //
                array_push($dataset->data, $count);
            }
            
            // Push Data to datasets Array
            array_push($datasets, $dataset);
        }

        //
        $daily_reports                          =   new stdClass();

        $daily_reports->labels                  =   $allDays;
        $daily_reports->datasets                =   $datasets;

        //
        return $daily_reports;
    }

    public static function dailyReportTable(Request $request) {

        $route_links    =   json_decode($request->get("route_links"));

        //
        $startDate      =   Carbon::parse($request->get("start_date")); // Replace with your start date
        $endDate        =   Carbon::parse($request->get("end_date"));   // Replace with your end date
        //

        //
        $users          =   DB::table("users")
                                ->select("users.*")
                                ->join("users_route_import", "users.id", "users_route_import.id_user")
                                ->whereIn('users_route_import.id_route_import', $route_links)
                                ->distinct("users.id")
                                ->get();
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
                                                    ->whereIn('clients.id_route_import', $route_links)
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

    //

    public static function byTelAvailabilityReport(Request $request) {

        $route_links    =   json_decode($request->get("route_links"));

        //
        $startDate      =   Carbon::parse($request->get("start_date")); // Replace with your start date
        $endDate        =   Carbon::parse($request->get("end_date"));   // Replace with your end date
        //

        //
        $users          =   DB::table("users")
                                ->select("users.*")
                                ->join("users_route_import", "users.id", "users_route_import.id_user")
                                ->whereIn('users_route_import.id_route_import', $route_links)
                                ->distinct("users.id")
                                ->get();
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
                                                ->whereIn('clients.id_route_import', $route_links)
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
                                                ->whereIn('clients.id_route_import', $route_links)
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

        $by_tel_availability_reports                                =   new stdClass();

        $by_tel_availability_reports->labels                        =   $labels;
        $by_tel_availability_reports->datasets                      =   $datasets;

        // $by_tel_availability_reports->by_tel_availability_table     =   Statistic::byTelAvailabilityTable($users, $route_links, $startDate, $endDate);

        //
        return $by_tel_availability_reports;
    }

    //

    public static function byCityReport(Request $request) {

        $route_links    =   json_decode($request->get("route_links"));

        //
        $startDate      =   Carbon::parse($request->get("start_date")); // Replace with your start date
        $endDate        =   Carbon::parse($request->get("end_date"));   // Replace with your end date
        //

        //
        $cities         =   DB::table("RTM_City")
                                ->select("RTM_City.*", "clients.CityNo")
                                ->join("clients", "RTM_City.CITYNO", "clients.CityNo")
                                ->whereIn('clients.id_route_import', $route_links)
                                ->distinct("clients.CityNo")
                                ->get();
        //

        //
        $datasets                   =   [];

        $dataset_expected           =   new stdClass();
        $dataset_expected->label    =   "Expected";
        $dataset_expected->data     =   [];

        $dataset_added              =   new stdClass();
        $dataset_added->label       =   "Added";
        $dataset_added->data        =   [];
        //

        //
        foreach ($cities as $city) {

            $city->added_clients        =   DB::table("clients")
                                                ->select("clients.*")
                                                ->where("clients.CityNo", $city->CityNo)
                                                ->whereIn('clients.id_route_import', $route_links)
                                                ->whereBetween(DB::raw('STR_TO_DATE(created_at, "%d %M %Y")'), [$startDate, $endDate]) // Use Y-m-d format for comparison
                                                ->count();

            //
            array_push($dataset_expected->data  , $city->expected_clients);
            array_push($dataset_added->data     , $city->added_clients);
            //
        }
        //

        //
        array_push($datasets, $dataset_expected);
        array_push($datasets, $dataset_added);
        //

        //
        $by_city_reports                    =   new stdClass();

        $by_city_reports->labels            =   $cities->pluck('CityNameE');
        $by_city_reports->datasets          =   $datasets;
        //

        //
        return $by_city_reports;
    }

    //

    public static function dataCensusReport(Request $request) {

        //
        $route_links    =   json_decode($request->get("route_links"));
        //

        //
        $startDate      =   Carbon::parse($request->get("start_date")); // Replace with your start date
        $endDate        =   Carbon::parse($request->get("end_date"));   // Replace with your end date
        //

        //
        $clients        =   DB::table("clients")
                                ->select("clients.*", 
                                        DB::raw('CASE WHEN clients.BrandAvailability = 1 THEN "Yes" ELSE "No" END AS BrandAvailabilityText'), 
                                        DB::raw('CASE WHEN clients.Tel IS NOT NULL AND clients.Tel != "" THEN "Yes" ELSE "No" END AS TelAvailabilityText'),
                                        "users.nom as OwnerName")
                                ->join("users", "clients.owner", "users.id")
                                ->whereIn('clients.id_route_import', $route_links)
                                ->whereBetween(DB::raw('STR_TO_DATE(clients.created_at, "%d %M %Y")'), [$startDate, $endDate]) // Use Y-m-d format for comparison
                                ->get();
        //

        //
        $data_census_table          =   new stdClass();

        $data_census_table->rows    =   $clients;

        //
        return $data_census_table;
    }

    //

    public static function statsDetails(Request $request) {

        $stats_details  =   new stdClass();

        // by_customer_type_report_chart_data
        $stats_details->by_customer_type_report_chart_data          =   Statistic::byCustomerTypeReport($request);

        // by_brand_source_purchase_report_chart_data
        $stats_details->by_brand_source_purchase_report_chart_data  =   Statistic::byBrandSourcePurchaseReport($request);

        // by_brand_availability_report_chart_data
        $stats_details->by_brand_availability_report_chart_data     =   Statistic::byBrandAvailabilityReport($request);

        // Daily Report
        $stats_details->daily_report_chart_data                     =   Statistic::dailyReport($request);

        // By Tel Report
        $stats_details->by_tel_availability_report_chart_data       =   Statistic::byTelAvailabilityReport($request);

        // By City Report
        $stats_details->by_city_report_chart_data                   =   Statistic::byCityReport($request);

        // Data Census
        $stats_details->data_census_report_table_data               =   Statistic::dataCensusReport($request);

        //
        return $stats_details;
    }
}