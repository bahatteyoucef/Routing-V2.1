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

        foreach ($clients as $client) {

            // Client

            $client         =   new ClientTempo([
                'CustomerCode'              =>  $client->CustomerCode   ,
                'CustomerNameE'             =>  $client->CustomerNameE  ,
                'CustomerNameA'             =>  $client->CustomerNameA  ,
                'Latitude'                  =>  $client->Latitude       ,
                'Longitude'                 =>  $client->Longitude      ,
                'Address'                   =>  $client->Address        ,
                'DistrictNo'                =>  $client->DistrictNo     ,
                'DistrictNameE'             =>  $client->DistrictNameE  ,
                'CityNo'                    =>  $client->CityNo         ,
                'CityNameE'                 =>  $client->CityNameE      ,
                'Tel'                       =>  $client->Tel            ,
                'CustomerType'              =>  $client->CustomerType   ,
                'JPlan'                     =>  $client->JPlan          ,
                'Journee'                   =>  $client->Journee        ,
                'id_route_import_tempo'     =>  $id_route_import_tempo  ,
                'owner'                     =>  Auth::user()->id
            ]);

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
        $client->JPlan                      =   $request->get("JPlan");
        $client->Journee                    =   $request->get("Journee");

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
            $client->JPlan                      =   $client_tempo->JPlan;
            $client->Journee                    =   $client_tempo->Journee;
            $client->id_route_import_tempo      =   $client_tempo->id_route_import_tempo;
            $client->owner                      =   $client_tempo->owner;

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


        $getDoublant->getDoublantTel                    =   DB::table('clients_tempo')
                                                            ->where('id_route_import_tempo', $id_route_import_tempo)
                                                            ->whereIn('Tel', function ($query) {
                                                                $query->select('Tel')
                                                                    ->from('clients_tempo')
                                                                    ->groupBy('Tel')
                                                                    ->havingRaw('COUNT(*) > 1');
                                                            })
                                                            ->get();


        $getDoublant->getDoublantLatitudeLongitude      =   DB::table('clients_tempo')
                                                            ->where('id_route_import_tempo', $id_route_import_tempo)
                                                            ->whereIn(DB::raw('(Latitude, Longitude)'), function ($query) {
                                                                $query->select('Latitude', 'Longitude')
                                                                    ->from('clients_tempo')
                                                                    ->groupBy('Latitude', 'Longitude')
                                                                    ->havingRaw('COUNT(*) > 1');
                                                            })
                                                            ->get();

        $getDoublant->getDoublantCustomerNameE          =   DB::table('clients_tempo')
                                                            ->where('id_route_import_tempo', $id_route_import_tempo)
                                                            ->whereIn('CustomerNameE', function ($query) {
                                                                $query->select('CustomerNameE')
                                                                    ->from('clients_tempo')
                                                                    ->groupBy('CustomerNameE')
                                                                    ->havingRaw('COUNT(*) > 1');
                                                            })
                                                            ->get();

        $getDoublant->getDoublantCustomerCode           =   DB::table('clients_tempo')
                                                            ->where('id_route_import_tempo', $id_route_import_tempo)
                                                            ->whereIn('CustomerCode', function ($query) {
                                                                $query->select('CustomerCode')
                                                                    ->from('clients_tempo')
                                                                    ->groupBy('CustomerCode')
                                                                    ->havingRaw('COUNT(*) > 1');
                                                            })
                                                            ->get();

        /*

        $getDoublantTel                 =   "   SELECT *
                                                FROM clients_tempo
                                                WHERE clients_tempo.Tel IN (
                                                    SELECT clients_tempo.Tel
                                                    FROM clients_tempo
                                                    GROUP BY clients_tempo.Tel
                                                    HAVING COUNT(*) > 1
                                            )";

        $getDoublantLatitudeLongitude   =   "   SELECT *
                                                FROM clients_tempo
                                                WHERE (Latitude, Longitude) IN (
                                                    SELECT Latitude, Longitude
                                                    FROM clients_tempo
                                                    GROUP BY Latitude, Longitude
                                                    HAVING COUNT(*) > 1
                                            )";

        $getDoublantCustomerNameE       =   "   SELECT *
                                                FROM clients_tempo
                                                WHERE CustomerNameE IN (
                                                    SELECT CustomerNameE
                                                    FROM clients_tempo
                                                    GROUP BY CustomerNameE
                                                    HAVING COUNT(*) > 1
                                            )";

        $getDoublantCustomerCode        =   "   SELECT *
                                                FROM clients_tempo
                                                WHERE CustomerCode IN (
                                                    SELECT CustomerCode
                                                    FROM clients_tempo
                                                    GROUP BY CustomerCode
                                                    HAVING COUNT(*) > 1
                                            )";

        $getDoublant->getDoublantTel                    =   DB::select($getDoublantTel);

        $getDoublant->getDoublantLatitudeLongitude      =   DB::select($getDoublantLatitudeLongitude);

        $getDoublant->getDoublantCustomerNameE          =   DB::select($getDoublantCustomerNameE);

        $getDoublant->getDoublantCustomerCode           =   DB::select($getDoublantCustomerCode);

        */

        return $getDoublant;
    }
}
