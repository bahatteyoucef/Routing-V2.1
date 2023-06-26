<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public static function storeJourneyPlan(Request $request, int $id_route_import) {

        $old_journey_plan   =   JourneyPlan::where([['id_route_import', $id_route_import], ['JPlan', $request->get("old_JPlan")]])->first();

        if($old_journey_plan) {

            $old_journey_plan->latlngs      =   null;
            $old_journey_plan->color        =   null;

            $old_journey_plan->save();
        }

        //

        $journey_plan   =   JourneyPlan::where([['id_route_import', $id_route_import], ['JPlan', $request->get("JPlan")]])->first();

        if($journey_plan) {

            $journey_plan->JPlan                =   $request->get("JPlan");
            $journey_plan->latlngs              =   $request->get("latlngs");
            $journey_plan->color                =   $request->get("color");
            $journey_plan->id_route_import      =   $id_route_import;

            $journey_plan->save();
        }

        else {

            $journey_plan       =   new JourneyPlan([
                'JPlan'             =>  $request->get("JPlan")      ,
                'latlngs'           =>  $request->get("latlngs")    ,
                'color'             =>  $request->get("color")      ,
                'id_route_import'   =>  $id_route_import            ,
                'owner'             =>  Auth::user()->id
            ]);

            $journey_plan->save();
        }
    }

    public static function deleteJourneyPlan(string $JPlan, int $id_route_import) {

        // Delete
        $journey_plan   =   JourneyPlan::where([['id_route_import', $id_route_import], ['JPlan', $JPlan]])->first();

        if($journey_plan) {

            $journey_plan->latlngs   =   null;
            $journey_plan->save();
        }

        else {

        }
    }

    //

}
