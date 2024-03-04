<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use stdClass;

class ClientTempo extends Model
{

    //

    use HasFactory;

    protected $guarded      =   [];

    protected $table        =   'clients_tempo';
    protected $primaryKey   =   'id';

    public    $timestamps   =   false;

    //

    public static function index(int $id_route_import_tempo) {

        return ClientTempo::where('id_route_import_tempo', $id_route_import_tempo)->get();
    }

    //

    public static function storeClients(Request $request, int $id_route_import_tempo) {

        //

        ClientTempo::deleteClients();

        //

        $clients    =   json_decode($request->get("data"));

        foreach ($clients as $client_elem) {

            // Client

            $client         =   new ClientTempo([
                'CustomerCode'              =>  $client_elem->CustomerCode   ,
                'CustomerNameE'             =>  $client_elem->CustomerNameE  ,
                'CustomerNameA'             =>  $client_elem->CustomerNameA  ,
                'Latitude'                  =>  $client_elem->Latitude       ,
                'Longitude'                 =>  $client_elem->Longitude      ,
                'Address'                   =>  $client_elem->Address        ,
                'DistrictNo'                =>  $client_elem->DistrictNo     ,
                'DistrictNameE'             =>  $client_elem->DistrictNameE  ,
                'CityNo'                    =>  $client_elem->CityNo         ,
                'CityNameE'                 =>  $client_elem->CityNameE      ,
                'Tel'                       =>  $client_elem->Tel            ,
                'CustomerType'              =>  $client_elem->CustomerType   ,
                'id_route_import_tempo'     =>  $id_route_import_tempo  ,
                'owner'                     =>  Auth::user()->id
            ]);

            if($client_elem->JPlan      !=  null) {

                $client->JPlan                  =   $client_elem->JPlan;
            }

            else {

                $client->JPlan                  =   "";
            }

            if($client_elem->Journee    !=  null) {

                $client->Journee                =   $client_elem->Journee;
            }

            else {

                $client->Journee                =   "";
            }


            $client->save();
        }
    }

    public static function updateClient(Request $request, int $id_route_import_tempo, int $id) {

        $client                             =   ClientTempo::find($id);

        $client->CustomerCode               =   $request->get("CustomerCode");
        $client->CustomerNameE              =   $request->get("CustomerNameE");
        $client->CustomerNameA              =   $request->get("CustomerNameA");
        $client->Latitude                   =   $request->get("Latitude");
        $client->Longitude                  =   $request->get("Longitude");
        $client->Address                    =   $request->get("Address");
        $client->DistrictNo                 =   $request->get("DistrictNo");
        $client->DistrictNameE              =   $request->get("DistrictNameE");
        $client->CityNo                     =   $request->get("CityNo");
        $client->CityNameE                  =   $request->get("CityNameE");
        $client->Tel                        =   $request->get("Tel");
        $client->CustomerType               =   $request->get("CustomerType");

        if($request->get("JPlan")   !=  null) {

            $client->JPlan              =   $request->get("JPlan");
        }

        else {

            $client->JPlan              =   "";
        }

        if($request->get("Journee") !=  null) {

            $client->Journee            =   $request->get("Journee");
        }

        else {

            $client->Journee            =   "";
        }

        $client->save();

    }

    public static function deleteClient(int $id_route_import_tempo, int $id) {

        $client                             =   ClientTempo::find($id);

        if($client) {

            $client->delete();
        }
    }

    //

    public static function updateClients(Request $request, int $id_route_import_tempo) {

        $clients    =   json_decode($request->get("data"));

        foreach ($clients as $client_tempo) {

            // Client

            $client                             =   ClientTempo::find($client_tempo->id);

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
            $client->id_route_import_tempo      =   $client_tempo->id_route_import_tempo;

            if($client_tempo->JPlan     !=  null) {

                $client->JPlan                  =   $client_tempo->JPlan;
            }

            else {

                $client->JPlan                  =   "";
            }

            if($client_tempo->Journee   !=  null) {

                $client->Journee                =   $client_tempo->Journee;
            }

            else {

                $client->Journee                =   "";
            }

            $client->save();
        }
    }

