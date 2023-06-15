<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use Illuminate\Validation\Rule;

class RouteImport extends Model
{
    use HasFactory;

    protected $guarded      =   [];

    protected $table        =   'route_import';
    protected $primaryKey   =   'id';

    public    $timestamps   =   false;

    //

    public static function validateStore(Request $request) 
    {

        $validator = Validator::make($request->all(), [
            'libelle'           =>  ["required", "max:255"      ],
            'data'              =>  ["required", "json"         ],
            'file'              =>  ["required", "mimes:xlsx"   ]
        ]);

        return $validator;
    }

    public static function storeRouteImport(Request $request) 
    {

        $route_import   =   new RouteImport([
            'libelle'       =>  $request->input('libelle')  ,
            'data'          =>  $request->get("data")       
        ]);

        $route_import->save();

        $fileName               =   uniqid().'.'.$request->file->getClientOriginalExtension();
        // $request->file->move(public_path('uploads/route_import/'.$route_import->id), $fileName);

        $route_import->file     =   $fileName;

        $route_import->save();

        return $route_import;
    }

    public static function validateUpdate(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'data'              =>  ["required", "json"]
        ]);

        return $validator;
    }

    public static function updateRouteImport(Request $request, int $id_route_import)
    {

        $route_import       =   RouteImport::find($id_route_import);

        $route_import->data =   $request->get("data");

        $route_import->save();
    }

    public static function deleteRouteImport(int $id_route_import)
    {

        $route_import       =   RouteImport::find($id_route_import);

        $route_import->delete();
    }

    //

    public static function indexRouteImport()
    {

        $liste_route_import =   RouteImport::all();

        return $liste_route_import;
    }

    //

    public static function obsDetailsRouteImport(string $id_route_import)
    {

        $route_import   =   RouteImport::find($id_route_import);

        return $route_import;  
    }
}
