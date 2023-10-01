<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use stdClass;

class Client extends Model
{
    use HasFactory;

    protected $guarded      =   [];

    protected $table        =   'clients';
    protected $primaryKey   =   'id';

    public    $timestamps   =   false;

    //

    protected static function boot()
    {
        parent::boot();

        // Event hook to set the date before storing a new object in the database
        static::creating(function ($client) {
            $client->created_at = Carbon::now()->format('d F Y');;
        });
    }

    //

    public static function storeClients(Request $request, int $id_route_import) {

        $clients    =   json_decode($request->get("data"));

        foreach ($clients as $client_elem) {

            // Client

            $client         =   new Client([
                'CustomerCode'          =>  $client_elem->CustomerCode      ,
                'CustomerNameE'         =>  $client_elem->CustomerNameE     ,
                'CustomerNameA'         =>  $client_elem->CustomerNameA     ,
                'Latitude'              =>  $client_elem->Latitude          ,
                'Longitude'             =>  $client_elem->Longitude         ,
                'Address'               =>  $client_elem->Address           ,
                'DistrictNo'            =>  $client_elem->DistrictNo        ,
                'DistrictNameE'         =>  $client_elem->DistrictNameE     ,
                'CityNo'                =>  $client_elem->CityNo            ,
                'CityNameE'             =>  $client_elem->CityNameE         ,
                'Tel'                   =>  $client_elem->Tel               ,
                'CustomerType'          =>  $client_elem->CustomerType      ,
                'id_route_import'       =>  $id_route_import                ,
                'status'                =>  "pending"                       ,
                'nonvalidated_details'  =>  ""                              ,
                'created_at'            =>  1,
                'owner'                 =>  Auth::user()->id
            ]);

            if($client_elem->JPlan      !=  null) {
    
                $client->JPlan              =   $client_elem->JPlan;
            }

            else {

                $client->JPlan              =   "";
            }

            if($client_elem->Journee    !=  null) {

                $client->Journee            =   $client_elem->Journee;
            }

            else {

                $client->Journee            =   "";
            }


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
            // 'DistrictNo'            =>  ["required", "max:255"],
            // 'DistrictNameE'         =>  ["required", "max:255"],
            // 'CityNo'                =>  ["required", "max:255"],
            // 'CityNameE'             =>  ["required", "max:255"],
            'Tel'                   =>  ["required", "max:255"],
            'CustomerType'          =>  ["required", "max:255"],
        ]);
    
