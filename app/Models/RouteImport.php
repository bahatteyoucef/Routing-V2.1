<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

    public static function indexRouteImport()
    {

        $liste_route_import =   RouteImport::where('owner', Auth::user()->id)->orderBy("id")->get();

        return $liste_route_import;
    }

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
            'owner'         =>  Auth::user()->id
        ]);

        $route_import->save();

        $fileName               =   uniqid().'.'.$request->file->getClientOriginalExtension();
        // $request->file->move(public_path('uploads/route_import/'.$route_import->id), $fileName);

        $route_import->file     =   $fileName;

        $route_import->save();

        // Store Data

        RouteImport::storeData($request, $route_import->id);

        return $route_import;
    }

    public static function validateUpdate(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'data'              =>  ["required", "json"]
        ]);

        return $validator;
    }

    public static function updateRouteImport(Request $request, int $id)
    {

        $route_import       =   RouteImport::find($id);

        $route_import->save();
    }

    public static function deleteRouteImport(int $id)
    {

        $route_import       =   RouteImport::find($id);

        $route_import->delete();

        // Delete Data
        RouteImport::deleteData($id);
    }

    //

    // Journey Plan

    public static function journeyPlan(int $id) 
    {

        $liste_journey_plan =   JourneyPlan::where([['id_route_import', $id],['owner', Auth::user()->id]])->orderBy("JPlan")->get();

        return $liste_journey_plan;
    }

    public static function journeyPlanUtil(Request $request, int $id) 
    {

        $liste_journey_plan_array =   json_decode($request->get("liste_journey_plan"));

        if($liste_journey_plan_array  ==  []) {

            $liste_journey_plan =   JourneyPlan::where([['id_route_import', $id],['owner', Auth::user()->id]])->whereNotNull('latlngs')->orderBy("JPlan")->get();
        }

        else {

            $liste_journey_plan =   JourneyPlan::where([['id_route_import', $id],['owner', Auth::user()->id]])->whereNotNull('latlngs')->whereIn('JPlan', $liste_journey_plan_array)->orderBy("JPlan")->get();
        }

        return $liste_journey_plan;
    }

    // Journee

    public static function journees(int $id) 
    {

        $journees   =   Journee::where([['id_route_import', $id],['owner', Auth::user()->id]])->orderBy("JPlan")->get();

        return $journees;
    }

    public static function journeesUtil(Request $request, int $id) 
    {

        $liste_journey_plan_array   =   json_decode($request->get("liste_journey_plan"));
        $journees_array             =   json_decode($request->get("journees"));

        if($liste_journey_plan_array    ==  []) {

            if($journees_array          ==  []) {

                $liste_journey_plan =   Journee::where([['id_route_import', $id],['owner', Auth::user()->id]])->whereNotNull('latlngs')->orderBy("JPlan")->get();
            }

            else {

                $liste_journey_plan =   Journee::where([['id_route_import', $id],['owner', Auth::user()->id]])->whereNotNull('latlngs')->whereIn('Journee', $journees_array)->orderBy("JPlan")->get();
            }
        }

        else {

            if($journees_array          ==  []) {

                $liste_journey_plan =   Journee::where([['id_route_import', $id],['owner', Auth::user()->id]])->whereNotNull('latlngs')->whereIn('JPlan', $liste_journey_plan_array)->orderBy("JPlan")->get();
            }

            else {

                $liste_journey_plan =   Journee::where([['id_route_import', $id],['owner', Auth::user()->id]])->whereNotNull('latlngs')->whereIn('JPlan', $liste_journey_plan_array)->whereIn('Journee', $journees_array)->orderBy("JPlan")->get();
            }
        }

        return $liste_journey_plan;
    }

    //

    // Clients

    public static function storeData(Request $request, int $id)
    {

        //  Clients

        Client::storeClients($request, $id);

        // 

        // Journey Plan

        // JourneyPlan::storeListeJourneyPlan($request, $id);

        //

        // Journee

        // Journee::storeJournees($request, $id);

        //
    }

    public static function deleteData(int $id)
    {

        Client::where("id_route_import", $id)->delete();
        JourneyPlan::where("id_route_import", $id)->delete();
        Journee::where("id_route_import", $id)->delete();
    }

    //

    // Validate City District in Clients

    public static function setWillayasCites(Request $request)
    {

        $clients    =   json_decode($request->get("clients"));

        foreach ($clients as $client) {

            // District
            $willaya   =   RTMWillaya::where('DistrictNameE', $client->DistrictNameE)->first();

            if($willaya) {

                $client->DistrictNo     =   $willaya->DistrictNo;

                // Cite
                $cite   =   RTMCite::where([['CityNameE', $client->CityNameE],['DistrictNo', $client->DistrictNo]])->first();

                if($cite) {

                    $client->CityNo         =   $cite->CITYNO;
                }

                else {

                    $client->CityNo         =   "UND";
                }
            }

            else {

                $client->DistrictNo     =   "UND";
                $client->CityNo         =   "UND";
            }
        }

        return $clients;
    }

    //

    // Routing

    public static function obsDetailsRouteImport(string $id)
    {

        $route_import           =   RouteImport::find($id);

        $route_import->data     =   Client::where([["id_route_import", $id],['owner', Auth::user()->id]])->get();   

        return $route_import;
    }

    //
}
