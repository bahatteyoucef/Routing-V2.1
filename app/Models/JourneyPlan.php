<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class JourneyPlan extends Model
{
    use HasFactory;

    protected $guarded      =   [];

    protected $table        =   'journey_plan';
    protected $primaryKey   =   'id';

    public    $timestamps   =   false;

    //

    public static function storeListeJourneyPlan(Request $request, int $id_route_import) {

        $liste_journey_plan     =   Client::where('id_route_import', $id_route_import)->distinct()->pluck('JPlan');

        foreach ($liste_journey_plan as $journey_plan) {

            $journey_plan   =   new JourneyPlan([
                'JPlan'             =>  $journey_plan       ,
                'id_route_import'   =>  $id_route_import    ,
                'owner'             =>  Auth::user()->id
            ]);

            $journey_plan->save();
        }
    }

    //

    public static function validateStore(Request $request) {

        $validator = Validator::make($request->all(), [
            'JPlan'         =>  ["required", "max:255"],
            'latlngs'       =>  ["required"],
            'color'         =>  ["required", "max:255"],
        ]);
    
        return $validator;
    }

    public static function storeJourneyPlan(Request $request, int $id_route_import) {

        $journey_plan       =   new JourneyPlan([
            'JPlan'             =>  $request->get("JPlan")      ,
            'latlngs'           =>  $request->get("latlngs")    ,
            'color'             =>  $request->get("color")      ,
            'id_route_import'   =>  $id_route_import            ,
            'owner'             =>  Auth::user()->id
        ]);

        $journey_plan->save();
    }

    //

    public static function validateUpdate(Request $request) {

        $validator = Validator::make($request->all(), [
            'JPlan'         =>  ["required", "max:255"],
            'latlngs'       =>  ["required"],
            'color'         =>  ["required", "max:255"],
        ]);
    
        return $validator;
    }

    public static function updateJourneyPlan(Request $request, int $id_route_import, int $id_journey_plan) {

        $journey_plan   =   JourneyPlan::find($id_journey_plan);

        if($journey_plan) {

            $journey_plan->JPlan                =   $request->get("JPlan");
            $journey_plan->latlngs              =   $request->get("latlngs");
            $journey_plan->color                =   $request->get("color");
            $journey_plan->id_route_import      =   $id_route_import;

            $journey_plan->save();
        }
    }

    //

    public static function deleteJourneyPlan(int $id_journey_plan) {

        // Delete
        $journey_plan   =   JourneyPlan::find($id_journey_plan);

        if($journey_plan) {

            $journey_plan->delete();
        }
    }
}