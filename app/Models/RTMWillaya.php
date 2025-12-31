<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class RTMWillaya extends Model
{
    use HasFactory; 

    protected $guarded      =   [];

    protected $table        =   'RTM_Willaya';
    protected $primaryKey   =   'DistrictNo';

    public    $timestamps   =   false;

    //  //  //  //  //
    //  //  //  //  //  Listings
    //  //  //  //  //

    public static function index() {

        return DB::table("RTM_Willaya")->orderBy('DistrictNameE')->get();
    }

    //  //  //  //  //
    //  //  //  //  //  Cities
    //  //  //  //  //

    public static function willayasCities() {

        $willayas   =   DB::table("RTM_Willaya")->orderBy('DistrictNameE')->get();

        foreach ($willayas as $willaya) {

            $willaya->cites =   DB::table("RTM_City")->where('DistrictNo', $willaya->DistrictNo)->orderBy('CityNameE')->get();
        }

        return $willayas;
    }

    public static function cities(int $DistrictNo) {

        $cities     =   DB::table("RTM_City")->where('DistrictNo', $DistrictNo)->orderBy('CityNameE')->get();
        return $cities;
    }

    //  //  //  //  //
    //  //  //  //  //  Set Expected Clients Number of each city
    //  //  //  //  //

    public static function updateExpectedClients(int $DistrictNo, Request $request) {

        $district_cities    =   json_decode($request->get("district_cities"));

        //
        foreach ($district_cities as $city_element) {

            $city               =   City::find($city_element->CityNo);

            //
            $city->expected_clients     =   $city_element->expected_clients;
            $city->save();
        }
    }
}
