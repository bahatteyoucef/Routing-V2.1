<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use stdClass;

class Statistic extends Model
{
    use HasFactory;

    //

    public static function numberClientsValidated($request) {

        //
        $route_links                    =   json_decode($request->get("route_links"));
        
        //
        $startDate                      =   Carbon::parse($request->get("start_date")); // Replace with your start date
        $endDate                        =   Carbon::parse($request->get("end_date"));   // Replace with your end date

        //
        $number_clients_validated       =   DB::table("clients")
                                                ->whereIn('clients.id_route_import', $route_links)
                                                ->whereBetween(DB::raw('STR_TO_DATE(created_at, "%d %M %Y")'), [$startDate, $endDate]) // Use Y-m-d format for comparison
                                                ->where("clients.status", "validated")
                                                ->count();

        return $number_clients_validated;
    }

    public static function numberClientsNonValidated($request) {

        //
        $route_links                    =   json_decode($request->get("route_links"));
        
        //
        $startDate                      =   Carbon::parse($request->get("start_date")); // Replace with your start date
        $endDate                        =   Carbon::parse($request->get("end_date"));   // Replace with your end date

        //
        $number_clients_nonvalidated    =   DB::table("clients")
                                                ->where("clients.status", "nonvalidated")
                                                ->whereIn('clients.id_route_import', $route_links)
                                                ->whereBetween(DB::raw('STR_TO_DATE(created_at, "%d %M %Y")'), [$startDate, $endDate]) // Use Y-m-d format for comparison
                                                ->count();

        return $number_clients_nonvalidated;
    }

    public static function numberClientsTotal($request) {

        //
        $route_links                    =   json_decode($request->get("route_links"));
        
        //
        $startDate                      =   Carbon::parse($request->get("start_date")); // Replace with your start date
        $endDate                        =   Carbon::parse($request->get("end_date"));   // Replace with your end date

        //
        $number_clients_total           =   DB::table("clients")
                                            ->whereIn('clients.id_route_import', $route_links)
                                            ->whereBetween(DB::raw('STR_TO_DATE(created_at, "%d %M %Y")'), [$startDate, $endDate]) // Use Y-m-d format for comparison
                                            ->count();

        return $number_clients_total;
    }

    public static function numberClientsExpected($request) {

        //
        $route_links                =   json_decode($request->get("route_links"));
        
        //
        $startDate                  =   Carbon::parse($request->get("start_date")); // Replace with your start date
        $endDate                    =   Carbon::parse($request->get("end_date"));   // Replace with your end date
        
        //
        $cities                     =   DB::table("RTM_City")
                                            ->select("RTM_City.*")
                                            ->join("clients", "RTM_City.CITYNO", "clients.CityNo")
                                            ->whereIn('clients.id_route_import', $route_links)
                                            ->whereBetween(DB::raw('STR_TO_DATE(clients.created_at, "%d %M %Y")'), [$startDate, $endDate]) // Use Y-m-d format for comparison
                                            ->distinct("clients.CityNo")
                                            ->get();

        //
        $number_clients_expected    =   0;

        foreach ($cities as $city) {

            $number_clients_expected    =   $number_clients_expected    +   $city->expected_clients;
        }

        return $number_clients_expected;
    }

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

