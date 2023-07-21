<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Fluent;
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

        $liste_route_import             =   RouteImport::orderBy("id", "desc")->get();

        foreach ($liste_route_import as $route_import) {

            $route_import->clients          =   Client::where("id_route_import", $route_import->id)->get();
        }

        return $liste_route_import;
    }

    public static function validateStore(Request $request) 
    {

        $validator = Validator::make($request->all(), [
            'libelle'                   =>  ["required", "max:255"                  ],
            'new_upload'                =>  ["required"                             ],
            'data'                      =>  ["required", "json"                     ]
        ]);

        //

        $validator->sometimes(['file']                                              , 'required|file:xlsx'      , function (Fluent $input) {
            return $input->new_upload   ==  "true";
        });

        $validator->sometimes(['id_route_import_tempo', 'file_route_import_tempo']  , 'required'                , function (Fluent $input) {
            return $input->new_upload   ==  "false";
        });

        return $validator;
    }

    public static function storeRouteImport(Request $request) 
    {

        $route_import   =   new RouteImport([
            'libelle'       =>  $request->input('libelle')  ,
            'owner'         =>  Auth::user()->id
        ]);

        $route_import->save();

        if($request->get("new_upload")  ==  "true") {

            $fileName               =   uniqid().'.'.$request->file->getClientOriginalExtension();
            $request->file->move(public_path('uploads/route_import/'.Auth::user()->id), $fileName);

            // 

            $route_import_filename  =   new RouteImportFile();

            $route_import_filename->id_route_import     =   $route_import->id;
            $route_import_filename->file                =   $fileName;

            $route_import_filename->save();
        }

        else {

            $fileName               =   $request->get("file_route_import_tempo");

            if(!File::exists(public_path().'/uploads')) {

                $path = public_path().'/uploads';
                File::makeDirectory($path, $mode = 0777, true, true);
            }

            if(!File::exists(public_path().'/uploads'.'/route_import')) {

                $path = public_path().'/uploads'.'/route_import';
                File::makeDirectory($path, $mode = 0777, true, true);
            }

            if(!File::exists(public_path().'/uploads'.'/route_import/'.Auth::user()->id)) {

                $path = public_path().'/uploads'.'/route_import/'.Auth::user()->id;
                File::makeDirectory($path, $mode = 0777, true, true);
            }

            //

            File::move(public_path('uploads/'.'route_import_tempo/'.Auth::user()->id.'/'.$fileName), public_path('uploads/'.'route_import/'.Auth::user()->id.'/'.$fileName));

            // 

            $route_import_filename                      =   new RouteImportFile();

            $route_import_filename->id_route_import     =   $route_import->id;
            $route_import_filename->file                =   $fileName;

            $route_import_filename->save();
        }

        $route_import->save();

        // Store Data
        RouteImport::storeData($request, $route_import->id);

        // Delete Tempo
        RouteImportTempo::deleteRouteImportTempo();

        // Store Relation with User
        $user_route_import  =   new UserRouteImport([

            'id_user'           =>  Auth::user()->id        ,
            'id_route_import'   =>  $route_import->id
        ]);

        $user_route_import->save();

        // 
        return $route_import;
    }

    public static function validateUpdate(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'data'              =>  ["required", "json"]    ,
            'file'              =>  ["required", "file:xlsx"]
        ]);

        return $validator;
    }

    public static function updateRouteImport(Request $request, int $id)
    {

        $fileName               =   uniqid().'.'.$request->file->getClientOriginalExtension();
        $request->file->move(public_path('uploads/route_import/'.Auth::user()->id), $fileName);

        // 

        $route_import_filename  =   new RouteImportFile();

        $route_import_filename->id_route_import     =   $id;
        $route_import_filename->file                =   $fileName;

        $route_import_filename->save();

        // 

        // Store Data
        RouteImport::storeData($request, $id);
    }

    public static function showRouteImport(int $id)
    {

        $route_import                       =   RouteImport::find($id);

        $route_import->clients              =   Client::where("id_route_import", $id)->get();
        $route_import->liste_journey_plan   =   JourneyPlan::where("id_route_import", $id)->get();
        $route_import->liste_journee        =   Journee::where("id_route_import", $id)->get();

        return $route_import;
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

        $liste_journey_plan =   JourneyPlan::where('id_route_import', $id)->orderBy("JPlan")->get();

        return $liste_journey_plan;
    }

    public static function journeyPlanUtil(Request $request, int $id) 
    {

        $liste_journey_plan_array =   json_decode($request->get("liste_journey_plan"));

        if($liste_journey_plan_array  ==  []) {

            $liste_journey_plan =   JourneyPlan::where('id_route_import', $id)->whereNotNull('latlngs')->orderBy("JPlan")->get();
        }

        else {

            $liste_journey_plan =   JourneyPlan::where('id_route_import', $id)->whereNotNull('latlngs')->whereIn('JPlan', $liste_journey_plan_array)->orderBy("JPlan")->get();
        }

        return $liste_journey_plan;
    }

    // Journee

    public static function journees(int $id) 
    {

        $journees   =   Journee::where('id_route_import', $id)->orderBy("JPlan")->get();

        return $journees;
    }

    public static function journeesUtil(Request $request, int $id) 
    {

        $liste_journey_plan_array   =   json_decode($request->get("liste_journey_plan"));
        $journees_array             =   json_decode($request->get("journees"));

        if($liste_journey_plan_array    ==  []) {

            if($journees_array          ==  []) {

                $liste_journey_plan =   Journee::where('id_route_import', $id)->whereNotNull('latlngs')->orderBy("JPlan")->get();
            }

            else {

                $liste_journey_plan =   Journee::where('id_route_import', $id)->whereNotNull('latlngs')->whereIn('Journee', $journees_array)->orderBy("JPlan")->get();
            }
        }

        else {

            if($journees_array          ==  []) {

                $liste_journey_plan =   Journee::where('id_route_import', $id)->whereNotNull('latlngs')->whereIn('JPlan', $liste_journey_plan_array)->orderBy("JPlan")->get();
            }

            else {

                $liste_journey_plan =   Journee::where('id_route_import', $id)->whereNotNull('latlngs')->whereIn('JPlan', $liste_journey_plan_array)->whereIn('Journee', $journees_array)->orderBy("JPlan")->get();
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
        RouteImportFile::where("id_route_import", $id)->delete();
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

        $route_import->data     =   Client::where("id_route_import", $id)->get();   

        return $route_import;
    }

    //

    // Clients

    public static function clients(int $id)
    {

        return Client::where("id_route_import", $id)->get();
    }


    //
}
