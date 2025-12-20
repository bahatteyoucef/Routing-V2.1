<?php

namespace App\Models;

use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

use stdClass;
use ZipArchive;

class RouteImport extends Model
{
    use HasFactory;

    protected $guarded      =   [];

    protected $table        =   'route_import';
    protected $primaryKey   =   'id';

    public    $timestamps   =   false;

    //

    public function scopePermittedByRole(Builder $query, String $type = "id") {

        $user       =   Auth::user();

        // Super Admin sees everything
        if ($user && $user->hasRole('Super Admin')) return $query;

        // Roles that should be limited to assigned route_imports (same logic as your original)
        $limitedRoles   =   ['BU Manager', 'BackOffice', 'Viewer', 'FrontOffice'];

        if ($user && $user->hasAnyRole($limitedRoles)) {

            // Get all id_route_import assigned to the user
            $allowedIds     =   UserRouteImport::where('id_user', $user->id)
                                    ->pluck('id_route_import')
                                    ->unique()
                                    ->toArray();

            //
            if (empty($allowedIds)) return $query->whereRaw('0 = 1'); // return empty results

            //
            return $query->whereIn('route_import' . '.' . $type, $allowedIds);
        }
    }

    //

    public static function indexRouteImport() {

        $liste_route_import             =   RouteImport::orderBy("id", "desc")->permittedByRole()->get();

        foreach ($liste_route_import as $route_import) {

            $route_import->clients      =   Client::where("id_route_import", $route_import->id)
                                                ->join('users', 'clients.owner', '=', 'users.id')
                                                ->select('clients.*', 'users.username as owner_username')
                                                ->get();

            //
            foreach ($route_import->clients as $client) {

                $AvailableBrands_AssocArray                 =   json_decode($client->AvailableBrands, true); // Convert JSON to associative array
                $client->AvailableBrands_array_formatted    =   array_values($AvailableBrands_AssocArray); // Extract values as an indexed array
                $client->AvailableBrands_string_formatted   =   implode(", ", $client->AvailableBrands_array_formatted);
            }
        }

        return $liste_route_import;
    }

    public static function headerRouteImports() {

        $liste_route_import             =   RouteImport::orderBy("id", "desc")->permittedByRole()->get();

        return $liste_route_import;
    }

    public static function statsRouteImports() {

        $liste_route_import             =   RouteImport::orderBy("id", "desc")->permittedByRole()->get();

        return $liste_route_import;
    }

    //

    public static function indexRouteImports() {

        $liste_route_import             =   RouteImport::orderBy("id", "desc")->permittedByRole()->get();

        return $liste_route_import;
    }

    //

    public static function comboRouteImport() {

        $liste_route_import             =   RouteImport::orderBy("id", "desc")->permittedByRole()->get();

        return $liste_route_import;
    }

    //

    public static function validateStore(Request $request) {

        $validator = Validator::make($request->all(), [
            'id_route_import_tempo'     =>  ["required", "max:255"                  ],
            'libelle'                   =>  ["required", "max:255"                  ],
            'districts'                 =>  ["required"                             ],
        ]);

        //

        return $validator;
    }