    public static function byCustomerTypeReportTable(Request $request) {

        $route_links    =   json_decode($request->get("route_links"));

        //
        $startDate      =   Carbon::parse($request->get("start_date")); // Replace with your start date
        $endDate        =   Carbon::parse($request->get("end_date"));   // Replace with your end date
        //

        //
        $customer_types     =   DB::table("clients")
                                    ->select("clients.CustomerType")
                                    ->whereIn('clients.id_route_import', $route_links)
                                    ->whereBetween(DB::raw('STR_TO_DATE(created_at, "%d %M %Y")'), [$startDate, $endDate]) // Use Y-m-d format for comparison
                                    ->distinct("clients.CustomerType")
                                    ->pluck('CustomerType');

        $total_clients      =   DB::table("clients")
                                    ->select("clients.id")
                                    ->whereIn('clients.id_route_import', $route_links)
                                    ->whereBetween(DB::raw('STR_TO_DATE(created_at, "%d %M %Y")'), [$startDate, $endDate]) // Use Y-m-d format for comparison
                                    ->count();

        //
        $rows                       =   [];

        foreach ($customer_types as $customer_type) {

            //  //  //  //  //  //  //  //  //  //
            $count                          =   DB::table("clients")
                                                    ->where('clients.CustomerType', $customer_type)
                                                    ->whereIn('clients.id_route_import', $route_links)
                                                    ->whereBetween(DB::raw('STR_TO_DATE(created_at, "%d %M %Y")'), [$startDate, $endDate]) // Use Y-m-d format for comparison
                                                    ->count();

            $row                            =   new stdClass();
            $row->label                     =   $customer_type;
            $row->count_clients             =   $count;
            $row->percentage_clients        =   $count/$total_clients;

            array_push($rows, $row);
        }

        
        // Set Total By Yes/No    
        $by_customer_type_report_table_data                                         =   new stdClass();

        $by_customer_type_report_table_data->rows                                   =   $rows;

        $by_customer_type_report_table_data->total_row                              =   new stdClass();
        $by_customer_type_report_table_data->total_row->label                       =   "Total";
        $by_customer_type_report_table_data->total_row->count_clients               =   $total_clients;
        $by_customer_type_report_table_data->total_row->percentage_clients          =   1;

        //
        return $by_customer_type_report_table_data;
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

    public static function byBrandSourcePurchaseReportTable(Request $request) {

        $route_links        =   json_decode($request->get("route_links"));

        //
        $startDate          =   Carbon::parse($request->get("start_date")); // Replace with your start date
        $endDate            =   Carbon::parse($request->get("end_date"));   // Replace with your end date
        //

        //
        $brand_source_purchases     =   DB::table("clients")
                                            ->select("clients.BrandSourcePurchase")
                                            ->whereIn('clients.id_route_import', $route_links)
                                            ->whereBetween(DB::raw('STR_TO_DATE(created_at, "%d %M %Y")'), [$startDate, $endDate]) // Use Y-m-d format for comparison
                                            ->distinct("clients.BrandSourcePurchase")
                                            ->pluck('BrandSourcePurchase');

        $total_clients              =   DB::table("clients")
                                            ->select("clients.id")
                                            ->whereIn('clients.id_route_import', $route_links)
                                            ->whereBetween(DB::raw('STR_TO_DATE(created_at, "%d %M %Y")'), [$startDate, $endDate]) // Use Y-m-d format for comparison
                                            ->count();
        //

        //
        $rows                       =   [];

        foreach ($brand_source_purchases as $brand_source_purchase) {

            $count                      =   DB::table("clients")
                                                ->where('clients.BrandSourcePurchase', $brand_source_purchase)
                                                ->whereIn('clients.id_route_import', $route_links)
                                                ->whereBetween(DB::raw('STR_TO_DATE(created_at, "%d %M %Y")'), [$startDate, $endDate]) // Use Y-m-d format for comparison
                                                ->count();
            //
            $row                            =   new stdClass();
            $row->label                     =   $brand_source_purchase;
            $row->count_clients             =   $count;
            $row->percentage_clients        =   $count/$total_clients;

            array_push($rows, $row);
        }
        
        // Set Total By Yes/No    
        $by_brand_source_purchase_report_table_data                                  =   new stdClass();

        $by_brand_source_purchase_report_table_data->rows                            =   $rows;

        $by_brand_source_purchase_report_table_data->total_row                       =   new stdClass();
        $by_brand_source_purchase_report_table_data->total_row->label                =   "Total";
        $by_brand_source_purchase_report_table_data->total_row->count_clients        =   $total_clients;
        $by_brand_source_purchase_report_table_data->total_row->percentage_clients   =   1;

        //
        return $by_brand_source_purchase_report_table_data;
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

    public static function byBrandAvailabilityReportTable(Request $request) {

        $route_links        =   json_decode($request->get("route_links"));

        //
        $startDate          =   Carbon::parse($request->get("start_date")); // Replace with your start date
        $endDate            =   Carbon::parse($request->get("end_date"));   // Replace with your end date
        //

        //
        $total_clients          =   DB::table("clients")
                                        ->select("clients.id")
                                        ->whereIn('clients.id_route_import', $route_links)
                                        ->whereBetween(DB::raw('STR_TO_DATE(created_at, "%d %M %Y")'), [$startDate, $endDate]) // Use Y-m-d format for comparison
                                        ->count();
        //

        //  //  //  //  //  //  //  //  //  //

        $count_yes                  =   DB::table("clients")
                                            ->where('clients.BrandAvailability', 1)
                                            ->whereIn('clients.id_route_import', $route_links)
                                            ->whereBetween(DB::raw('STR_TO_DATE(created_at, "%d %M %Y")'), [$startDate, $endDate]) // Use Y-m-d format for comparison
                                            ->count();

        //  //  //  //  //  //  //  //  //  //

        $count_no                   =   DB::table("clients")
                                            ->where('clients.BrandAvailability', 0)
                                            ->whereIn('clients.id_route_import', $route_links)
                                            ->whereBetween(DB::raw('STR_TO_DATE(created_at, "%d %M %Y")'), [$startDate, $endDate]) // Use Y-m-d format for comparison
                                            ->count();

        //  //  //  //  //  //  //  //  //  //

        //
        $rows                           =   [];

        $row                            =   new stdClass();
        $row->label                     =   "Yes";
        $row->count_clients             =   $count_yes;
        $row->percentage_clients        =   $count_yes/$total_clients;

        array_push($rows, $row);
        //

        $row                            =   new stdClass();
        $row->label                     =   "No";
        $row->count_clients             =   $count_no;
        $row->percentage_clients        =   $count_no/$total_clients;

        array_push($rows, $row);
        //

        // Set Total By Yes/No    
        $by_brand_availability_report_table_data                                        =   new stdClass();

        $by_brand_availability_report_table_data->rows                                  =   $rows;

        $by_brand_availability_report_table_data->total_row                             =   new stdClass();
        $by_brand_availability_report_table_data->total_row->label                      =   "Total";
        $by_brand_availability_report_table_data->total_row->count_clients              =   $total_clients;
        $by_brand_availability_report_table_data->total_row->percentage_clients         =   1;

        //
        return $by_brand_availability_report_table_data;
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
        $rows                   =   [];

        //
        $total_by_day_object        =   new stdClass();
        $total_by_day_object->data  =   [];
        $total_by_day_object->total =   0;

        foreach ($users as $user) {

            //
            $row                    =   new stdClass();

            // Set Chart Label
            $row->label             =   $user->nom . " (".$user->id.")";
            $row->data              =   [];
            $row->total             =   0;

            // Set data
            foreach ($allDays as $index => $day) {

                //
                $count                      =   DB::table("clients")
                                                    ->where('owner', $user->id)
                                                    ->whereIn('clients.id_route_import', $route_links)
                                                    ->whereRaw('STR_TO_DATE(created_at, "%d %M %Y") = ?', [$day]) // Use Y-m-d format for comparison
                                                    ->count();

                // 
                $row->total             =   $row->total             +   $count;
                $total_by_day_object->total =   $total_by_day_object->total +   $count;

                //
                if (isset($total_by_day_object->data[$index])) {

                    $total_by_day_object->data[$index]     =   $total_by_day_object->data[$index] +   $count;
                }

                else {

                    array_push($total_by_day_object->data, $count);
                }

                //
                array_push($row->data, $count);
            }
            
            // Push Data to rows Array
            array_push($rows, $row);
        }
        //

        //
        $daily_reports                          =   new stdClass();

        $daily_reports->allDays                 =   $allDays;
        $daily_reports->rows                    =   $rows;
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

    public static function byTelAvailabilityReportTable(Request $request) {

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

        $rows               =   [];

        $count_total_yes    =   0;
        $count_total_no     =   0;

        foreach ($users as $key => $user) {

            //
            $row                            =   new stdClass();
            $row->label                     =   $user->nom . " (" . $user->id . ")";
            //

            //
            $count_yes                      =   DB::table("clients")
                                                    ->where('owner', $user->id)
                                                    ->where(function ($query) {
                                                        $query->whereNotNull('Tel')
                                                                ->where('Tel', '!=', '');
                                                    })
                                                    ->whereIn('clients.id_route_import', $route_links)
                                                    ->whereBetween(DB::raw('STR_TO_DATE(created_at, "%d %M %Y")'), [$startDate, $endDate]) // Use Y-m-d format for comparison
                                                    ->count();
            //

            //
            $count_no                       =   DB::table("clients")
                                                    ->where('owner', $user->id)
                                                    ->where(function ($query) {
                                                        $query->whereNull('Tel')
                                                                ->orWhere('Tel', '');
                                                    })
                                                    ->whereIn('clients.id_route_import', $route_links)
                                                    ->whereBetween(DB::raw('STR_TO_DATE(created_at, "%d %M %Y")'), [$startDate, $endDate]) // Use Y-m-d format for comparison
                                                    ->count();
            //

            //
            $row->count_yes                 =   $count_yes;
            $row->count_no                  =   $count_no;
            $row->count_total               =   $count_yes + $count_no;
            //

            //
            $count_total_yes                =   $count_total_yes    +   $count_yes;
            $count_total_no                 =   $count_total_no     +   $count_no;
            //

            array_push($rows, $row);
        }

        // Set Total By Yes/    
        $by_tel_availability_table                             =   new stdClass();

        $by_tel_availability_table->rows                       =   $rows;

        $by_tel_availability_table->total_row                  =   new stdClass();
        $by_tel_availability_table->total_row->label           =   "Total";
        $by_tel_availability_table->total_row->count_yes       =   $count_total_yes;
        $by_tel_availability_table->total_row->count_no        =   $count_total_no;
        $by_tel_availability_table->total_row->count_total     =   $count_total_yes + $count_total_no;

        return $by_tel_availability_table;
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

    public static function byCityReportTable(Request $request) {

        $route_links    =   json_decode($request->get("route_links"));

        //
        $total_expected_clients     =   0;
        $total_added_clients        =   0;
        //

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
        foreach ($cities as $city) {

            $city->added_clients        =   DB::table("clients")
                                                ->select("clients.*")
                                                ->where("clients.CityNo", $city->CityNo)
                                                ->whereIn('clients.id_route_import', $route_links)
                                                ->whereBetween(DB::raw('STR_TO_DATE(created_at, "%d %M %Y")'), [$startDate, $endDate]) // Use Y-m-d format for comparison
                                                ->count();

            //
            $city->gap                  =   $city->expected_clients -   $city->added_clients;

            //
            if($city->expected_clients  ==  0) {

                $city->percentage_clients   =   1;
            } 

            else {

                $city->percentage_clients   =   $city->added_clients / $city->expected_clients;
            }

            //
            if($city->percentage_clients    ==  0) {

                $city->status_clients       =   "Not Started";
            }

            if(($city->percentage_clients    >  0)&&(($city->percentage_clients    <  1))) {

                $city->status_clients       =   "In Progress";
            }

            if($city->percentage_clients    >=  1) {

                $city->status_clients       =   "Done";
            }

            //
            $total_expected_clients         =   $total_expected_clients +   $city->expected_clients;
            $total_added_clients            =   $total_added_clients    +   $city->added_clients;
        }

        $by_city_report_table_data                                      =   new stdClass();

        $by_city_report_table_data->rows                                =   $cities;

        $by_city_report_table_data->total_row                           =   new stdClass();
        $by_city_report_table_data->total_row->label                    =   "Total";
        $by_city_report_table_data->total_row->expected_clients         =   $total_expected_clients;
        $by_city_report_table_data->total_row->added_clients            =   $total_added_clients;
        $by_city_report_table_data->total_row->gap                      =   $by_city_report_table_data->total_row->expected_clients     -   $by_city_report_table_data->total_row->added_clients;

        if($by_city_report_table_data->total_row->expected_clients      ==  0) {

            $by_city_report_table_data->total_row->percentage_clients       =   1;
        }

        else {

            $by_city_report_table_data->total_row->percentage_clients       =   $by_city_report_table_data->total_row->added_clients / $by_city_report_table_data->total_row->expected_clients;
        }

        //
        if($by_city_report_table_data->total_row->percentage_clients    ==  0) {

            $by_city_report_table_data->total_row->status_clients       =   "Not Started";
        }

        if(($by_city_report_table_data->total_row->percentage_clients   >  0)&&(($by_city_report_table_data->total_row->percentage_clients    <  1))) {

            $by_city_report_table_data->total_row->status_clients       =   "In Progress";
        }

        if($by_city_report_table_data->total_row->percentage_clients    >=  1) {

            $by_city_report_table_data->total_row->status_clients       =   "Done";
        }

        //
        return $by_city_report_table_data;
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

        // cards    //  //  //  //  //  //  //  //  //  //  //  //  //

        $stats_details->number_clients_validated                     =   Statistic::numberClientsValidated($request);
        $stats_details->number_clients_nonvalidated                  =   Statistic::numberClientsNonValidated($request);
        $stats_details->number_clients_total                         =   Statistic::numberClientsTotal($request);
        $stats_details->number_clients_expected                      =   Statistic::numberClientsExpected($request);

        //  //  //  //  //  //  //  //  //  //  //  //  //  //  //  //

        // by_customer_type_report_chart_data
        $stats_details->by_customer_type_report_chart_data          =   Statistic::byCustomerTypeReport($request);
        $stats_details->by_customer_type_report_table_data          =   Statistic::byCustomerTypeReportTable($request);

        // by_brand_source_purchase_report_chart_data
        $stats_details->by_brand_source_purchase_report_chart_data  =   Statistic::byBrandSourcePurchaseReport($request);
        $stats_details->by_brand_source_purchase_report_table_data  =   Statistic::byBrandSourcePurchaseReportTable($request);

        // by_brand_availability_report_chart_data
        $stats_details->by_brand_availability_report_chart_data     =   Statistic::byBrandAvailabilityReport($request);
        $stats_details->by_brand_availability_report_table_data     =   Statistic::byBrandAvailabilityReportTable($request);

        // Daily Report
        $stats_details->daily_report_chart_data                     =   Statistic::dailyReport($request);
        $stats_details->daily_report_table_data                     =   Statistic::dailyReportTable($request);

        // By Tel Report
        $stats_details->by_tel_availability_report_chart_data       =   Statistic::byTelAvailabilityReport($request);
        $stats_details->by_tel_availability_report_table_data       =   Statistic::byTelAvailabilityReportTable($request);

        // By City Report
        $stats_details->by_city_report_chart_data                   =   Statistic::byCityReport($request);
        $stats_details->by_city_report_table_data                   =   Statistic::byCityReportTable($request);

        // Data Census
        $stats_details->data_census_report_table_data               =   Statistic::dataCensusReport($request);

        //
        return $stats_details;
    }
}