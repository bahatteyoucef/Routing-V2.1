<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class JourneyPlanTerritory extends Model
{
    use HasFactory;

    protected $guarded      =   [];

    protected $table        =   'journey_plan_territories';
    protected $primaryKey   =   'id';

    public    $timestamps   =   false;

    //  //  //  //  //
    //  //  //  //  //  Store/Update/Delete JourneyPlanTerritory
    //  //  //  //  //

    public static function validateStore(Request $request) {

        $validator = Validator::make($request->all(), [
            'JPlan'         =>  ["required", "max:255"],
            'latlngs'       =>  ["required"],
            'color'         =>  ["required", "max:255"],
        ]);
    
        return $validator;
    }

    public static function storeJourneyPlanTerritory(Request $request, int $id_route_import) {

        $journey_plan_territory       =   new JourneyPlanTerritory([
            'JPlan'             =>  $request->get("JPlan")      ,
            'latlngs'           =>  $request->get("latlngs")    ,
            'color'             =>  $request->get("color")      ,
            'id_route_import'   =>  $id_route_import            ,
            'owner'             =>  Auth::user()->id
        ]);

        $journey_plan_territory->save();
    }

    public static function validateUpdate(Request $request) {

        $validator = Validator::make($request->all(), [
            'JPlan'         =>  ["required", "max:255"],
            'latlngs'       =>  ["required"],
            'color'         =>  ["required", "max:255"],
        ]);
    
        return $validator;
    }

    public static function updateJourneyPlanTerritory(Request $request, int $id_route_import, int $id_journey_plan_territory) {

        $journey_plan_territory   =   JourneyPlanTerritory::find($id_journey_plan_territory);

        if($journey_plan_territory) {

            $journey_plan_territory->JPlan                =   $request->get("JPlan");
            $journey_plan_territory->latlngs              =   $request->get("latlngs");
            $journey_plan_territory->color                =   $request->get("color");
            $journey_plan_territory->id_route_import      =   $id_route_import;

            $journey_plan_territory->save();
        }
    }

    public static function deleteJourneyPlanTerritory(int $id_journey_plan_territory) {

        // Delete
        $journey_plan_territory   =   JourneyPlanTerritory::find($id_journey_plan_territory);

        if($journey_plan_territory) {

            $journey_plan_territory->delete();
        }
    }
}