        return $validator;
    }

    public static function storeClient(Request $request, int $id_route_import) {

        $client     =   new Client([
            'CustomerCode'                  =>  $request->input("CustomerCode")                 ,
            'CustomerNameE'                 =>  $request->input("CustomerNameE")                ,
            'CustomerNameA'                 =>  $request->input("CustomerNameA")                ,
            'Latitude'                      =>  $request->input("Latitude")                     ,
            'Longitude'                     =>  $request->input("Longitude")                    ,
            'Address'                       =>  $request->input("Address")                      ,
            'DistrictNo'                    =>  $request->input("DistrictNo")                   ,
            'DistrictNameE'                 =>  $request->input("DistrictNameE")                ,
            'CityNo'                        =>  $request->input("CityNo")                       ,
            'CityNameE'                     =>  $request->input("CityNameE")                    ,
            'Tel'                           =>  $request->input("Tel")                          ,
            'CustomerType'                  =>  $request->input("CustomerType")                 ,
            'id_route_import'               =>  $id_route_import                                ,  

            'facade_image'                  =>  $request->input("facade_image")                 ,
            'in_store_image'                =>  $request->input("in_store_image")               ,
            'facade_image_original_name'    =>  $request->input("facade_image_original_name")   ,
            'in_store_image_original_name'  =>  $request->input("in_store_image_original_name") ,

            'status'                        =>  $request->input("status")                       ,
            'nonvalidated_details'          =>  $request->input("nonvalidated_details")         ,

            'owner'                         =>  Auth::user()->id
        ]);

        if($request->input("JPlan")     !=  null) {

            $client->JPlan          =   $request->input("JPlan");
        }

        if($request->input("Journee")   !=  null) {

            $client->Journee        =   $request->input("Journee");
        }

        $client->save();

        return $client;
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
            // 'DistrictNo'            =>  ["required", "max:255"],
            // 'DistrictNameE'         =>  ["required", "max:255"],
            // 'CityNo'                =>  ["required", "max:255"],
            // 'CityNameE'             =>  ["required", "max:255"],
            'Tel'                   =>  ["required", "max:255"],
            'CustomerType'          =>  ["required", "max:255"]
        ]);
    
        return $validator;
    }

    public static function updateClient(Request $request, int $id_route_import, int $id) {

        $client                     =   Client::find($id);

        if($client) {

            $client->CustomerCode                   =   $request->get("CustomerCode")       ;
            $client->CustomerNameE                  =   $request->get("CustomerNameE")      ;
            $client->CustomerNameA                  =   $request->get("CustomerNameA")      ;
            $client->Latitude                       =   $request->get("Latitude")           ;
            $client->Longitude                      =   $request->get("Longitude")          ;
            $client->Address                        =   $request->get("Address")            ;
            $client->DistrictNo                     =   $request->get("DistrictNo")         ;
            $client->DistrictNameE                  =   $request->get("DistrictNameE")      ;
            $client->CityNo                         =   $request->get("CityNo")             ;
            $client->CityNameE                      =   $request->get("CityNameE")          ;
            $client->Tel                            =   $request->get("Tel")                ;
            $client->CustomerType                   =   $request->get("CustomerType")       ;
            $client->id_route_import                =   $id_route_import                    ;

            $client->facade_image                   =   $request->get("facade_image")                   ;
            $client->in_store_image                 =   $request->get("in_store_image")                 ;
            $client->facade_image_original_name     =   $request->get("facade_image_original_name")     ;
            $client->in_store_image_original_name   =   $request->get("in_store_image_original_name")   ;

            $client->status                         =   $request->get("status")                         ;
            $client->nonvalidated_details           =   $request->get("nonvalidated_details")           ;

            $client->owner                          =   Auth::user()->id;

            if($request->input("JPlan")     !=  null) {

                $client->JPlan          =   $request->input("JPlan");
            }

            else {

                $client->JPlan          =   "";
            }

            //

            if($request->input("Journee")   !=  null) {

                $client->Journee        =   $request->input("Journee");
            }

            else {

                $client->Journee        =   "";
            }

            $client->save();
        }
    }

    //

    public static function validateClient(int $id_route_import, int $id) {

        $client                     =   Client::find($id);

        if($client) {

            $client->status         =   "validated";
            $client->save();
        }
    }

    //

    public static function updateClients(Request $request, int $id_route_import) {

        $clients    =   json_decode($request->get("data"));

        foreach ($clients as $client_tempo) {

            // Client

            $client                             =   Client::find($client_tempo->id);

            $client->CustomerCode               =   $client_tempo->CustomerCode;
            $client->CustomerNameE              =   $client_tempo->CustomerNameE;
            $client->CustomerNameA              =   $client_tempo->CustomerNameA;
            $client->Latitude                   =   $client_tempo->Latitude;
            $client->Longitude                  =   $client_tempo->Longitude;
            $client->Address                    =   $client_tempo->Address;
            $client->DistrictNo                 =   $client_tempo->DistrictNo;
            $client->DistrictNameE              =   $client_tempo->DistrictNameE;
            $client->CityNo                     =   $client_tempo->CityNo;
            $client->CityNameE                  =   $client_tempo->CityNameE;
            $client->Tel                        =   $client_tempo->Tel;
            $client->CustomerType               =   $client_tempo->CustomerType;
            $client->owner                      =   Auth::user()->id;

            if($client_tempo->JPlan     !=  null) {
    
                $client->JPlan                      =   $client_tempo->JPlan;
            }

            else {

                $client->JPlan                      =   "";
            }

            if($client_tempo->Journee   !=  null) {

                $client->Journee                    =   $client_tempo->Journee;
            }

            else {

                $client->Journee                    =   "";
            }

            $client->save();
        }
    }

    //

    public static function deleteClient(int $id_route_import, int $id) {

        $client     =   Client::find($id);

        $client->delete();
    }

    //

    public static function getDoublesClients(int $id_route_import) {

        $getDoublant    =   new stdClass();

        $getDoublant->getDoublantCustomerCode           =   Client::getDoublesCustomerCodeClients($id_route_import);
        $getDoublant->getDoublantCustomerNameE          =   Client::getDoublesCustomerNameEClients($id_route_import);
        $getDoublant->getDoublantTel                    =   Client::getDoublesTelClients($id_route_import);
        $getDoublant->getDoublantLatitudeLongitude      =   Client::getDoublesGPSClients($id_route_import);

        return $getDoublant;
    }

    public static function getDoublesTelClients(int $id_route_import) {

        return  DB::table('clients')
                    ->where('id_route_import', $id_route_import)
                    ->whereIn('Tel', function ($query) use ($id_route_import) {
                        $query->select('Tel')
                            ->from('clients')
                            ->where('id_route_import', $id_route_import)
                            ->groupBy('Tel')
                            ->havingRaw('COUNT(*) > 1');
                    })
                    ->get();
    } 

    public static function getDoublesCustomerCodeClients(int $id_route_import) {

        return  DB::table('clients')
                    ->where('id_route_import', $id_route_import)
                    ->whereIn('CustomerCode', function ($query) use ($id_route_import) {
                        $query->select('CustomerCode')
                            ->from('clients')
                            ->where('id_route_import', $id_route_import)
                            ->groupBy('CustomerCode')
                            ->havingRaw('COUNT(*) > 1');
                    })
                    ->get();
    }

    public static function getDoublesCustomerNameEClients(int $id_route_import) {

        return  DB::table('clients')
                    ->where('id_route_import', $id_route_import)
                    ->whereIn('CustomerNameE', function ($query) use ($id_route_import) {
                        $query->select('CustomerNameE')
                            ->from('clients')
                            ->where('id_route_import', $id_route_import)
                            ->groupBy('CustomerNameE')
                            ->havingRaw('COUNT(*) > 1');
                    })
                    ->get();
    }

    public static function getDoublesGPSClients(int $id_route_import) {

        return  DB::table('clients')
                    ->where('id_route_import', $id_route_import)
                    ->whereIn(DB::raw('(Latitude, Longitude)'), function ($query) use ($id_route_import) {
                        $query->select('Latitude', 'Longitude')
                            ->from('clients')
                            ->where('id_route_import', $id_route_import)
                            ->groupBy('Latitude', 'Longitude')
                            ->havingRaw('COUNT(*) > 1');
                    })
                    ->get();
    }

    //

}