    public static function storeRouteImport(Request $request) {

        $route_import   =   new RouteImport([
            'libelle'       =>  $request->input('libelle')  ,
            'owner'         =>  Auth::user()->id
        ]);

        $route_import->save();

        //  //  //

        $route_import_tempo     =   RouteImportTempo::find($request->get("id_route_import_tempo"));

        $from                   =   public_path("uploads/route_import_tempo/" . Auth::user()->id . "/{$route_import_tempo->file}");
        $to                     =   public_path("uploads/route_import/"       . Auth::user()->id . "/{$route_import_tempo->file}");
        
        // Ensure destination directory exists
        if (! File::exists(dirname($to))) {
            File::makeDirectory(dirname($to), 0755, true);
        }
        
        // Move the file
        if (File::exists($from)) {
            File::move($from, $to);
        }

        // 

        $route_import_filename                      =   new RouteImportFile();

        $route_import_filename->id_route_import     =   $route_import->id;
        $route_import_filename->file                =   $route_import_tempo->file;

        $route_import_filename->save();

        //

        $route_import->save();

        //  //  //  //  //

        $districts      =   json_decode($request->get("districts"));   

        foreach ($districts as $district) {

            $route_import_district  =   new RouteImportDistrict([
                'DistrictNo'            =>  $district           ,
                'id_route_import'       =>  $route_import->id   ,
                'owner'                 =>  Auth::user()->id
            ]);

            $route_import_district->save();
        }

        //  //  //  //  //

        $clients    =   ClientTempo::where("id_route_import_tempo", $request->get("id_route_import_tempo"))->get();

        $request->merge([
            'clients'   =>  $clients
        ]);

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

    public static function validateUpdate(Request $request) {

        $validator = Validator::make($request->all(), [
            'file'              =>  ["required", "file:xlsx"]
        ]);

        return $validator;
    }

    public static function updateRouteImport(Request $request, int $id) {

        $fileName               =   uniqid().'.'.$request->file->getClientOriginalExtension();
        $request->file->move(public_path('uploads/route_import/'.Auth::user()->id), $fileName);

        // 

        $route_import_filename  =   new RouteImportFile();

        $route_import_filename->id_route_import     =   $id;
        $route_import_filename->file                =   $fileName;

        $route_import_filename->save();

        // 

        $request->merge([
            'clients'   =>  json_decode($request->get('data'))
        ]);

        // Store Data
        RouteImport::updateData($request, $id);
    }

    public static function showRouteImport(int $id) {

        $route_import                       =   RouteImport::find($id);

        $route_import->clients              =   Client::where("id_route_import", $id)->join('users', 'clients.owner', '=', 'users.id')->select('clients.*', 'users.username as owner_username')->get();

        //
        foreach ($route_import->clients as $client) {

            $AvailableBrands_AssocArray                 =   json_decode($client->AvailableBrands, true); // Convert JSON to associative array
            $client->AvailableBrands_array_formatted    =   array_values($AvailableBrands_AssocArray); // Extract values as an indexed array
            $client->AvailableBrands_string_formatted   =   implode(", ", $client->AvailableBrands_array_formatted);
        }

        $route_import->liste_journey_plan   =   JourneyPlan::where("id_route_import", $id)->get();
        $route_import->liste_journee        =   Journee::where("id_route_import", $id)->get();

        return $route_import;
    }

    public static function indexedDBShowRouteImport(int $id) {

        $route_import                       =   RouteImport::find($id);

        $route_import->clients              =   Client::where([["id_route_import", $id], ["clients.owner", Auth::user()->id]])->join('users', 'clients.owner', '=', 'users.id')->select('clients.*', 'users.username as owner_username')->get();

        //
        foreach ($route_import->clients as $client) {

            $AvailableBrands_AssocArray                 =   json_decode($client->AvailableBrands, true); // Convert JSON to associative array
            $client->AvailableBrands_array_formatted    =   array_values($AvailableBrands_AssocArray); // Extract values as an indexed array
            $client->AvailableBrands_string_formatted   =   implode(", ", $client->AvailableBrands_array_formatted);
        }

        return $route_import;
    }

    public static function deleteRouteImport(int $id) {

        $route_import       =   RouteImport::find($id);

        $route_import->delete();

        // Delete Data
        RouteImport::deleteData($id);
    }

    //

    // User Territory

    public static function userTerritory(int $id) {

        $liste_user_territory   =   UserTerritory::where('id_route_import', $id)->orderBy("id_user")->get();

        return $liste_user_territory;
    }

    public static function userTerritoryUtil(Request $request, int $id) {

        $liste_user_territory_array     =   json_decode($request->get("liste_user_territory"));

        if($liste_user_territory_array  ==  []) {

            $liste_user_territory           =   DB::table("user_territories")->select("user_territories.*"  ,   "users.username   as  user")
                                                ->join("users", "user_territories.id_user", "users.id")
                                                ->where('id_route_import', $id)
                                                ->whereNotNull('latlngs')
                                                ->orderBy("id_user")
                                                ->get();
        }

        else {

            $liste_user_territory           =   DB::table("user_territories")->select("user_territories.*"  ,   "users.username   as  user")
                                                ->join("users", "user_territories.id_user", "users.id")
                                                ->where('id_route_import', $id)
                                                ->whereNotNull('latlngs')
                                                ->whereIn('users.username', $liste_user_territory_array)
                                                ->orderBy("id_user")
                                                ->get();
        }

        return $liste_user_territory;
    }

    //

    // Journey Plan

    public static function journeyPlan(int $id) {

        $liste_journey_plan =   JourneyPlan::where('id_route_import', $id)->orderBy("JPlan")->get();

        return $liste_journey_plan;
    }

    public static function journeyPlanUtil(Request $request, int $id) {

        $liste_journey_plan_array =   json_decode($request->get("liste_journey_plan"));

        if($liste_journey_plan_array  ==  []) {

            $liste_journey_plan =   JourneyPlan::where('id_route_import', $id)->whereNotNull('latlngs')->orderBy("JPlan")->get();
        }

        else {

            $liste_journey_plan =   JourneyPlan::where('id_route_import', $id)->whereNotNull('latlngs')->whereIn('JPlan', $liste_journey_plan_array)->orderBy("JPlan")->get();
        }

        return $liste_journey_plan;
    }

    //

    // Journee

    public static function journees(int $id) {

        $journees   =   Journee::where('id_route_import', $id)->orderBy("JPlan")->get();

        return $journees;
    }

    public static function journeesUtil(Request $request, int $id) {

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

    public static function storeData(Request $request, int $id) {

        //  Clients
        Client::storeClients($request, $id);
    }

    public static function updateData(Request $request, int $id) {

        //  Clients
        Client::storeClientsUpdateRouteImport($request, $id);
    }

    public static function deleteData(int $id) {

        // Delete Route Import Files and Excel Files

        $route_import_files  =   RouteImportFile::where("id_route_import", $id)->get(); 

        foreach ($route_import_files as $route_import_file) {

            $fileName       =   $route_import_file->file; // Replace with the actual filename
            RouteImportFile::deleteRouteImportFile($fileName);

            $route_import_file->delete();
        }

        //

        // Delete Client and Their Images

        $clients    =   Client::where("id_route_import", $id)->get();

        foreach ($clients as $client) {

            // $filePath   =   public_path('uploads/clients/'.$client->id);  

            // if (File::exists($filePath)) {

            //     File::deleteDirectory($filePath);
            // }

            $client->delete();
        }

        //

        RouteImportDistrict::where("id_route_import", $id)->get();
        JourneyPlan::where("id_route_import", $id)->delete();
        Journee::where("id_route_import", $id)->delete();
    }

    //

    // Validate City District in Clients

    public static function setWillayasCites(Request $request) {

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

    public static function obsDetailsRouteImport(string $id) {

        $route_import           =   RouteImport::find($id);

        $route_import->clients     =   Client::where("id_route_import", $id)->join('users', 'clients.owner', '=', 'users.id')->select('clients.*', 'users.username as owner_username')->get();   

        //
        foreach ($route_import->clients as $client) {

            $AvailableBrands_AssocArray                 =   json_decode($client->AvailableBrands, true); // Convert JSON to associative array
            $client->AvailableBrands_array_formatted    =   array_values($AvailableBrands_AssocArray); // Extract values as an indexed array
            $client->AvailableBrands_string_formatted   =   implode(", ", $client->AvailableBrands_array_formatted);
        }

        return $route_import;
    }

    public static function obsDetailsRouteImportFrontOffice(string $id) {

        $route_import           =   RouteImport::find($id);

        $route_import->data     =   Client::query()
                                        ->where('clients.id_route_import', $id)
                                        ->where(function($q) {
                                            $q->where('clients.owner', Auth::id())
                                            ->orWhere(function($q2) {
                                                $q2->where('clients.status', 'visible')
                                                    ->whereIn('clients.CityNo', function($sub) {
                                                        $sub->select('CITYNO')
                                                            ->from('users_cities')
                                                            ->where('id_user', Auth::id());
                                                    });
                                            });
                                        })
                                        ->join('users', 'clients.owner', '=', 'users.id')
                                        ->select('clients.*', 'users.username as owner_username')
                                        ->get();

        //
        foreach ($route_import->data as $client) {

            $AvailableBrands_AssocArray                 =   json_decode($client->AvailableBrands, true); // Convert JSON to associative array
            $client->AvailableBrands_array_formatted    =   array_values($AvailableBrands_AssocArray); // Extract values as an indexed array
            $client->AvailableBrands_string_formatted   =   implode(", ", $client->AvailableBrands_array_formatted);
        }

        return $route_import;
    }

    //

    // Clients

    public static function clients(int $id) {

        // Use the current request instance when $request wasn't provided
        $request = $request ?? request();

        //

        $start      =   $request->filled('start_date') ? Carbon::parse($request->start_date)->format('Y-m-d')   : null;
        $end        =   $request->filled('end_date')   ? Carbon::parse($request->end_date)->format('Y-m-d')     : null;

        //

        $clients    =   Client::where("id_route_import", $id)
                            ->when(
                                $request->filled('status'),
                                function ($q) use ($request) {
                                    $q->where("clients.status", $request->get("status"));
                                }
                            )

                            ->when($start || $end, function ($q) use ($start, $end) {
                                if ($start && $end) $q->whereRaw("STR_TO_DATE(clients.created_at, '%d %M %Y') BETWEEN ? AND ?"  , [$start, $end]);
                                elseif ($start)     $q->whereRaw("STR_TO_DATE(clients.created_at, '%d %M %Y') >= ?"             , [$start]);
                                else                $q->whereRaw("STR_TO_DATE(clients.created_at, '%d %M %Y') <= ?"             , [$end]);
                            })

                            ->when($request->has('selected_CustomerTypes'), function ($q) use ($request) {
                                $CustomerTypes  =   json_decode($request->get('selected_CustomerTypes'), true);
                                return $q->whereIn('clients.CustomerType', $CustomerTypes);
                            })

                            ->when($request->has('selected_NbrVitrines'), function ($q) use ($request) {
                                $NbrVitrines  =   json_decode($request->get('selected_NbrVitrines'), true);
                                return $q->whereIn('clients.NbrRideauxFacade', $NbrVitrines);
                            })

                            ->when($request->has('selected_SuperficieMagasins'), function ($q) use ($request) {
                                $SuperficieMagasins  =   json_decode($request->get('selected_SuperficieMagasins'), true);
                                return $q->whereIn('clients.SuperficieMagasin', $SuperficieMagasins);
                            })

                            ->join('users', 'clients.owner', 'users.id')
                            ->select('clients.*', 'users.username as owner_name')
                            ->get();

        //
        return $clients;
    }

    //

    public static function sync(Request $request) {

        //

        $updated_clients    =   json_decode($request->get("updated_clients"));

        foreach ($updated_clients as $client_elem) {

            $client             =   Client::find($client_elem->id);

            if($client) {

                $client->NewCustomer            =   $client_elem->NewCustomer;
                $client->CustomerIdentifier     =   $client_elem->CustomerIdentifier;
                $client->CustomerCode           =   $client_elem->CustomerCode;
                $client->OpenCustomer           =   $client_elem->OpenCustomer;
                $client->CustomerNameE          =   $client_elem->CustomerNameE;
                $client->CustomerNameA          =   $client_elem->CustomerNameA;
                $client->Latitude               =   $client_elem->Latitude;
                $client->Longitude              =   $client_elem->Longitude;
                $client->Address                =   $client_elem->Address;
                $client->CityNo                 =   $client_elem->CityNo;
                $client->DistrictNo             =   $client_elem->DistrictNo;
                $client->CityNameE              =   $client_elem->CityNameE;
                $client->DistrictNameE          =   $client_elem->DistrictNameE;
                $client->Tel                    =   $client_elem->Tel;
                $client->CustomerType           =   $client_elem->CustomerType;

                $client->Neighborhood           =   $client_elem->Neighborhood;
                $client->Landmark               =   $client_elem->Landmark;
                $client->BrandAvailability      =   $client_elem->BrandAvailability;
                $client->BrandSourcePurchase    =   $client_elem->BrandSourcePurchase;

                $client->Frequency              =   $client_elem->Frequency;
                $client->SuperficieMagasin      =   $client_elem->SuperficieMagasin;
                $client->NbrAutomaticCheckouts  =   $client_elem->NbrAutomaticCheckouts;
                $client->AvailableBrands        =   $client_elem->AvailableBrands;

                $client->id_route_import        =   $client_elem->id_route_import;
                $client->owner                  =   Auth::user()->id;

                //

                if($client_elem->CustomerBarCode_image_original_name    !=  null) {

                    $client->CustomerBarCode_image_original_name    =   $client_elem->CustomerBarCode_image_original_name;
                }

                else {

                    $client->CustomerBarCode_image_original_name    =   "";
                }

                //

                if($client_elem->facade_image_original_name    !=  null) {

                    $client->facade_image_original_name             =   $client_elem->facade_image_original_name;
                }

                else {

                    $client->facade_image_original_name             =   "";
                }

                //

                if($client_elem->in_store_image_original_name    !=  null) {

                    $client->in_store_image_original_name           =   $client_elem->in_store_image_original_name;
                }

                else {

                    $client->in_store_image_original_name           =   "";
                }

                //

                if($client_elem->CustomerBarCode_image_updated       ==  "true") {

                    if($client_elem->CustomerBarCode_image) {

                        $CustomerBarCode_image              =   $client_elem->CustomerBarCode_image;  // your base64 encoded
                        $CustomerBarCode_image              =   str_replace('data:image/png;base64,', '', $CustomerBarCode_image);
                        $CustomerBarCode_image              =   str_replace(' ', '+', $CustomerBarCode_image);
                        $fileName                           =   uniqid().'.'.'png';

                        $target_path                        =   public_path('uploads/clients/' . $client->id . '/' . $fileName);

                        // Create the directory if it doesn't exist
                        if (!File::isDirectory(dirname($target_path))) {

                            File::makeDirectory(dirname($target_path), 0775, true); // Ensure write permissions
                        }

                        file_put_contents($target_path, base64_decode($CustomerBarCode_image));

                        $client->CustomerBarCode_image       =   $fileName; 
                    } 

                    else {

                        $client->CustomerBarCode_image       =   "";
                    }
                }

                //

                if($client_elem->facade_image_updated       ==  "true") {

                    if($client_elem->facade_image) {

                        $facade_image               =   $client_elem->facade_image;  // your base64 encoded
                        $facade_image               =   str_replace('data:image/png;base64,', '', $facade_image);
                        $facade_image               =   str_replace(' ', '+', $facade_image);
                        $fileName                   =   uniqid().'.'.'png';

                        $target_path                =   public_path('uploads/clients/' . $client->id . '/' . $fileName);

                        // Create the directory if it doesn't exist
                        if (!File::isDirectory(dirname($target_path))) {

                            File::makeDirectory(dirname($target_path), 0775, true); // Ensure write permissions
                        }

                        file_put_contents($target_path, base64_decode($facade_image));

                        $client->facade_image       =   $fileName; 
                    } 

                    else {

                        $client->facade_image       =   "";
                    }
                }

                //

                if($client_elem->in_store_image_updated     ==  "true") {

                    if($client_elem->in_store_image) {

                        $in_store_image             =   $client_elem->in_store_image;  // your base64 encoded
                        $in_store_image             =   str_replace('data:image/png;base64,', '', $in_store_image);
                        $in_store_image             =   str_replace(' ', '+', $in_store_image);
                        $fileName                   =   uniqid().'.'.'png';

                        $target_path                =   public_path('uploads/clients/' . $client->id . '/' . $fileName);

                        // Create the directory if it doesn't exist
                        if (!File::isDirectory(dirname($target_path))) {

                            File::makeDirectory(dirname($target_path), 0775, true); // Ensure write permissions
                        }

                        file_put_contents($target_path, base64_decode($in_store_image));

                        $client->in_store_image     =   $fileName;
                    }

                    else {

                        $client->in_store_image     =   "";
                    }
                }

                //

                if($client_elem->nonvalidated_details    !=  null) {

                    $client->nonvalidated_details           =   $client_elem->nonvalidated_details;
                }

                else {

                    $client->nonvalidated_details           =   "";
                }

                //

                if($client_elem->JPlan      !=  null) {

                    $client->JPlan          =   $client_elem->JPlan;
                }

                else {

                    $client->JPlan          =   "";
                }

                //

                if($client_elem->Journee    !=  null) {

                    $client->Journee        =   $client_elem->Journee;
                }

                else {

                    $client->Journee        =   "";
                }

                $client->save();
            }
        }

        // 

        $added_clients      =   json_decode($request->get("added_clients"));

        foreach ($added_clients as $client_elem) {

            //

            $client         =   new Client([
                'NewCustomer'           =>  $client_elem->NewCustomer           ,
                'CustomerIdentifier'    =>  $client_elem->CustomerIdentifier    ,
                'CustomerCode'          =>  $client_elem->CustomerCode          ,    
                'OpenCustomer'          =>  $client_elem->OpenCustomer          ,    
                'CustomerNameE'         =>  $client_elem->CustomerNameE         ,
                'CustomerNameA'         =>  $client_elem->CustomerNameA         ,
                'Latitude'              =>  $client_elem->Latitude              ,
                'Longitude'             =>  $client_elem->Longitude             ,
                'Address'               =>  $client_elem->Address               ,
                'CityNo'                =>  $client_elem->CityNo                ,
                'DistrictNo'            =>  $client_elem->DistrictNo            ,
                'CityNameE'             =>  $client_elem->CityNameE             ,
                'DistrictNameE'         =>  $client_elem->DistrictNameE         ,
                'Tel'                   =>  $client_elem->Tel                   ,
                'CustomerType'          =>  $client_elem->CustomerType          ,

                'Neighborhood'          =>  $client_elem->Neighborhood          ,
                'Landmark'              =>  $client_elem->Landmark              ,
                'BrandAvailability'     =>  $client_elem->BrandAvailability     ,
                'BrandSourcePurchase'   =>  $client_elem->BrandSourcePurchase   ,

                'Frequency'             =>  $client_elem->Frequency             ,
                'SuperficieMagasin'     =>  $client_elem->SuperficieMagasin     ,
                'NbrAutomaticCheckouts' =>  $client_elem->NbrAutomaticCheckouts ,
                'AvailableBrands'       =>  $client_elem->AvailableBrands       ,

                'id_route_import'       =>  $client_elem->id_route_import       ,  
                'status'                =>  $client_elem->status                ,
                'owner'                 =>  Auth::user()->id                    ,
            ]);

            $client->save();

            //

            if($client_elem->CustomerBarCode_image_original_name    !=  null) {

                $client->CustomerBarCode_image_original_name    =   $client_elem->CustomerBarCode_image_original_name;
            }

            else {

                $client->CustomerBarCode_image_original_name    =   "";
            }

            //

            if($client_elem->facade_image_original_name    !=  null) {

                $client->facade_image_original_name           =   $client_elem->facade_image_original_name;
            }

            else {

                $client->facade_image_original_name           =   "";
            }

            //

            if($client_elem->in_store_image_original_name    !=  null) {

                $client->in_store_image_original_name           =   $client_elem->in_store_image_original_name;
            }

            else {

                $client->in_store_image_original_name           =   "";
            }

            //

            if($client_elem->CustomerBarCode_image) {

                $CustomerBarCode_image      =   $client_elem->CustomerBarCode_image;  // your base64 encoded
                $CustomerBarCode_image      =   str_replace('data:image/png;base64,', '', $CustomerBarCode_image);
                $CustomerBarCode_image      =   str_replace(' ', '+', $CustomerBarCode_image);
                $fileName                   =   uniqid().'.'.'png';

                $target_path                =   public_path('uploads/clients/' . $client->id . '/' . $fileName);

                // Create the directory if it doesn't exist
                if (!File::isDirectory(dirname($target_path))) {

                    File::makeDirectory(dirname($target_path), 0775, true); // Ensure write permissions
                }

                file_put_contents($target_path, base64_decode($CustomerBarCode_image));

                $client->CustomerBarCode_image       =   $fileName; 
            } 

            else {

                $client->CustomerBarCode_image       =   "";
            }

            //

            if($client_elem->facade_image) {

                $facade_image               =   $client_elem->facade_image;  // your base64 encoded
                $facade_image               =   str_replace('data:image/png;base64,', '', $facade_image);
                $facade_image               =   str_replace(' ', '+', $facade_image);
                $fileName                   =   uniqid().'.'.'png';

                $target_path                =   public_path('uploads/clients/' . $client->id . '/' . $fileName);

                // Create the directory if it doesn't exist
                if (!File::isDirectory(dirname($target_path))) {

                    File::makeDirectory(dirname($target_path), 0775, true); // Ensure write permissions
                }

                file_put_contents($target_path, base64_decode($facade_image));

                $client->facade_image       =   $fileName; 
            } 

            else {

                $client->facade_image       =   "";
            }

            //

            if($client_elem->in_store_image) {

                $in_store_image             =   $client_elem->in_store_image;  // your base64 encoded
                $in_store_image             =   str_replace('data:image/png;base64,', '', $in_store_image);
                $in_store_image             =   str_replace(' ', '+', $in_store_image);
                $fileName                   =   uniqid().'.'.'png';

                $target_path                =   public_path('uploads/clients/' . $client->id . '/' . $fileName);

                // Create the directory if it doesn't exist
                if (!File::isDirectory(dirname($target_path))) {

                    File::makeDirectory(dirname($target_path), 0775, true); // Ensure write permissions
                }

                file_put_contents($target_path, base64_decode($in_store_image));

                $client->in_store_image     =   $fileName; 
            }

            else {

                $client->in_store_image     =   "";
            }

            //

            if($client_elem->nonvalidated_details    !=  null) {

                $client->nonvalidated_details           =   $client_elem->nonvalidated_details;
            }

            else {

                $client->nonvalidated_details           =   "";
            }

            //

            if($client_elem->JPlan      !=  null) {

                $client->JPlan          =   $client_elem->JPlan;
            }

            else {

                $client->JPlan          =   "";
            }

            if($client_elem->Journee    !=  null) {

                $client->Journee        =   $client_elem->Journee;
            }

            else {

                $client->Journee        =   "";
            }

            //

            $client->save();
        }

        //

        $deleted_clients    =   json_decode($request->get("deleted_clients"));

        foreach ($deleted_clients as $client_elem) {

            $client         =   Client::find($client_elem->id);

            if($client) {

                $client->delete();
            }
        }

        //
    }

    //

    public static function frontOffice(int $id_route_import) {

        $users      =   DB::table('users')

                        ->select([ 
                            'users.id                               as  id'                 , 

                            'users.username                         as  username'           ,
                            'users.first_name                       as  first_name'         ,
                            'users.last_name                        as  last_name'          ,
                            'users.email                            as  email'              ,

                            'users.tel                              as  tel'                ,
                            'users.company                          as  company'            ,

                            'users.type_user                        as  type_user'          ,

                            'users.accuracy                         as  accuracy'           ,

                            'users.max_route_import                 as  max_route_import'   ,

                            //

                            'users_route_import.id_route_import     as  id_route_import'
                        ])

                        ->join("users_route_import",    "users.id",     "users_route_import.id_user")

                        ->where([["users_route_import.id_route_import", $id_route_import],
                                ["users.type_user", "FrontOffice"]])

                        ->get();

        return $users;
    }

    public static function users(int $id_route_import) {

        $query      =   DB::table('users')

                            ->select([ 
                                'users.id                               as  id'                 , 

                                'users.username                         as  username'           ,
                                'users.first_name                       as  first_name'         ,
                                'users.last_name                        as  last_name'          ,
                                'users.email                            as  email'              ,

                                'users.tel                              as  tel'                ,
                                'users.company                          as  company'            ,

                                'users.type_user                        as  type_user'          ,

                                'users.accuracy                         as  accuracy'           ,

                                'users.max_route_import                 as  max_route_import'   ,

                                //

                                'users_route_import.id_route_import     as  id_route_import'
                            ])

                            ->join("users_route_import",    "users.id",     "users_route_import.id_user")

                            ->where("users_route_import.id_route_import", $id_route_import);
        //        
        if((Auth::user()->hasRole("BackOffice"))||(Auth::user()->hasRole("BU Manager"))) {

            $query  =   $query->where(function ($sub_query) { // This creates parentheses in SQL
                            $sub_query->where("users.type_user", "FrontOffice")
                                    ->orWhere("users.type_user", "BackOffice")
                                    ->orWhere("users.type_user", "BU Manager");
                        });
        }

        //
        return $query->get();
    }

    //

    public static function clientsByStatus(Request $request, int $id_route_import) {

        $clients        =   Client::query()
                                ->where('clients.id_route_import', $id_route_import)
                                ->where('clients.status', $request->get('status'))
                                ->when(
                                    $request->get('status') !== 'visible',
                                    fn($q) => $q->where('clients.owner', Auth::id()),
                                    fn($q) => $q->whereIn('clients.CityNo', function($sub) {
                                        $sub->select('CITYNO')
                                            ->from('users_cities')
                                            ->where('id_user', Auth::id());
                                    })
                                )
                                ->join('users', 'clients.owner', '=', 'users.id')
                                ->select('clients.*', 'users.username as owner_username')
                                ->get();

        //
        foreach ($clients as $client) {

            $AvailableBrands_AssocArray                 =   json_decode($client->AvailableBrands, true); // Convert JSON to associative array
            $client->AvailableBrands_array_formatted    =   array_values($AvailableBrands_AssocArray); // Extract values as an indexed array
            $client->AvailableBrands_string_formatted   =   implode(", ", $client->AvailableBrands_array_formatted);
        }

        return $clients;
    }

    //

    public static function routeImportCities(int $id_route_import, string $DistrictNo) {

        $route_import_cities    =   DB::table("RTM_City")
                                        ->select("RTM_City.*", "route_import_cities.expected_clients", "route_import_cities.id_route_import")

                                        ->leftJoin('route_import_cities', function($join) use($id_route_import) {
                                            $join->on('RTM_City.CityNo', '=', 'route_import_cities.CityNo')
                                                ->where("route_import_cities.id_route_import", $id_route_import);
                                        })

                                        ->where("RTM_City.DistrictNo", $DistrictNo)
                                        ->get();

        return $route_import_cities;
    }

    //  //  //  //  //  //  //  //  //

    //  //  //  //  //  //  //  //  //

    //  //  //  //  //  //  //  //  //

    public static function downloadData(Request $request) {

        //
        $clients         =   Client::clientsExport($request);

        //
        return $clients;
    }

    //

    public static function downloadImages(Request $request) {

        //
        $route_import               =   RouteImport::find($request->get("id_route_import"));

        //
        $images                     =   [];

        $zip                        =   new \ZipArchive;
        $zipFile                    =   $route_import->libelle.' Images.zip';

        //
        $images                     =   RouteImport::prepareImagesExport($request);
        $customer_bar_code_images   =   $images->customer_bar_code_images;
        $in_store_images            =   $images->in_store_images;
        $facade_images              =   $images->facade_images;

        //
        if ($zip->open(public_path($zipFile), ZipArchive::CREATE) === TRUE) {

            // CustomerBarCode Images
            foreach ($customer_bar_code_images as $image) {

                $pathToFile     =   public_path($image);

                $name           =   basename($pathToFile);
                $folderName     =   'CustomerBarCode Images'; // Specify the folder name here

                // Adjust the path to include the folder name
                $pathInZip      =   $folderName . '/' . $name;

                // Add the file to the zip archive with the adjusted path
                $zip->addFile($pathToFile, $pathInZip);
            }

            // In Store Images
            foreach ($in_store_images as $image) {

                $pathToFile     =   public_path($image);

                $name           =   basename($pathToFile);
                $folderName     =   'In Store Images'; // Specify the folder name here

                // Adjust the path to include the folder name
                $pathInZip      =   $folderName . '/' . $name;

                // Add the file to the zip archive with the adjusted path
                $zip->addFile($pathToFile, $pathInZip); 
            }

            // Facade Images
            foreach ($facade_images as $image) {

                $pathToFile     =   public_path($image);

                $name           =   basename($pathToFile);
                $folderName     =   'Facade Images'; // Specify the folder name here

                // Adjust the path to include the folder name
                $pathInZip      =   $folderName . '/' . $name;

                // Add the file to the zip archive with the adjusted path
                $zip->addFile($pathToFile, $pathInZip);
            }

            $zip->close();
        }

        $filePath   =   public_path($zipFile);

        return $filePath;
    }

    public static function prepareImagesExport(Request $request) {

        //
        $images                             =   new stdClass();
        $images->customer_bar_code_images   =   [];
        $images->in_store_images            =   [];
        $images->facade_images              =   [];

        //
        $clients    =   Client::clientsExport($request);

        //
        foreach ($clients as $client) {

            if($client->CustomerBarCode_image   !=  "") {

                if(file_exists('uploads/clients/'.$client->id.'/'.$client->CustomerBarCode_image)) {

                    array_push($images->customer_bar_code_images    , ('/uploads/clients/'.$client->id.'/'.$client->CustomerBarCode_image));
                }
            }

            if($client->in_store_image          !=  "") {

                if(file_exists('uploads/clients/'.$client->id.'/'.$client->in_store_image)) {

                    array_push($images->in_store_images             , ('/uploads/clients/'.$client->id.'/'.$client->in_store_image));
                }
            }

            if($client->facade_image            !=  "") {

                if(file_exists('uploads/clients/'.$client->id.'/'.$client->facade_image)) {

                    array_push($images->facade_images               , ('/uploads/clients/'.$client->id.'/'.$client->facade_image));
                }
            }
        }

        //
        return $images;
    }

    //

    public static function downloadCustomerCodeImages(Request $request) {

        //
        $route_import               =   RouteImport::find($request->get("id_route_import"));

        //
        $images                     =   [];

        $zip                        =   new \ZipArchive;
        $zipFile                    =   $route_import->libelle.' Customer Code Images.zip';

        //
        $images                     =   RouteImport::prepareImagesExport($request);
        $customer_bar_code_images   =   $images->customer_bar_code_images;

        //
        if ($zip->open(public_path($zipFile), ZipArchive::CREATE) === TRUE) {

            // CustomerBarCode Images
            foreach ($customer_bar_code_images as $image) {

                $pathToFile     =   public_path($image);

                $name           =   basename($pathToFile);
                $folderName     =   'CustomerBarCode Images'; // Specify the folder name here

                // Adjust the path to include the folder name
                $pathInZip      =   $folderName . '/' . $name;

                // Add the file to the zip archive with the adjusted path
                $zip->addFile($pathToFile, $pathInZip);
            }

            $zip->close();
        }

        $filePath   =   public_path($zipFile);

        return $filePath;
    }

    public static function prepareCustomerCodeImagesExport(Request $request) {

        //
        $images                             =   new stdClass();
        $images->customer_bar_code_images   =   [];

        //
        $clients    =   Client::clientsExport($request);

        //
        foreach ($clients as $client) {

            if($client->CustomerBarCode_image   !=  "") {

                if(file_exists('uploads/clients/'.$client->id.'/'.$client->CustomerBarCode_image)) {

                    array_push($images->customer_bar_code_images    , ('/uploads/clients/'.$client->id.'/'.$client->CustomerBarCode_image));
                }
            }
        }

        //
        return $images;
    }

    //

    public static function downloadFacadeImages(Request $request) {

        //
        $route_import               =   RouteImport::find($request->get("id_route_import"));

        //
        $images                     =   [];

        $zip                        =   new \ZipArchive;
        $zipFile                    =   $route_import->libelle.' Facade Images.zip';

        //
        $images                     =   RouteImport::prepareImagesExport($request);
        $facade_images              =   $images->facade_images;

        //
        if ($zip->open(public_path($zipFile), ZipArchive::CREATE) === TRUE) {

            // Facade Images
            foreach ($facade_images as $image) {

                $pathToFile     =   public_path($image);

                $name           =   basename($pathToFile);
                $folderName     =   'Facade Images'; // Specify the folder name here

                // Adjust the path to include the folder name
                $pathInZip      =   $folderName . '/' . $name;

                // Add the file to the zip archive with the adjusted path
                $zip->addFile($pathToFile, $pathInZip);
            }

            $zip->close();
        }

        $filePath   =   public_path($zipFile);

        return $filePath;
    }

    public static function prepareFacadeImagesExport(Request $request) {

        //
        $images                             =   new stdClass();
        $images->facade_images              =   [];

        //
        $clients    =   Client::clientsExport($request);

        //
        foreach ($clients as $client) {

            if($client->facade_image            !=  "") {

                if(file_exists('uploads/clients/'.$client->id.'/'.$client->facade_image)) {

                    array_push($images->facade_images               , ('/uploads/clients/'.$client->id.'/'.$client->facade_image));
                }
            }
        }

        //
        return $images;
    }

    //

    public static function downloadInStoreImages(Request $request) {

        //
        $route_import               =   RouteImport::find($request->get("id_route_import"));

        //
        $images                     =   [];

        $zip                        =   new \ZipArchive;
        $zipFile                    =   $route_import->libelle.' In Store Images.zip';

        //
        $images                     =   RouteImport::prepareImagesExport($request);
        $in_store_images            =   $images->in_store_images;

        //
        if ($zip->open(public_path($zipFile), ZipArchive::CREATE) === TRUE) {

            // In Store Images
            foreach ($in_store_images as $image) {

                $pathToFile     =   public_path($image);

                $name           =   basename($pathToFile);
                $folderName     =   'In Store Images'; // Specify the folder name here

                // Adjust the path to include the folder name
                $pathInZip      =   $folderName . '/' . $name;

                // Add the file to the zip archive with the adjusted path
                $zip->addFile($pathToFile, $pathInZip); 
            }

            $zip->close();
        }

        $filePath   =   public_path($zipFile);

        return $filePath;
    }

    public static function prepareInStoreImagesExport(Request $request) {

        //
        $images                             =   new stdClass();
        $images->in_store_images            =   [];

        //
        $clients    =   Client::clientsExport($request);

        //
        foreach ($clients as $client) {

            if($client->in_store_image          !=  "") {

                if(file_exists('uploads/clients/'.$client->id.'/'.$client->in_store_image)) {

                    array_push($images->in_store_images             , ('/uploads/clients/'.$client->id.'/'.$client->in_store_image));
                }
            }
        }

        //
        return $images;
    }
}