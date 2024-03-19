<?php

namespace App\Models;

use Illuminate\Http\Request;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RouteImportCity extends Model
{
    use HasFactory;

    protected $guarded      =   [];

    protected $table        =   'route_import_cities';
    protected $primaryKey   =   'id';

    public    $timestamps   =   false;

    //
    public static function setRouteImportCities(int $id_route_import, string $DistrictNo, Request $request) {

        $route_import_cities    =   json_decode($request->get("route_import_cities"));

        foreach ($route_import_cities as $route_import_city) {

            $route_import_city_elem     =   RouteImportCity::where([["CityNo", $route_import_city->CITYNO], ["DistrictNo", $DistrictNo], ["id_route_import", $id_route_import]])->first();

            if($route_import_city_elem) {

                $route_import_city_elem->expected_clients   =   $route_import_city->expected_clients;

                $route_import_city_elem->save();
            }

            else {

                $route_import_city_elem    =   new RouteImportCity([
                    'id_route_import'       =>  $id_route_import                        ,
                    'DistrictNo'            =>  $DistrictNo                             ,
                    'CityNo'                =>  $route_import_city->CITYNO              ,
                    'expected_clients'      =>  $route_import_city->expected_clients                  
                ]);

                //
                $route_import_city_elem->save();
            }
        }   
    }
}