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

    //  //  //  //  //
    //  //  //  //  //  Parent Standard Stats Function
    //  //  //  //  //

    public static function standardStatistics(Request $request) {

        $stats_details  =   new stdClass();

        //
        $route_links    =   json_decode($request->get("route_links"));
        //

        // cards    //  //  //  //  //  //  //  //  //  //  //  //  //

        $stats_details->number_clients_tel_status_validated             =   Statistic::numberClientsTelValidated($request);
        $stats_details->number_clients_confirmed                        =   Statistic::numberClientsConfirmed($request);
        $stats_details->number_clients_validated                        =   Statistic::numberClientsValidated($request);
        $stats_details->number_clients_pending                          =   Statistic::numberClientsPending($request);
        $stats_details->number_clients_nonvalidated                     =   Statistic::numberClientsNonValidated($request);
        $stats_details->number_clients_visible                          =   Statistic::numberClientsVisible($request);
        $stats_details->number_clients_introuvable                      =   Statistic::numberClientsIntrouvable($request);
        $stats_details->number_clients_ferme                            =   Statistic::numberClientsFerme($request);
        $stats_details->number_clients_refus                            =   Statistic::numberClientsRefus($request);
        $stats_details->number_clients_total                            =   Statistic::numberClientsTotal($request);
        $stats_details->number_clients_expected                         =   Statistic::numberClientsExpected($request);
        $stats_details->number_clients_non_visible                      =   Statistic::numberClientsNonVisible($request);

        //  //  //  //  //  //  //  //  //  //  //  //  //  //  //  //

        // by_customer_type_report_chart_data
        $stats_details->by_customer_type_report_chart_data              =   Statistic::byCustomerTypeReport($request);
        $stats_details->by_customer_type_report_table_data              =   Statistic::byCustomerTypeReportTable($request);

        // by_open_customer_report_chart_data
        $stats_details->by_open_customer_report_chart_data              =   Statistic::byOpenCustomerReport($request);
        $stats_details->by_open_customer_report_table_data              =   Statistic::byOpenCustomerReportTable($request);

        // by_new_customer_report_chart_data
        $stats_details->by_new_customer_report_chart_data               =   Statistic::byNewCustomerReport($request);
        $stats_details->by_new_customer_report_table_data               =   Statistic::byNewCustomerReportTable($request);

        // Daily Report
        $stats_details->daily_report_chart_data                         =   Statistic::dailyReport($request);
        $stats_details->daily_report_table_data                         =   Statistic::dailyReportTable($request);

        // By Tel Report
        $stats_details->by_tel_validity_report_chart_data               =   Statistic::byTelValidityReport($request);
        $stats_details->by_tel_validity_report_table_data               =   Statistic::byTelValidityReportTable($request);

        // By City Report
        // $stats_details->by_city_report_chart_data                    =   Statistic::byCityReport($request);
        $stats_details->by_city_report_table_data                       =   Statistic::byCityReportTable($request);

        // Data Census
        $stats_details->data_census_report_table_data                   =   Statistic::dataCensusReport($request);

        // Total Clients
        $stats_details->total_clients                                   =   RouteImport::clients($route_links[0]);

        //  //  //  //  //  //
        //  //  //  //  //  //
        //  //  //  //  //  //

        //
        $route_link                                                     =   json_decode($request->get("route_links"))[0];

        //
        $stats_details->getDoublant                                     =   new stdClass();

        $stats_details->getDoublant->getDoublantCustomerCode            =   Client::getDoublesClients($request, $route_link);

        //
        return $stats_details;
    }

    //  //  //  //  //
    //  //  //  //  //  Cards
    //  //  //  //  //

    public static function numberClientsTelValidated($request) {

        //
        $route_links                    =   json_decode($request->get("route_links"));
        
        //
        $startDate                      =   Carbon::parse($request->get("start_date")); // Replace with your start date
        $endDate                        =   Carbon::parse($request->get("end_date"));   // Replace with your end date

        //
        $number_clients_tel_status_validated       =   DB::table("clients")
                                                ->whereIn('clients.id_route_import', $route_links)
                                                ->whereBetween(DB::raw('STR_TO_DATE(created_at, "%d %M %Y")'), [$startDate, $endDate]) // Use Y-m-d format for comparison
                                                ->where("clients.tel_status", "validated")
                                                ->count();

        return $number_clients_tel_status_validated;
    }

    public static function numberClientsConfirmed($request) {

        //
        $route_links                    =   json_decode($request->get("route_links"));
        
        //
        $startDate                      =   Carbon::parse($request->get("start_date")); // Replace with your start date
        $endDate                        =   Carbon::parse($request->get("end_date"));   // Replace with your end date

        //
        $number_clients_confirmed       =   DB::table("clients")
                                                ->whereIn('clients.id_route_import', $route_links)
                                                ->whereBetween(DB::raw('STR_TO_DATE(created_at, "%d %M %Y")'), [$startDate, $endDate]) // Use Y-m-d format for comparison
                                                ->where("clients.status", "confirmed")
                                                ->count();

        return $number_clients_confirmed;
    }

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

    public static function numberClientsPending($request) {

        //
        $route_links                    =   json_decode($request->get("route_links"));
        
        //
        $startDate                      =   Carbon::parse($request->get("start_date")); // Replace with your start date
        $endDate                        =   Carbon::parse($request->get("end_date"));   // Replace with your end date

        //
        $number_clients_pending    =   DB::table("clients")
                                                ->where("clients.status", "pending")
                                                ->whereIn('clients.id_route_import', $route_links)
                                                ->whereBetween(DB::raw('STR_TO_DATE(created_at, "%d %M %Y")'), [$startDate, $endDate]) // Use Y-m-d format for comparison
                                                ->count();

        return $number_clients_pending;
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

    public static function numberClientsVisible($request) {

        //
        $route_links                    =   json_decode($request->get("route_links"));
        
        //
        $startDate                      =   Carbon::parse($request->get("start_date")); // Replace with your start date
        $endDate                        =   Carbon::parse($request->get("end_date"));   // Replace with your end date

        //
        $number_clients_visible         =   DB::table("clients")
                                                ->where("clients.status", "visible")
                                                ->whereIn('clients.id_route_import', $route_links)
                                                ->whereBetween(DB::raw('STR_TO_DATE(created_at, "%d %M %Y")'), [$startDate, $endDate]) // Use Y-m-d format for comparison
                                                ->count();

        return $number_clients_visible;
    }

    public static function numberClientsFerme($request) {

        //
        $route_links                    =   json_decode($request->get("route_links"));
        
        //
        $startDate                      =   Carbon::parse($request->get("start_date")); // Replace with your start date
        $endDate                        =   Carbon::parse($request->get("end_date"));   // Replace with your end date

        //
        $number_clients_ferme           =   DB::table("clients")
                                                ->where("clients.status", "ferme")
                                                ->whereIn('clients.id_route_import', $route_links)
                                                ->whereBetween(DB::raw('STR_TO_DATE(created_at, "%d %M %Y")'), [$startDate, $endDate]) // Use Y-m-d format for comparison
                                                ->count();

        return $number_clients_ferme;
    }

    public static function numberClientsRefus($request) {

        //
        $route_links                    =   json_decode($request->get("route_links"));
        
        //
        $startDate                      =   Carbon::parse($request->get("start_date")); // Replace with your start date
        $endDate                        =   Carbon::parse($request->get("end_date"));   // Replace with your end date

        //
        $number_clients_refus           =   DB::table("clients")
                                                ->where("clients.status", "refus")
                                                ->whereIn('clients.id_route_import', $route_links)
                                                ->whereBetween(DB::raw('STR_TO_DATE(created_at, "%d %M %Y")'), [$startDate, $endDate]) // Use Y-m-d format for comparison
                                                ->count();

        return $number_clients_refus;
    }

    public static function numberClientsIntrouvable($request) {

        //
        $route_links                    =   json_decode($request->get("route_links"));
        
        //
        $startDate                      =   Carbon::parse($request->get("start_date")); // Replace with your start date
        $endDate                        =   Carbon::parse($request->get("end_date"));   // Replace with your end date

        //
        $number_clients_introuvable     =   DB::table("clients")
                                                ->where("clients.status", "introuvable")
                                                ->whereIn('clients.id_route_import', $route_links)
                                                ->whereBetween(DB::raw('STR_TO_DATE(created_at, "%d %M %Y")'), [$startDate, $endDate]) // Use Y-m-d format for comparison
                                                ->count();

        return $number_clients_introuvable;
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
        $cities                     =   DB::table("RTM_City")
                                            ->select("RTM_City.*"   , "RTM_City.CityNo as CityNo")
                                            ->join("route_import_districts"       , "RTM_City.DistrictNo"  , "route_import_districts.DistrictNo")
                                            ->whereIn('route_import_districts.id_route_import', $route_links)
                                            ->distinct("RTM_City.CityNo")
                                            ->get();

                                        // DB::table("RTM_City")
                                        //     ->select("RTM_City.*")
                                        //     ->join("clients", "RTM_City.CityNo", "clients.CityNo")
                                        //     ->whereIn('clients.id_route_import', $route_links)
                                        //     ->whereBetween(DB::raw('STR_TO_DATE(clients.created_at, "%d %M %Y")'), [$startDate, $endDate]) // Use Y-m-d format for comparison
                                        //     ->distinct("clients.CityNo")
                                        //     ->get();

        //
        $number_clients_expected    =   0;

        foreach ($cities as $city) {

            $number_clients_expected    =   $number_clients_expected    +   $city->expected_clients;
        }

        return $number_clients_expected;
    }

    public static function numberClientsNonVisible($request) {

        //
        $route_links                    =   json_decode($request->get("route_links"));
        
        //
        $startDate                      =   Carbon::parse($request->get("start_date")); // Replace with your start date
        $endDate                        =   Carbon::parse($request->get("end_date"));   // Replace with your end date

        //
        $number_clients_non_visible     =   DB::table("clients")
                                                ->whereIn('clients.id_route_import', $route_links)
                                                ->where('clients.status', '!=', 'visible')
                                                ->whereBetween(DB::raw('STR_TO_DATE(created_at, "%d %M %Y")'), [$startDate, $endDate]) // Use Y-m-d format for comparison
                                                ->count();

        //
        return $number_clients_non_visible;
    }

    //  //  //  //  //
    //  //  //  //  //  Customer Type Stats
    //  //  //  //  //

    public static function byCustomerTypeReport(Request $request) {

        $route_links    =   json_decode($request->get("route_links"));

        //
        $startDate      =   Carbon::parse($request->get("start_date")); // Replace with your start date
        $endDate        =   Carbon::parse($request->get("end_date"));   // Replace with your end date

        //
        $customer_types     =   DB::table("clients")
                                    ->select("clients.CustomerType")
                                    // ->where('clients.status', "validated")
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
                                                ->whereIn('clients.status', ["validated", "confirmed"])
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
                                    // ->where('clients.status', "validated")
                                    ->whereIn('clients.id_route_import', $route_links)
                                    ->whereBetween(DB::raw('STR_TO_DATE(created_at, "%d %M %Y")'), [$startDate, $endDate]) // Use Y-m-d format for comparison
                                    ->distinct("clients.CustomerType")
                                    ->pluck('CustomerType');

        $total_clients      =   DB::table("clients")
                                    ->select("clients.id")
                                    ->whereIn('clients.id_route_import', $route_links)
                                    ->whereIn('clients.status', ["validated", "confirmed"])
                                    ->whereBetween(DB::raw('STR_TO_DATE(created_at, "%d %M %Y")'), [$startDate, $endDate]) // Use Y-m-d format for comparison
                                    ->count();

        //
        $rows                       =   [];

        foreach ($customer_types as $customer_type) {

            $count                          =   DB::table("clients")
                                                    ->where('clients.CustomerType', $customer_type)
                                                    ->whereIn('clients.id_route_import', $route_links)
                                                    ->whereIn('clients.status', ["validated", "confirmed"])
                                                    ->whereBetween(DB::raw('STR_TO_DATE(created_at, "%d %M %Y")'), [$startDate, $endDate]) // Use Y-m-d format for comparison
                                                    ->count();

            $row                            =   new stdClass();
            $row->label                     =   $customer_type;
            $row->count_clients             =   $count;

            if($total_clients   ==  0) {

                $row->percentage_clients        =   1;
            }

            else {

                $row->percentage_clients        =   $count/$total_clients;
            }

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

    //  //  //  //  //
    //  //  //  //  //  Source Purchase Stats
    //  //  //  //  //

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
                                                ->whereIn('clients.status', ["validated", "confirmed"])
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
                                            ->whereIn('clients.status', ["validated", "confirmed"])
                                            ->whereBetween(DB::raw('STR_TO_DATE(created_at, "%d %M %Y")'), [$startDate, $endDate]) // Use Y-m-d format for comparison
                                            ->count();
        //

        //
        $rows                       =   [];

        foreach ($brand_source_purchases as $brand_source_purchase) {

            $count                      =   DB::table("clients")
                                                ->where('clients.BrandSourcePurchase', $brand_source_purchase)
                                                ->whereIn('clients.id_route_import', $route_links)
                                                ->whereIn('clients.status', ["validated", "confirmed"])
                                                ->whereBetween(DB::raw('STR_TO_DATE(created_at, "%d %M %Y")'), [$startDate, $endDate]) // Use Y-m-d format for comparison
                                                ->count();
            //
            $row                            =   new stdClass();
            $row->label                     =   $brand_source_purchase;
            $row->count_clients             =   $count;

            if($total_clients   ==  0) {

                $row->percentage_clients        =   1;
            }

            else {

                $row->percentage_clients        =   $count/$total_clients;
            }

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

    //  //  //  //  //
    //  //  //  //  //  Brand Availability Stats
    //  //  //  //  //

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
                                            ->where('clients.BrandAvailability', "Oui")
                                            ->whereIn('clients.id_route_import', $route_links)
                                            ->whereIn('clients.status', ["validated", "confirmed"])
                                            ->whereBetween(DB::raw('STR_TO_DATE(created_at, "%d %M %Y")'), [$startDate, $endDate]) // Use Y-m-d format for comparison
                                            ->count();

        array_push($dataset->data, $count_yes);

        //  //  //  //  //  //  //  //  //  //

        $count_no                   =   DB::table("clients")
                                            ->where('clients.BrandAvailability', "Non")
                                            ->whereIn('clients.id_route_import', $route_links)
                                            ->whereIn('clients.status', ["validated", "confirmed"])
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
                                        ->whereIn('clients.status', ["validated", "confirmed"])
                                        ->whereBetween(DB::raw('STR_TO_DATE(created_at, "%d %M %Y")'), [$startDate, $endDate]) // Use Y-m-d format for comparison
                                        ->count();
        //

        //  //  //  //  //  //  //  //  //  //

        $count_yes                  =   DB::table("clients")
                                            ->where('clients.BrandAvailability', "Oui")
                                            ->whereIn('clients.id_route_import', $route_links)
                                            ->whereIn('clients.status', ["validated", "confirmed"])
                                            ->whereBetween(DB::raw('STR_TO_DATE(created_at, "%d %M %Y")'), [$startDate, $endDate]) // Use Y-m-d format for comparison
                                            ->count();

        //  //  //  //  //  //  //  //  //  //

        $count_no                   =   DB::table("clients")
                                            ->where('clients.BrandAvailability', "Non")
                                            ->whereIn('clients.id_route_import', $route_links)
                                            ->whereIn('clients.status', ["validated", "confirmed"])
                                            ->whereBetween(DB::raw('STR_TO_DATE(created_at, "%d %M %Y")'), [$startDate, $endDate]) // Use Y-m-d format for comparison
                                            ->count();

        //  //  //  //  //  //  //  //  //  //

        //
        $rows                           =   [];

        $row                            =   new stdClass();
        $row->label                     =   "Yes";
        $row->count_clients             =   $count_yes;

        //

        if($total_clients   ==  0) {

            $row->percentage_clients        =   1;
        }

        else {

            $row->percentage_clients        =   $count_yes/$total_clients;
        }

        //

        array_push($rows, $row);
        //

        $row                            =   new stdClass();
        $row->label                     =   "No";
        $row->count_clients             =   $count_no;

        //

        if($total_clients   ==  0) {

            $row->percentage_clients        =   1;
        }

        else {

            $row->percentage_clients        =   $count_no/$total_clients;
        }

        //

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

    //  //  //  //  //
    //  //  //  //  //  Open Customer Stats
    //  //  //  //  //

    public static function byOpenCustomerReport(Request $request) {

        $route_links        =   json_decode($request->get("route_links"));

        //
        $startDate          =   Carbon::parse($request->get("start_date")); // Replace with your start date
        $endDate            =   Carbon::parse($request->get("end_date"));   // Replace with your end date
        //

        //
        $datasets                       =   [];

        //  //  //  //  //  //  //  //  //  //

        $dataset                        =   new stdClass();
        $dataset->data                  =   [];

        //  //  //  //  //  //  //  //  //  //

        $count_ouvert_confirmed         =   DB::table("clients")
                                                ->where([['clients.OpenCustomer', 'Ouvert'], ['clients.status', "confirmed"]])
                                                ->whereIn('clients.id_route_import', $route_links)
                                                ->whereBetween(DB::raw('STR_TO_DATE(created_at, "%d %M %Y")'), [$startDate, $endDate]) // Use Y-m-d format for comparison
                                                ->count();

        array_push($dataset->data, $count_ouvert_confirmed);

        //  //  //  //  //  //  //  //  //  //

        $count_ouvert_validated         =   DB::table("clients")
                                                ->where([['clients.OpenCustomer', 'Ouvert'], ['clients.status', "validated"]])
                                                ->whereIn('clients.id_route_import', $route_links)
                                                ->whereBetween(DB::raw('STR_TO_DATE(created_at, "%d %M %Y")'), [$startDate, $endDate]) // Use Y-m-d format for comparison
                                                ->count();

        array_push($dataset->data, $count_ouvert_validated);

        //  //  //  //  //  //  //  //  //  //

        $count_ferme                    =   DB::table("clients")
                                                ->where([['clients.OpenCustomer', 'Ferme'], ['clients.status', "ferme"]])
                                                ->whereIn('clients.id_route_import', $route_links)
                                                ->whereBetween(DB::raw('STR_TO_DATE(created_at, "%d %M %Y")'), [$startDate, $endDate]) // Use Y-m-d format for comparison
                                                ->count();

        array_push($dataset->data, $count_ferme);

        //  //  //  //  //  //  //  //  //  //

        $count_refus                    =   DB::table("clients")
                                                ->where([['clients.OpenCustomer', 'refus'], ['clients.status', "refus"]])
                                                ->whereIn('clients.id_route_import', $route_links)
                                                ->whereBetween(DB::raw('STR_TO_DATE(created_at, "%d %M %Y")'), [$startDate, $endDate]) // Use Y-m-d format for comparison
                                                ->count();

        array_push($dataset->data, $count_refus);

        //  //  //  //  //  //  //  //  //  //

        $count_introuvable              =   DB::table("clients")
                                                ->where([['clients.OpenCustomer', 'Introuvable'], ['clients.status', "introuvable"]])
                                                ->whereIn('clients.id_route_import', $route_links)
                                                ->whereBetween(DB::raw('STR_TO_DATE(created_at, "%d %M %Y")'), [$startDate, $endDate]) // Use Y-m-d format for comparison
                                                ->count();

        array_push($dataset->data, $count_introuvable);

        //  //  //  //  //  //  //  //  //  //

        array_push($datasets, $dataset);

        //
        $by_open_customer_reports               =   new stdClass();

        $by_open_customer_reports->labels       =   ["Confirme ouvert", "Valide ouvert", "Ferme", "Refus", "Introuvable"];
        $by_open_customer_reports->datasets     =   $datasets;

        //
        return $by_open_customer_reports;
    }

    public static function byOpenCustomerReportTable(Request $request) {

        $route_links        =   json_decode($request->get("route_links"));

        //
        $startDate          =   Carbon::parse($request->get("start_date")); // Replace with your start date
        $endDate            =   Carbon::parse($request->get("end_date"));   // Replace with your end date
        //

        //
        $total_clients          =   DB::table("clients")
                                        ->select("clients.id")
                                        ->where(function($q) {
                                            $q->whereIn('clients.status', ['confirmed', 'validated'])
                                            ->orWhere('clients.status', 'ferme')
                                            ->orWhere('clients.status', 'refus')
                                            ->orWhere('clients.status', 'introuvable');
                                        })
                                        ->whereIn('clients.id_route_import', $route_links)
                                        ->whereBetween(DB::raw('STR_TO_DATE(created_at, "%d %M %Y")'), [$startDate, $endDate]) // Use Y-m-d format for comparison
                                        ->count();
        //

        //  //  //  //  //  //  //  //  //  //

        $count_ouvert_confirmed         =   DB::table("clients")
                                                ->where([['clients.OpenCustomer', 'Ouvert'], ['clients.status', "confirmed"]])
                                                ->whereIn('clients.id_route_import', $route_links)
                                                ->whereBetween(DB::raw('STR_TO_DATE(created_at, "%d %M %Y")'), [$startDate, $endDate]) // Use Y-m-d format for comparison
                                                ->count();

        //  //  //  //  //  //  //  //  //  //

        $count_ouvert_validated         =   DB::table("clients")
                                                ->where([['clients.OpenCustomer', 'Ouvert'], ['clients.status', "validated"]])
                                                ->whereIn('clients.id_route_import', $route_links)
                                                ->whereBetween(DB::raw('STR_TO_DATE(created_at, "%d %M %Y")'), [$startDate, $endDate]) // Use Y-m-d format for comparison
                                                ->count();

        //  //  //  //  //  //  //  //  //  //

        $count_ferme                    =   DB::table("clients")
                                                ->where([['clients.OpenCustomer', 'Ferme'], ['clients.status', "ferme"]])
                                                ->whereIn('clients.id_route_import', $route_links)
                                                ->whereBetween(DB::raw('STR_TO_DATE(created_at, "%d %M %Y")'), [$startDate, $endDate]) // Use Y-m-d format for comparison
                                                ->count();

        //  //  //  //  //  //  //  //  //  //

        $count_refus                    =   DB::table("clients")
                                                ->where([['clients.OpenCustomer', 'refus'], ['clients.status', "refus"]])
                                                ->whereIn('clients.id_route_import', $route_links)
                                                ->whereBetween(DB::raw('STR_TO_DATE(created_at, "%d %M %Y")'), [$startDate, $endDate]) // Use Y-m-d format for comparison
                                                ->count();

        //  //  //  //  //  //  //  //  //  //

        $count_introuvable              =   DB::table("clients")
                                                ->where([['clients.OpenCustomer', 'Introuvable'], ['clients.status', "introuvable"]])
                                                ->whereIn('clients.id_route_import', $route_links)
                                                ->whereBetween(DB::raw('STR_TO_DATE(created_at, "%d %M %Y")'), [$startDate, $endDate]) // Use Y-m-d format for comparison
                                                ->count();

        //  //  //  //  //  //  //  //  //  //

        //
        $rows                           =   [];

        $row                            =   new stdClass();
        $row->label                     =   "Confirme ouvert";
        $row->count_clients             =   $count_ouvert_confirmed;

        //

        if($total_clients   ==  0) {

            $row->percentage_clients        =   1;
        }

        else {

            $row->percentage_clients        =   $count_ouvert_confirmed/$total_clients;
        }

        //

        array_push($rows, $row);
        //

        //
        $rows                           =   [];

        $row                            =   new stdClass();
        $row->label                     =   "Valide ouvert";
        $row->count_clients             =   $count_ouvert_validated;

        //

        if($total_clients   ==  0) {

            $row->percentage_clients        =   1;
        }

        else {

            $row->percentage_clients        =   $count_ouvert_validated/$total_clients;
        }

        //

        array_push($rows, $row);
        //

        $row                            =   new stdClass();
        $row->label                     =   "Ferme";
        $row->count_clients             =   $count_ferme;

        //

        if($total_clients   ==  0) {

            $row->percentage_clients        =   1;
        }

        else {

            $row->percentage_clients        =   $count_ferme/$total_clients;
        }

        //

        array_push($rows, $row);
        //

        $row                            =   new stdClass();
        $row->label                     =   "Refus";
        $row->count_clients             =   $count_refus;

        //

        if($total_clients   ==  0) {

            $row->percentage_clients        =   1;
        }

        else {

            $row->percentage_clients        =   $count_refus/$total_clients;
        }

        //

        array_push($rows, $row);
        //

        $row                            =   new stdClass();
        $row->label                     =   "Introuvable";
        $row->count_clients             =   $count_introuvable;

        //

        if($total_clients   ==  0) {

            $row->percentage_clients        =   1;
        }

        else {

            $row->percentage_clients        =   $count_introuvable/$total_clients;
        }

        //

        array_push($rows, $row);
        //

        // Set Total By Yes/No    
        $by_open_customer_report_table_data                                     =   new stdClass();

        $by_open_customer_report_table_data->rows                               =   $rows;

        $by_open_customer_report_table_data->total_row                          =   new stdClass();
        $by_open_customer_report_table_data->total_row->label                   =   "Total";
        $by_open_customer_report_table_data->total_row->count_clients           =   $total_clients;
        $by_open_customer_report_table_data->total_row->percentage_clients      =   1;

        //
        return $by_open_customer_report_table_data;
    }

    //  //  //  //  //
    //  //  //  //  //  New Customer Stats
    //  //  //  //  //

    public static function byNewCustomerReport(Request $request) {

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

        $count_client_existant      =   DB::table("clients")
                                            ->where('clients.NewCustomer', 'Client Existant')
                                            ->whereIn('clients.id_route_import', ["confirmed", "validated"])
                                            ->whereIn('clients.id_route_import', $route_links)
                                            ->whereBetween(DB::raw('STR_TO_DATE(created_at, "%d %M %Y")'), [$startDate, $endDate]) // Use Y-m-d format for comparison
                                            ->count();

        array_push($dataset->data, $count_client_existant);

        //  //  //  //  //  //  //  //  //  //

        $count_nouveau_client       =   DB::table("clients")
                                            ->where('clients.NewCustomer', 'Nouveau Client')
                                            ->whereIn('clients.id_route_import', ["confirmed", "validated"])
                                            ->whereIn('clients.id_route_import', $route_links)
                                            ->whereBetween(DB::raw('STR_TO_DATE(created_at, "%d %M %Y")'), [$startDate, $endDate]) // Use Y-m-d format for comparison
                                            ->count();

        array_push($dataset->data, $count_nouveau_client);

        //  //  //  //  //  //  //  //  //  //

        array_push($datasets, $dataset);

        //
        $by_new_customer_reports                =   new stdClass();

        $by_new_customer_reports->labels        =   ["Clients Existants", "Nouveaux Clients"];
        $by_new_customer_reports->datasets      =   $datasets;

        //
        return $by_new_customer_reports;
    }

    public static function byNewCustomerReportTable(Request $request) {

        $route_links        =   json_decode($request->get("route_links"));

        //
        $startDate          =   Carbon::parse($request->get("start_date")); // Replace with your start date
        $endDate            =   Carbon::parse($request->get("end_date"));   // Replace with your end date
        //

        //
        $total_clients          =   DB::table("clients")
                                        ->select("clients.id")
                                        ->whereIn('clients.status', ['confirmed', 'validated'])
                                        ->whereIn('clients.id_route_import', $route_links)
                                        ->whereBetween(DB::raw('STR_TO_DATE(created_at, "%d %M %Y")'), [$startDate, $endDate]) // Use Y-m-d format for comparison
                                        ->count();
        //

        //  //  //  //  //  //  //  //  //  //

        $count_client_existant  =   DB::table("clients")
                                        ->where('clients.NewCustomer', 'Client Existant')
                                        ->whereIn('clients.status', ['confirmed', 'validated'])
                                        ->whereIn('clients.id_route_import', $route_links)
                                        ->whereBetween(DB::raw('STR_TO_DATE(created_at, "%d %M %Y")'), [$startDate, $endDate]) // Use Y-m-d format for comparison
                                        ->count();

        //  //  //  //  //  //  //  //  //  //

        $count_nouveau_client   =   DB::table("clients")
                                        ->where('clients.NewCustomer', 'Nouveau Client')
                                        ->whereIn('clients.status', ['confirmed', 'validated'])
                                        ->whereIn('clients.id_route_import', $route_links)
                                        ->whereBetween(DB::raw('STR_TO_DATE(created_at, "%d %M %Y")'), [$startDate, $endDate]) // Use Y-m-d format for comparison
                                        ->count();

        //  //  //  //  //  //  //  //  //  //

        //
        $rows                           =   [];

        $row                            =   new stdClass();
        $row->label                     =   "Clients Existants";
        $row->count_clients             =   $count_client_existant;

        //

        if($total_clients   ==  0) {

            $row->percentage_clients        =   1;
        }

        else {

            $row->percentage_clients        =   $count_client_existant/$total_clients;
        }

        //

        array_push($rows, $row);

        //

        $row                            =   new stdClass();
        $row->label                     =   "Nouveaux Clients";
        $row->count_clients             =   $count_nouveau_client;

        //

        if($total_clients   ==  0) {

            $row->percentage_clients        =   1;
        }

        else {

            $row->percentage_clients        =   $count_nouveau_client/$total_clients;
        }

        //

        array_push($rows, $row);
        //

        // Set Total By Yes/No    
        $by_new_customer_report_table_data                                      =   new stdClass();

        $by_new_customer_report_table_data->rows                                =   $rows;

        $by_new_customer_report_table_data->total_row                           =   new stdClass();
        $by_new_customer_report_table_data->total_row->label                    =   "Total";
        $by_new_customer_report_table_data->total_row->count_clients            =   $total_clients;
        $by_new_customer_report_table_data->total_row->percentage_clients       =   1;

        //
        return $by_new_customer_report_table_data;
    }

    //  //  //  //  //
    //  //  //  //  //  Tel Status Stats
    //  //  //  //  //

    public static function byTelValidityReport(Request $request) {

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
                                ->where('users.type_user', 'FrontOffice')
                                ->distinct("users.id")
                                ->get();
        //

        //  //  //  //  //

        // Set Y Data   //

        //
        $datasets       =   [];

        // Set Yes Data

        //
        $dataset_validated              =   new stdClass();

        // Set Chart Label
        $dataset_validated->label       =   "Validated";
        $dataset_validated->data        =   [];

        foreach ($users as $user) {

            //
            $count                      =   DB::table("clients")
                                                ->where([['owner', $user->id], ['clients.tel_status', 'validated']])
                                                ->whereIn('clients.status', ['confirmed', 'validated'])
                                                ->whereIn('clients.id_route_import', $route_links)
                                                ->whereBetween(DB::raw('STR_TO_DATE(created_at, "%d %M %Y")'), [$startDate, $endDate]) // Use Y-m-d format for comparison
                                                ->count();

            //
            array_push($dataset_validated->data, $count);
        }

        // Push Data to datasets Array
        array_push($datasets, $dataset_validated);

        //  //  //  //  //

        //
        $dataset_nonvalidated                     =   new stdClass();

        // Set Chart Label
        $dataset_nonvalidated->label              =   "NonValidated";
        $dataset_nonvalidated->data               =   [];

        // Set No Data
        foreach ($users as $user) {

            //
            $count                      =   DB::table("clients")
                                                ->where([['owner', $user->id], ['clients.tel_status', 'nonvalidated']])
                                                ->whereIn('clients.status', ['confirmed', 'validated'])
                                                ->whereIn('clients.id_route_import', $route_links)
                                                ->whereBetween(DB::raw('STR_TO_DATE(created_at, "%d %M %Y")'), [$startDate, $endDate]) // Use Y-m-d format for comparison
                                                ->count();

            //
            array_push($dataset_nonvalidated->data, $count);
        }

        // Push Data to datasets Array
        array_push($datasets, $dataset_nonvalidated);

        //  //  //  //  //

        // Set X Labels //

        $labels = [];

        foreach ($users as $user) {

            $labels[]   =   $user->first_name . " " . $user->last_name . " (" . $user->username . ")";
        }

        //  //  //  //  //

        $by_tel_validity_reports                =   new stdClass();

        $by_tel_validity_reports->labels        =   $labels;
        $by_tel_validity_reports->datasets      =   $datasets;

        //
        return $by_tel_validity_reports;
    }

    public static function byTelValidityReportTable(Request $request) {

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
                                ->where('users.type_user', 'FrontOffice')
                                ->distinct("users.id")
                                ->get();
        //

        $rows                       =   [];

        $count_total_validated      =   0;
        $count_total_nonvalidated   =   0;

        foreach ($users as $key => $user) {

            //
            $row                            =   new stdClass();
            $row->label                     =   $user->first_name . " " . $user->last_name . " (" . $user->username . ")";
            //

            //
            $count_validated                =   DB::table("clients")
                                                    ->where([['owner', $user->id], ['clients.tel_status', 'validated']])
                                                    ->whereIn('clients.status', ['confirmed', 'validated'])
                                                    ->whereIn('clients.id_route_import', $route_links)
                                                    ->whereBetween(DB::raw('STR_TO_DATE(created_at, "%d %M %Y")'), [$startDate, $endDate]) // Use Y-m-d format for comparison
                                                    ->count();
            //

            //
            $count_nonvalidated             =   DB::table("clients")
                                                    ->where([['owner', $user->id], ['clients.tel_status', 'nonvalidated']])
                                                    ->whereIn('clients.status', ['confirmed', 'validated'])
                                                    ->whereIn('clients.id_route_import', $route_links)
                                                    ->whereBetween(DB::raw('STR_TO_DATE(created_at, "%d %M %Y")'), [$startDate, $endDate]) // Use Y-m-d format for comparison
                                                    ->count();
            //

            //
            $row->count_validated           =   $count_validated;
            $row->count_nonvalidated        =   $count_nonvalidated;
            $row->count_total               =   $count_validated + $count_nonvalidated;
            //

            //
            $count_total_validated          =   $count_total_validated      +   $count_validated;
            $count_total_nonvalidated       =   $count_total_nonvalidated   +   $count_nonvalidated;
            //

            array_push($rows, $row);
        }

        // Set Total By Yes/    
        $by_tel_validity_table                                      =   new stdClass();

        $by_tel_validity_table->rows                                =   $rows;

        $by_tel_validity_table->total_row                           =   new stdClass();
        $by_tel_validity_table->total_row->label                    =   "Total";
        $by_tel_validity_table->total_row->count_validated          =   $count_total_validated;
        $by_tel_validity_table->total_row->count_nonvalidated       =   $count_total_nonvalidated;
        $by_tel_validity_table->total_row->count_total              =   $count_total_validated + $count_total_nonvalidated;

        return $by_tel_validity_table;
    }

    //  //  //  //  //
    //  //  //  //  //  City Stats
    //  //  //  //  //

    public static function byCityReport(Request $request) {

        $route_links    =   json_decode($request->get("route_links"));

        //
        $startDate      =   Carbon::parse($request->get("start_date")); // Replace with your start date
        $endDate        =   Carbon::parse($request->get("end_date"));   // Replace with your end date
        //

        //
        $cities         =   DB::table("RTM_City")
                                ->select("RTM_City.*", "clients.CityNo")
                                ->join("clients", "RTM_City.CityNo", "clients.CityNo")
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
                                                ->whereIn('clients.status', ['confirmed', 'validated'])
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
        $total_confirmed_clients    =   0;
        $total_added_clients        =   0;
        $total_validated_clients    =   0;
        $total_introuvable_clients  =   0;
        $total_ferme_clients        =   0;
        $total_refus_clients        =   0;
        //

        //
        $startDate      =   Carbon::parse($request->get("start_date")); // Replace with your start date
        $endDate        =   Carbon::parse($request->get("end_date"));   // Replace with your end date
        //

        //
        $cities         =   DB::table("RTM_City")
                                ->select("RTM_City.*"   , "RTM_City.CityNo as CityNo")
                                ->join("route_import_districts"       , "RTM_City.DistrictNo"  , "route_import_districts.DistrictNo")
                                ->whereIn('route_import_districts.id_route_import', $route_links)
                                ->distinct("RTM_City.CityNo")
                                ->get();

        //
        foreach ($cities as $city) {

            $city->added_clients        =   DB::table("clients")
                                                ->select("clients.*")
                                                ->where('clients.CityNo',     $city->CityNo)
                                                ->whereIn('clients.id_route_import', $route_links)
                                                ->whereBetween(
                                                    DB::raw('STR_TO_DATE(created_at, "%d %M %Y")'),
                                                    [$startDate, $endDate]
                                                )
                                                ->where(function($q){
                                                    $q
                                                    ->whereIn('clients.status', ['confirmed', 'validated'])
                                                    ->orWhere('clients.status', 'introuvable')
                                                    ->orWhere('clients.status', 'ferme')
                                                    ->orWhere('clients.status', 'refus');
                                                })
                                                ->count();

            $city->confirmed_clients    =   DB::table("clients")
                                                ->select("clients.*")
                                                ->where([["clients.CityNo", $city->CityNo], ["clients.status", "confirmed"]])
                                                ->whereIn('clients.id_route_import', $route_links)
                                                ->whereBetween(DB::raw('STR_TO_DATE(created_at, "%d %M %Y")'), [$startDate, $endDate]) // Use Y-m-d format for comparison
                                                ->count();

            $city->validated_clients    =   DB::table("clients")
                                                ->select("clients.*")
                                                ->where([["clients.CityNo", $city->CityNo], ["clients.status", "validated"]])
                                                ->whereIn('clients.id_route_import', $route_links)
                                                ->whereBetween(DB::raw('STR_TO_DATE(created_at, "%d %M %Y")'), [$startDate, $endDate]) // Use Y-m-d format for comparison
                                                ->count();

            $city->introuvable_clients  =   DB::table("clients")
                                                ->select("clients.*")
                                                ->where([["clients.CityNo", $city->CityNo], ["clients.status", "introuvable"]])
                                                ->whereIn('clients.id_route_import', $route_links)
                                                ->whereBetween(DB::raw('STR_TO_DATE(created_at, "%d %M %Y")'), [$startDate, $endDate]) // Use Y-m-d format for comparison
                                                ->count();

            $city->ferme_clients        =   DB::table("clients")
                                                ->select("clients.*")
                                                ->where([["clients.CityNo", $city->CityNo], ["clients.status", "ferme"]])
                                                ->whereIn('clients.id_route_import', $route_links)
                                                ->whereBetween(DB::raw('STR_TO_DATE(created_at, "%d %M %Y")'), [$startDate, $endDate]) // Use Y-m-d format for comparison
                                                ->count();

            $city->refus_clients        =   DB::table("clients")
                                                ->select("clients.*")
                                                ->where([["clients.CityNo", $city->CityNo], ["clients.status", "refus"]])
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

            $total_added_clients            =   $total_added_clients        +   $city->added_clients;
            $total_confirmed_clients        =   $total_confirmed_clients    +   $city->confirmed_clients;
            $total_validated_clients        =   $total_validated_clients    +   $city->validated_clients;
            $total_introuvable_clients      =   $total_introuvable_clients  +   $city->introuvable_clients;
            $total_ferme_clients            =   $total_ferme_clients        +   $city->ferme_clients;
            $total_refus_clients            =   $total_refus_clients        +   $city->refus_clients;
        }

        $by_city_report_table_data                                      =   new stdClass();

        $by_city_report_table_data->rows                                =   $cities;

        $by_city_report_table_data->total_row                           =   new stdClass();
        $by_city_report_table_data->total_row->label                    =   "Total";
        $by_city_report_table_data->total_row->expected_clients         =   $total_expected_clients;

        $by_city_report_table_data->total_row->added_clients            =   $total_added_clients;
        $by_city_report_table_data->total_row->confirmed_clients        =   $total_confirmed_clients;
        $by_city_report_table_data->total_row->validated_clients        =   $total_validated_clients;
        $by_city_report_table_data->total_row->introuvable_clients      =   $total_introuvable_clients;
        $by_city_report_table_data->total_row->ferme_clients            =   $total_ferme_clients;
        $by_city_report_table_data->total_row->refus_clients            =   $total_refus_clients;

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

    //  //  //  //  //
    //  //  //  //  //  Daily Report Stats
    //  //  //  //  //

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
                                ->where('users.type_user', 'FrontOffice')
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
            $dataset->label             =   $user->first_name . " " . $user->last_name . " (" . $user->username . ")";
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
                                ->where('users.type_user', 'FrontOffice')
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
        $rows                           =   [];

        //
        $total_by_day_object            =   new stdClass();
        $total_by_day_object->data      =   [];

        $total_by_day_object->total_obj                     =   new stdClass();
        $total_by_day_object->total_obj->count_ouvert       =   0;
        $total_by_day_object->total_obj->count_introuvable  =   0;   
        $total_by_day_object->total_obj->count_ferme        =   0;
        $total_by_day_object->total_obj->count_refus        =   0;
        $total_by_day_object->total_obj->count_total        =   0;

        foreach ($users as $user) {

            //
            $row                    =   new stdClass();

            // Set Chart Label
            $row->label                     =   $user->first_name . " " . $user->last_name . " (" . $user->username . ")";
            $row->data                      =   [];

            $row->total_obj                     =   new stdClass();
            $row->total_obj->count_ouvert       =   0;
            $row->total_obj->count_introuvable  =   0;
            $row->total_obj->count_ferme        =   0;
            $row->total_obj->count_refus        =   0;
            $row->total_obj->count_total        =   0;

            // Set data
            foreach ($allDays as $index => $day) {

                //
                $count_ouvert                   =   DB::table("clients")
                                                        ->where([['owner', $user->id], ['status', '!=', 'introuvable'], ['status', '!=','ferme'], ['status', '!=','refus']])
                                                        ->whereIn('clients.id_route_import', $route_links)
                                                        ->whereRaw('STR_TO_DATE(created_at, "%d %M %Y") = ?', [$day]) // Use Y-m-d format for comparison
                                                        ->count();

                //
                $count_introuvable              =   DB::table("clients")
                                                        ->where([['owner', $user->id], ['status', '=','introuvable']])
                                                        ->whereIn('clients.id_route_import', $route_links)
                                                        ->whereRaw('STR_TO_DATE(created_at, "%d %M %Y") = ?', [$day]) // Use Y-m-d format for comparison
                                                        ->count();

                //
                $count_ferme                    =   DB::table("clients")
                                                        ->where([['owner', $user->id], ['status', '=','ferme']])
                                                        ->whereIn('clients.id_route_import', $route_links)
                                                        ->whereRaw('STR_TO_DATE(created_at, "%d %M %Y") = ?', [$day]) // Use Y-m-d format for comparison
                                                        ->count();

                //
                $count_refus                    =   DB::table("clients")
                                                        ->where([['owner', $user->id], ['status', '=','refus']])
                                                        ->whereIn('clients.id_route_import', $route_links)
                                                        ->whereRaw('STR_TO_DATE(created_at, "%d %M %Y") = ?', [$day]) // Use Y-m-d format for comparison
                                                        ->count();

                //
                $count_obj                      =   new stdClass();

                $count_obj->count_ouvert        =   $count_ouvert                                                               ;
                $count_obj->count_introuvable   =   $count_introuvable                                                          ;
                $count_obj->count_ferme         =   $count_ferme                                                                ;
                $count_obj->count_refus         =   $count_refus                                                                ;
                $count_obj->count_total         =   $count_ouvert   +   $count_introuvable    +   $count_ferme  +   $count_refus;

                //
                array_push($row->data, $count_obj);

                //  //  //

                // 
                $row->total_obj->count_ouvert       =   $row->total_obj->count_ouvert           +   $count_obj->count_ouvert;
                $row->total_obj->count_introuvable  =   $row->total_obj->count_introuvable      +   $count_obj->count_introuvable;
                $row->total_obj->count_ferme        =   $row->total_obj->count_ferme            +   $count_obj->count_ferme;
                $row->total_obj->count_refus        =   $row->total_obj->count_refus            +   $count_obj->count_refus;
                $row->total_obj->count_total        =   $row->total_obj->count_total            +   $count_obj->count_total;

                //
                $total_by_day_object->total_obj->count_ouvert       =   $total_by_day_object->total_obj->count_ouvert       +   $count_obj->count_ouvert;
                $total_by_day_object->total_obj->count_introuvable  =   $total_by_day_object->total_obj->count_introuvable  +   $count_obj->count_introuvable;
                $total_by_day_object->total_obj->count_ferme        =   $total_by_day_object->total_obj->count_ferme        +   $count_obj->count_ferme;
                $total_by_day_object->total_obj->count_refus        =   $total_by_day_object->total_obj->count_refus        +   $count_obj->count_refus;
                $total_by_day_object->total_obj->count_total        =   $total_by_day_object->total_obj->count_total        +   $count_obj->count_total;

                //
                if (isset($total_by_day_object->data[$index])) {
                    $total_by_day_object->data[$index]->count_ouvert        =   $total_by_day_object->data[$index]->count_ouvert        +   $count_obj->count_ouvert;
                    $total_by_day_object->data[$index]->count_introuvable   =   $total_by_day_object->data[$index]->count_introuvable   +   $count_obj->count_introuvable;
                    $total_by_day_object->data[$index]->count_ferme         =   $total_by_day_object->data[$index]->count_ferme         +   $count_obj->count_ferme;
                    $total_by_day_object->data[$index]->count_refus         =   $total_by_day_object->data[$index]->count_refus         +   $count_obj->count_refus;
                    $total_by_day_object->data[$index]->count_total         =   $total_by_day_object->data[$index]->count_total         +   $count_obj->count_total;
                }

                else {

                    $data                                               =   new stdClass();
                    $data->count_ouvert                                 =   $count_obj->count_ouvert;
                    $data->count_introuvable                            =   $count_obj->count_introuvable;
                    $data->count_ferme                                  =   $count_obj->count_ferme;
                    $data->count_refus                                  =   $count_obj->count_refus;
                    $data->count_total                                  =   $count_obj->count_total;

                    //
                    array_push($total_by_day_object->data, $data);
                }
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

    //  //  //  //  //
    //  //  //  //  //  Data Census
    //  //  //  //  //

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
                                        // DB::raw('CASE WHEN clients.BrandAvailability = "Oui" THEN "Yes" ELSE "No" END AS BrandAvailabilityText'), 
                                        DB::raw('clients.BrandAvailability AS BrandAvailabilityText'), 
                                        DB::raw('CASE WHEN clients.Tel IS NOT NULL AND clients.Tel != "" THEN "Yes" ELSE "No" END AS TelValidityText'),
                                        "users.username as owner_username")
                                ->join("users", "clients.owner", "users.id")
                                ->whereIn('clients.id_route_import', $route_links)
                                ->whereBetween(DB::raw('STR_TO_DATE(clients.created_at, "%d %M %Y")'), [$startDate, $endDate]) // Use Y-m-d format for comparison
                                ->get();

        //
        $data_census_table          =   new stdClass();

        $data_census_table->rows    =   $clients;

        //
        return $data_census_table;
    }
}