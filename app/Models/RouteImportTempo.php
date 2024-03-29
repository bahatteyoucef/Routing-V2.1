<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class RouteImportTempo extends Model
{

    //

    use HasFactory;

    protected $guarded      =   [];

    protected $table        =   'route_import_tempo';
    protected $primaryKey   =   'id';

    public    $timestamps   =   false;

    //

    public static function lastTempo()
    {

        $last_route_import              =   RouteImportTempo::where('owner', Auth::user()->id)->first();

        if($last_route_import) {

            $last_route_import->clients     =   ClientTempo::index($last_route_import->id);
        }

        return $last_route_import;
    }

    public static function validateStore(Request $request) 
    {

        $validator = Validator::make($request->all(), [
            'libelle'           =>  ["required", "max:255"      ],
            'data'              =>  ["required", "json"         ],
            'District'          =>  ["required"                 ],
            'file'              =>  ["required", "mimes:xlsx"   ]
        ]);

        return $validator;
    }

    public static function storeRouteImportTempo(Request $request) 
    {

        if(Auth::user()->hasRole("BackOffice")) {

            $liste_route_import    =   RouteImport::where("owner", Auth::user()->id)->get();

            if(count($liste_route_import)   >=  Auth::user()->max_route_import) {

                throw new Exception("You have achieved the maximum nombre of route imports !");
            }
        }

        RouteImportTempo::deleteRouteImportTempo();

        $route_import_tempo =   new RouteImportTempo([
            'libelle'       =>  $request->get("libelle")    ,
            'District'      =>  $request->get("District")   ,
            'owner'         =>  Auth::user()->id
        ]);

        $route_import_tempo->save();

        $fileName                   =   uniqid().'.'.$request->file->getClientOriginalExtension();
        $request->file->move(public_path('uploads/route_import_tempo/'.Auth::user()->id), $fileName);

        $route_import_tempo->file                   =   $fileName;
        $route_import_tempo->file_original_name     =   $request->file->getClientOriginalName();

        $route_import_tempo->save();

        // Store Data
        RouteImportTempo::storeData($request, $route_import_tempo->id);
    }

    public static function deleteRouteImportTempo() {

        RouteImportTempo::where('owner', Auth::user()->id)->delete();
        ClientTempo::where('owner', Auth::user()->id)->delete();

        File::deleteDirectory(public_path('uploads/route_import_tempo/'.Auth::user()->id));
    }

    //

    // Clients

    public static function storeData(Request $request, int $id)
    {

        //  Clients
        ClientTempo::storeClients($request, $id);
    }

    //
}