    //

    public static function updateResumeClients(Request $request) {

        $clients    =   json_decode($request->get("data"));

        foreach ($clients as $client_tempo) {

            // Client

            $client                             =   ClientTempo::find($client_tempo->id);

            if($client_tempo->JPlan     !=  null) {

                $client->JPlan                  =   $client_tempo->JPlan;
            }

            else {

                $client->JPlan                  =   "";
            }

            if($client_tempo->Journee   !=  null) {

                $client->Journee                =   $client_tempo->Journee;
            }

            else {

                $client->Journee                =   "";
            }

            $client->save();
        }
    }

    //

    public static function deleteClients() {

        ClientTempo::where('owner', Auth::user()->id)->delete();
    }

    //

    public static function getDoublesClients(int $id_route_import_tempo) {

        $getDoublant    =   new stdClass();

        $getDoublant->getDoublantCustomerCode           =   ClientTempo::getDoublesCustomerCodeClients($id_route_import_tempo);
        $getDoublant->getDoublantCustomerNameE          =   ClientTempo::getDoublesCustomerNameEClients($id_route_import_tempo);
        $getDoublant->getDoublantTel                    =   ClientTempo::getDoublesTelClients($id_route_import_tempo);
        $getDoublant->getDoublantLatitudeLongitude      =   ClientTempo::getDoublesGPSClients($id_route_import_tempo);

        return $getDoublant;
    }

    public static function getDoublesTelClients(int $id_route_import_tempo) {

        return  DB::table('clients_tempo')
                    ->where('id_route_import_tempo', $id_route_import_tempo)
                    ->whereIn('Tel', function ($query) use ($id_route_import_tempo) {
                        $query->select('Tel')
                            ->from('clients_tempo')
                            ->where('id_route_import_tempo', $id_route_import_tempo)
                            ->groupBy('Tel')
                            ->havingRaw('COUNT(*) > 1');
                    })
                    ->get();
    } 

    public static function getDoublesCustomerCodeClients(int $id_route_import_tempo) {

        return  DB::table('clients_tempo')
                    ->where('id_route_import_tempo', $id_route_import_tempo)
                    ->whereIn('CustomerCode', function ($query) use ($id_route_import_tempo) {
                        $query->select('CustomerCode')
                            ->from('clients_tempo')
                            ->where('id_route_import_tempo', $id_route_import_tempo)
                            ->groupBy('CustomerCode')
                            ->havingRaw('COUNT(*) > 1');
                    })
                    ->get();
    }

    public static function getDoublesCustomerNameEClients(int $id_route_import_tempo) {

        return  DB::table('clients_tempo')
                    ->where('id_route_import_tempo', $id_route_import_tempo)
                    ->whereIn('CustomerNameE', function ($query) use ($id_route_import_tempo) {
                        $query->select('CustomerNameE')
                            ->from('clients_tempo')
                            ->where('id_route_import_tempo', $id_route_import_tempo)
                            ->groupBy('CustomerNameE')
                            ->havingRaw('COUNT(*) > 1');
                    })
                    ->get();
    }

    public static function getDoublesGPSClients(int $id_route_import_tempo) {

        return  DB::table('clients_tempo')
                    ->where('id_route_import_tempo', $id_route_import_tempo)
                    ->whereIn(DB::raw('(Latitude, Longitude)'), function ($query) use ($id_route_import_tempo) {
                        $query->select('Latitude', 'Longitude')
                            ->from('clients_tempo')
                            ->where('id_route_import_tempo', $id_route_import_tempo)
                            ->groupBy('Latitude', 'Longitude')
                            ->havingRaw('COUNT(*) > 1');
                    })
                    ->get();
    }
}
