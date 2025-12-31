<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class JourneeTerritory extends Model
{
    use HasFactory;

    protected $guarded      =   [];

    protected $table        =   'journee_territories';
    protected $primaryKey   =   'id';

    public    $timestamps   =   false;

    //  //  //  //  //
    //  //  //  //  //  Store/Update/Delete JourneeTerritory
    //  //  //  //  //

    public static function validateStore(Request $request) {

        $validator = Validator::make($request->all(), [
            'JourneeTerritory'       =>  ["required", "max:255"],
            'JPlan'         =>  ["required", "max:255"],
            'latlngs'       =>  ["required"],
            'color'         =>  ["required", "max:255"],
        ]);
    
        return $validator;
    }

    public static function storeJourneeTerritory(Request $request, int $id_route_import) {

        $journee_territory        =   new JourneeTerritory([
            'JourneeTerritory'           =>  $request->get("JourneeTerritory")    ,
            'JPlan'             =>  $request->get("JPlan")      ,
            'latlngs'           =>  $request->get("latlngs")    ,
            'color'             =>  $request->get("color")      ,
            
            'id_route_import'   =>  $id_route_import            ,
            'owner'             =>  Auth::user()->id
        ]);

        $journee_territory->save();
    }

    public static function validateUpdate(Request $request) {

        $validator = Validator::make($request->all(), [
            'JourneeTerritory'       =>  ["required", "max:255"],
            'JPlan'         =>  ["required", "max:255"],
            'latlngs'       =>  ["required"],
            'color'         =>  ["required", "max:255"],
        ]);
    
        return $validator;
    }

    public static function updateJourneeTerritory(Request $request, int $id_route_import, int $id_journee_territory) {

        $journee_territory        =   JourneeTerritory::find($id_journee_territory);

        if($journee_territory) {

            $journee_territory->JourneeTerritory           =   $request->get("JourneeTerritory");
            $journee_territory->JPlan             =   $request->get("JPlan");
            $journee_territory->latlngs           =   $request->get("latlngs");
            $journee_territory->color             =   $request->get("color");
            $journee_territory->id_route_import   =   $id_route_import;

            $journee_territory->save();
        }
    }

    public static function deleteJourneeTerritory(int $id_journee_territory) {

        $journee_territory        =   JourneeTerritory::find($id_journee_territory);

        if($journee_territory) {

            $journee_territory->delete();
        }

        else {

        }
    }
}