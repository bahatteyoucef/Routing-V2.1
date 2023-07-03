<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class Client extends Model
{
    use HasFactory;

    protected $guarded      =   [];

    protected $table        =   'clients';
    protected $primaryKey   =   'id';

    public    $timestamps   =   false;

    //

    public static function storeClients(Request $request, int $id_route_import) {

        $clients    =   json_decode($request->get("data"));

        foreach ($clients as $client) {

            // Client

            $client         =   new Client([
                'CustomerCode'      =>  $client->CustomerCode   ,
                'CustomerNameE'     =>  $client->CustomerNameE  ,
                'CustomerNameA'     =>  $client->CustomerNameA  ,
                'Latitude'          =>  $client->Latitude       ,
                'Longitude'         =>  $client->Longitude      ,
                'Address'           =>  $client->Address        ,
                'DistrictNo'        =>  $client->DistrictNo     ,
                'DistrictNameE'     =>  $client->DistrictNameE  ,
                'CityNo'            =>  $client->CityNo         ,
                'CityNameE'         =>  $client->CityNameE      ,
                'Tel'               =>  $client->Tel            ,
                'CustomerType'      =>  $client->CustomerType   ,
                'JPlan'             =>  $client->JPlan          ,
                'Journee'           =>  $client->Journee        ,
                'id_route_import'   =>  $id_route_import        ,
                'owner'             =>  Auth::user()->id
            ]);

            $client->save();
        }
    }

    public static function changeRouteClients(Request $request, int $id_route_import) {

        $liste_clients_elem =   json_decode($request->get("clients"));

        foreach ($liste_clients_elem    as  $client_elem) {

            $client                 =   Client::find($client_elem->id);

            $client->DistrictNo     =   $client_elem->DistrictNo;
            $client->DistrictNameE  =   $client_elem->DistrictNameE;
            $client->CityNo         =   $client_elem->CityNo;
            $client->CityNameE      =   $client_elem->CityNameE;
            $client->JPlan          =   $client_elem->JPlan;
            $client->Journee        =   $client_elem->Journee;

            $client->save();

            // Journey Plan
            // JourneyPlan::storeJourneyPlan($request, $id_route_import);

            // Journee
            // Journee::storeJournee($request, $id_route_import);
        }
    }

    //

    public static function validateStore(Request $request) 
    {

        $validator = Validator::make($request->all(), [
            'CustomerCode'          =>  ["required", "max:255"],
            'CustomerNameE'         =>  ["required", "max:255"],
            'CustomerNameA'         =>  ["required", "max:255"],
            'Latitude'              =>  ["required", "max:255"],
            'Longitude'             =>  ["required", "max:255"],
            'Address'               =>  ["required", "max:255"],
            'DistrictNo'            =>  ["required", "max:255"],
            'DistrictNameE'         =>  ["required", "max:255"],
            'CityNo'                =>  ["required", "max:255"],
            'CityNameE'             =>  ["required", "max:255"],
            'Tel'                   =>  ["required", "max:255"],
            'CustomerType'          =>  ["required", "max:255"],
            // 'JPlan'                 =>  ["required", "max:255"],
            // 'Journee'               =>  ["required", "max:255"]
        ]);
    
        return $validator;
    }

    public static function storeClient(Request $request, int $id_route_import) {

        $client     =   new Client([
            'CustomerCode'      =>  $request->input("CustomerCode")     ,
            'CustomerNameE'     =>  $request->input("CustomerNameE")    ,
            'CustomerNameA'     =>  $request->input("CustomerNameA")    ,
            'Latitude'          =>  $request->input("Latitude")         ,
            'Longitude'         =>  $request->input("Longitude")        ,
            'Address'           =>  $request->input("Address")          ,
            'DistrictNo'        =>  $request->input("DistrictNo")       ,
            'DistrictNameE'     =>  $request->input("DistrictNameE")    ,
            'CityNo'            =>  $request->input("CityNo")           ,
            'CityNameE'         =>  $request->input("CityNameE")        ,
            'Tel'               =>  $request->input("Tel")              ,
            'CustomerType'      =>  $request->input("CustomerType")     ,
            'JPlan'             =>  $request->input("JPlan")            ,
            'Journee'           =>  $request->input("Journee")          ,
            'id_route_import'   =>  $id_route_import                    ,  
            'owner'             =>  Auth::user()->id
        ]);

        $client->save();

        // Journey Plan
        JourneyPlan::storeJourneyPlan($request, $id_route_import);

        // Journee
        Journee::storeJournee($request, $id_route_import);
    }

    //

    public static function validateUpdate(Request $request) 
    {

        $validator = Validator::make($request->all(), [
            'CustomerCode'          =>  ["required", "max:255"],
            'CustomerNameE'         =>  ["required", "max:255"],
            'CustomerNameA'         =>  ["required", "max:255"],
            'Latitude'              =>  ["required", "max:255"],
            'Longitude'             =>  ["required", "max:255"],
            'Address'               =>  ["required", "max:255"],
            'DistrictNo'            =>  ["required", "max:255"],
            'DistrictNameE'         =>  ["required", "max:255"],
            'CityNo'                =>  ["required", "max:255"],
            'CityNameE'             =>  ["required", "max:255"],
            'Tel'                   =>  ["required", "max:255"],
            'CustomerType'          =>  ["required", "max:255"],
            // 'JPlan'                 =>  ["required", "max:255"],
            // 'Journee'               =>  ["required", "max:255"]
        ]);
    
        return $validator;
    }

    public static function updateClient(Request $request, int $id_route_import, int $id) {

        $client                     =   Client::find($id);

        $client->CustomerCode       =   $request->get("CustomerCode")       ;
        $client->CustomerNameE      =   $request->get("CustomerNameE")      ;
        $client->CustomerNameA      =   $request->get("CustomerNameA")      ;
        $client->Latitude           =   $request->get("Latitude")           ;
        $client->Longitude          =   $request->get("Longitude")          ;
        $client->Address            =   $request->get("Address")            ;
        $client->DistrictNo         =   $request->get("DistrictNo")         ;
        $client->DistrictNameE      =   $request->get("DistrictNameE")      ;
        $client->CityNo             =   $request->get("CityNo")             ;
        $client->CityNameE          =   $request->get("CityNameE")          ;
        $client->Tel                =   $request->get("Tel")                ;
        $client->CustomerType       =   $request->get("CustomerType")       ;
        $client->JPlan              =   $request->get("JPlan")              ;
        $client->Journee            =   $request->get("Journee")            ;
        $client->id_route_import    =   $id_route_import                    ;

        $client->save();

        // Journey Plan
        JourneyPlan::storeJourneyPlan($request, $id_route_import);

        // Journee
        Journee::storeJournee($request, $id_route_import);
    }

    //

    public static function deleteClient(int $id_route_import, int $id) {

        $client     =   Client::find($id);

        // $Journee    =   $client->Journee; 
        // $JPlan      =   $client->JPlan;

        $client->delete();

        // Journey Plan
        // JourneyPlan::deleteJourneyPlan($JPlan, $id_route_import);

        // Journee
        // Journee::deleteJournee($JPlan, $Journee, $id_route_import);
    }

    //
}
