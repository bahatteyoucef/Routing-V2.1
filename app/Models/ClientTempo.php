<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    //

    public static function deleteClients() {

        ClientTempo::where('owner', Auth::user()->id)->delete();
    }
}
