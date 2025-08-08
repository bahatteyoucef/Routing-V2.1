<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Http\Request;

class District extends Model {

    use HasFactory;

    protected $guarded      =   [];

    protected $table        =   'RTM_Willaya';
    protected $primaryKey   =   'DistrictNo';

    public    $timestamps   =   false;

    //

    public static function updateExpectedClients(int $DistrictNo, Request $request) {

        $district_cities    =   json_decode($request->get("district_cities"));

        //
        foreach ($district_cities as $city_element) {

            $city               =   City::find($city_element->CITYNO);

            //
            $city->expected_clients     =   $city_element->expected_clients;
            $city->save();
        }
    }
}