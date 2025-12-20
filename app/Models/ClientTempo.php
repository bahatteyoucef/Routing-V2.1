<?php

namespace App\Models;

use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Fluent;
use stdClass;

class ClientTempo extends Model
{

    //

    use HasFactory;

    protected $guarded      =   [];

    protected $table        =   'clients_tempo';
    protected $primaryKey   =   'id';

    public    $timestamps   =   false;

    //

    // Clients Tempo Index
    public static function index(int $id_route_import_tempo) {

        $clients        =   ClientTempo::where("id_route_import_tempo", $id_route_import_tempo)
                                ->join('users', 'clients_tempo.owner', '=', 'users.id')
                                ->select('clients_tempo.*', 'users.username as owner_username')
                                ->get();

        //
        foreach ($clients as $client) {

            $AvailableBrands_AssocArray                 =   json_decode($client->AvailableBrands, true); // Convert JSON to associative array
            $client->AvailableBrands_array_formatted    =   array_values($AvailableBrands_AssocArray); // Extract values as an indexed array
            $client->AvailableBrands_string_formatted   =   implode(", ", $client->AvailableBrands_array_formatted);
        }

        //
        return $clients;
    }

    //

    // when user add new map
    public static function storeClients(Request $request, int $id_route_import_tempo) {

        //

        ClientTempo::deleteClients();

        //

        $clients    =   json_decode($request->get("data"));

        foreach ($clients as $client_elem) {

            if(isset($client_elem->owner)) {
                $user   =   User::where('username', $client_elem->owner)->first();
            }

            //
            if(!isset($user)) {
                $user   =   Auth::user();
            }

            //  //  //  //  //

            //
            if (preg_match('/[\/\\\\:*?"<>|& ]/', $client_elem->CustomerCode)) {
                throw new Exception("Le champ CustomerCode contient des caractères interdits : ".$client_elem->CustomerCode);
            }

            //  //  //  //  //

            $brands     =   array_map('trim', explode(',', $client_elem->AvailableBrands)); // Split and trim whitespace

            $result     =   [];

            foreach ($brands as $index => $value) {
                $result["brand_$index"] = $value;
            }

            $AvailableBrands    =   json_encode($result, JSON_UNESCAPED_UNICODE);

            //  //  //  //  //

            // Client
            $client             =   new ClientTempo([
                // 'id'                        =>  $client_elem->id                                        ?? ''               ,

                'NewCustomer'               =>  $client_elem->NewCustomer                               ?? ''               ,
                'CustomerIdentifier'        =>  $client_elem->CustomerIdentifier                        ?? ''               ,
                'CustomerCode'              =>  $client_elem->CustomerCode                              ?? ''               ,
                'OpenCustomer'              =>  $client_elem->OpenCustomer                              ?? ''               ,
                'CustomerNameE'             =>  mb_strtoupper($client_elem->CustomerNameE, 'UTF-8')     ?? ''               ,
                'CustomerNameA'             =>  mb_strtoupper($client_elem->CustomerNameA, 'UTF-8')     ?? ''               ,
                'Latitude'                  =>  $client_elem->Latitude                                  ?? 0                ,
                'Longitude'                 =>  $client_elem->Longitude                                 ?? 0                ,
                'Address'                   =>  $client_elem->Address                                   ?? ''               ,
                'DistrictNo'                =>  $client_elem->DistrictNo                                ?? ''               ,
                'DistrictNameE'             =>  $client_elem->DistrictNameE                             ?? ''               ,
                'CityNo'                    =>  $client_elem->CityNo                                    ?? ''               ,
                'CityNameE'                 =>  $client_elem->CityNameE                                 ?? ''               ,
                'Tel'                       =>  $client_elem->Tel                                       ?? ''               ,
                'tel_comment'               =>  $client_elem->tel_comment                               ?? ''               ,
                'tel_status'                =>  $client_elem->tel_status                                ?? ''               ,
                'CustomerType'              =>  $client_elem->CustomerType                              ?? ''               ,

                'status'                    =>  $client_elem->status                                    ?? ''               ,

                'Neighborhood'              =>  $client_elem->Neighborhood                              ?? ''               ,
                'Landmark'                  =>  $client_elem->Landmark                                  ?? ''               ,
                'BrandAvailability'         =>  $client_elem->BrandAvailability                         ?? ''               ,
                'BrandSourcePurchase'       =>  $client_elem->BrandSourcePurchase                       ?? ''               ,

                'Frequency'                 =>  $client_elem->Frequency                                 ?? ''               ,
                'SuperficieMagasin'         =>  $client_elem->SuperficieMagasin                         ?? ''               ,
                'NbrAutomaticCheckouts'     =>  $client_elem->NbrAutomaticCheckouts                     ?? ''               ,
                'AvailableBrands'           =>  $AvailableBrands                                        ?? ''               ,

                'start_adding_time'         =>  $client_elem->start_adding_time                         ?? ''               ,
                'adding_duration'           =>  $client_elem->adding_duration                           ?? ''               ,

                'JPlan'                     =>  $client_elem->JPlan                                     ?? ''               ,
                'Journee'                   =>  $client_elem->Journee                                   ?? ''               ,

                'comment'                   =>  $client_elem->comment                                   ?? ''               ,

                'created_at'                =>  $client_elem->created_at                                ?? Carbon::now()    ,

                'id_route_import_tempo'     =>  $id_route_import_tempo                                  ?? ''               ,

                'owner'                     =>  $user->id                                                                   ,
                'created_by'                =>  Auth::user()->id
            ]);

            //
            $client->save();
        }
    }

    //

    public static function validateUpdate(Request $request) 
    {

        $validator = Validator::make($request->all(), [
            'NewCustomer'           =>  ["required", "max:255"],
            'OpenCustomer'          =>  ["required", "max:255"],
            'CustomerIdentifier'    =>  ["required", "max:255"],
            'CustomerCode'          =>  ["required_if:OpenCustomer,Ouvert", "max:255", function ($attribute, $value, $fail) {
                if (preg_match('/[\/\\\\:*?"<>|& ]/', $value)) {
                    $fail("Le champ $attribute contient des caractères interdits.");
                }
            }],
            'CustomerNameE'         =>  ["required_if:OpenCustomer,Ouvert", "max:255"],
            'CustomerNameA'         =>  ["required", "max:255"],
            'Tel'                   =>  ["required_if:OpenCustomer,Ouvert", "max:255"],
            'Latitude'              =>  ["required", "max:255"],
            'Longitude'             =>  ["required", "max:255"],
            'Address'               =>  ["required", "max:255"],
            'DistrictNo'            =>  ["required", "max:255"],
            'DistrictNameE'         =>  ["required", "max:255"],
            'CityNo'                =>  ["required", "max:255"],
            'CityNameE'             =>  ["required", "max:255"],
            'CustomerType'          =>  ["required", "max:255"],
            'status'                =>  ["required", "max:255"],

            'Frequency'             =>  ["required", "max:255"],
            'SuperficieMagasin'     =>  ["required_if:OpenCustomer,Ouvert", "max:255"],
            'NbrAutomaticCheckouts' =>  ["required_if:OpenCustomer,Ouvert", "max:255"],
        ]);

        $validator->sometimes('nonvalidated_details',  ["required"] , function (Fluent $input) {
    
            return $input->status    ==  "nonvalidated";
        });

        //

        $validator->sometimes(['AvailableBrands'],  ["required_if:OpenCustomer,Ouvert"] , function (Fluent $input) {
    
            return $input->BrandAvailability    ==  "";
        });

        //

        $validator->sometimes(['CustomerBarCode_image'],  ["required_if:OpenCustomer,Ouvert"] , function (Fluent $input) {
    
            return (($input->CustomerBarCode_image_original_name    !=  "")&&($input->CustomerBarCode_image_updated     ==  "true"));
        });
        
        $validator->sometimes(['facade_image'],  ["file"] , function (Fluent $input) {
    
            return (($input->facade_image_original_name             !=  "")&&($input->facade_image_updated              ==  "true"));
        });

        $validator->sometimes(['in_store_image'],  ["required_if:OpenCustomer,Ouvert"] , function (Fluent $input) {
    
            return (($input->in_store_image_original_name           !=  "")&&($input->in_store_image_updated            ==  "true"));
        });

        //

        return $validator;
    }

    public static function updateClient(Request $request, int $id_route_import_tempo, int $id) {

        $client                             =   ClientTempo::find($id);

        if($client) {

            //
            $brandsArray             =   json_decode($request->input("AvailableBrands"), true);

            $AvailableBrands        =   [];
            foreach ($brandsArray as $index => $value) {
                $AvailableBrands["brand_$index"] = $value;
            }
            //

            $client->NewCustomer                    =   $request->get("NewCustomer")                            ?? ''   ;
            $client->OpenCustomer                   =   $request->get("OpenCustomer")                           ?? ''   ;
            $client->CustomerIdentifier             =   $request->get("CustomerIdentifier")                     ?? ''   ;
            $client->CustomerCode                   =   $request->get("CustomerCode")                           ?? ''   ;
            $client->Latitude                       =   $request->get("Latitude")                               ?? ''   ;
            $client->Longitude                      =   $request->get("Longitude")                              ?? ''   ;
            $client->Address                        =   $request->get("Address")                                ?? ''   ;
            $client->DistrictNo                     =   $request->get("DistrictNo")                             ?? ''   ;
            $client->DistrictNameE                  =   $request->get("DistrictNameE")                          ?? ''   ;
            $client->CityNo                         =   $request->get("CityNo")                                 ?? ''   ;
            $client->CityNameE                      =   $request->get("CityNameE")                              ?? ''   ;
            $client->Tel                            =   $request->get("Tel")                                    ?? ''   ;
            $client->CustomerType                   =   $request->get("CustomerType")                           ?? ''   ;

            $client->Neighborhood                   =   $request->get("Neighborhood")                           ?? ''   ;
            $client->Landmark                       =   $request->get("Landmark")                               ?? ''   ;
            $client->BrandAvailability              =   $request->get("BrandAvailability")                      ?? ''   ;
            $client->BrandSourcePurchase            =   $request->get("BrandSourcePurchase")                    ?? ''   ;

            $client->Frequency                      =   $request->get("Frequency")                              ?? ''   ;
            $client->SuperficieMagasin              =   $request->get("SuperficieMagasin")                      ?? ''   ;
            $client->NbrAutomaticCheckouts          =   $request->get("NbrAutomaticCheckouts")                  ?? ''   ;
            $client->comment                        =   $request->input("comment")                              ?? ''   ;
            $client->Journee                        =   $request->input("Journee")                              ?? ''   ;
            $client->status                         =   $request->input("status")                               ?? ''   ;
            $client->nonvalidated_details           =   $request->input("nonvalidated_details")                 ?? ''   ;

            $client->CustomerNameE                  =   mb_strtoupper($request->get("CustomerNameE"), 'UTF-8')  ?? ''   ;
            $client->CustomerNameA                  =   mb_strtoupper($request->get("CustomerNameA"), 'UTF-8')  ?? ''   ;
            $client->JPlan                          =   mb_strtoupper($request->input("JPlan"), 'UTF-8')        ?? ''   ;

            $client->AvailableBrands                =   json_encode($AvailableBrands, JSON_UNESCAPED_UNICODE)           ;

            if(Auth::user()->hasRole('FrontOffice')) {

                $client->owner                          =   Auth::user()->id;
            }

            else {

                $client->owner                          =   $request->get("owner");
                $client->tel_status                     =   $request->get("tel_status");
                $client->tel_comment                    =   $request->get("tel_comment");
            }

            //
            $client->save();

            //  //  //  //  //
            //  //  //  //  //
            //  //  //  //  //

            //
            $AvailableBrands_AssocArray                     =   json_decode($client->AvailableBrands, true); // Convert JSON to associative array
            $client->AvailableBrands_array_formatted        =   array_values($AvailableBrands_AssocArray); // Extract values as an indexed array
            $client->AvailableBrands_string_formatted       =   implode(", ", $client->AvailableBrands_array_formatted);
        }

        return $client;
    }

    //

    public static function deleteClient(int $id_route_import_tempo, int $id) {

        $client                             =   ClientTempo::find($id);

        if($client) {

            $client->delete();
        }
    }

    //

    // Resume Clients
    public static function updateResumeClients(Request $request) {

        $clients    =   json_decode($request->get("data"));

        foreach ($clients as $client_tempo) {

            // Client

            $client                             =   ClientTempo::find($client_tempo->id);

            if($client_tempo->JPlan     !=  null) {

                $client->JPlan                  =   mb_strtoupper($client_tempo->JPlan, 'UTF-8');
            }

            else {

                $client->JPlan                  =   "";
            }

            if($client_tempo->Journee   !=  null) {

                $client->Journee                =   $client_tempo->Journee;
            }

            else {

                $client->Journee                =   "";
            }

            $client->save();
        }
    }

    //

    // Delete Clients Tempo when submit clients or when add new map
    public static function deleteClients() {

        ClientTempo::where('created_by', Auth::user()->id)->delete();
    }

    //

    public static function getDoublesClients(int $id_route_import_tempo) {

        $getDoublant    =   new stdClass();

        $getDoublant->getDoublantCustomerCode           =   ClientTempo::getDoublesCustomerCodeClients($id_route_import_tempo);
        $getDoublant->getDoublantCustomerNameE          =   ClientTempo::getDoublesCustomerNameEClients($id_route_import_tempo);
        $getDoublant->getDoublantTel                    =   ClientTempo::getDoublesTelClients($id_route_import_tempo);
        $getDoublant->getDoublantGPS                    =   ClientTempo::getDoublesGPSClients($id_route_import_tempo);

        return $getDoublant;
    }

    public static function getDoublesCustomerCodeClients(int $id_route_import_tempo) {

        // build a subquery that returns only the duplicate Tels
        $duplicates     =   DB::table('clients_tempo')
                                ->select('CustomerCode')
                                ->where('id_route_import_tempo', $id_route_import_tempo)
                                ->groupBy('CustomerCode')
                                ->havingRaw('COUNT(*) > 1');

        // join that subquery back to the main table
        $clients        =   DB::table('clients_tempo as c')
                                ->joinSub($duplicates, 'd', function($join) {
                                    $join->on('c.CustomerCode', '=', 'd.CustomerCode');
                                })
                                ->where('c.id_route_import_tempo', $id_route_import_tempo)
                                ->get();

        //
        foreach ($clients as $client) {

            $AvailableBrands_AssocArray                 =   json_decode($client->AvailableBrands, true); // Convert JSON to associative array
            $client->AvailableBrands_array_formatted    =   array_values($AvailableBrands_AssocArray); // Extract values as an indexed array
            $client->AvailableBrands_string_formatted   =   implode(", ", $client->AvailableBrands_array_formatted);
        }

        //
        return $clients;
    }

    public static function getDoublesCustomerNameEClients(int $id_route_import_tempo) {

        // build a subquery that returns only the duplicate Tels
        $duplicates     =   DB::table('clients_tempo')
                                ->select('CustomerNameE')
                                ->where('id_route_import_tempo', $id_route_import_tempo)
                                ->groupBy('CustomerNameE')
                                ->havingRaw('COUNT(*) > 1');

        // join that subquery back to the main table
        $clients        =   DB::table('clients_tempo as c')
                                ->joinSub($duplicates, 'd', function($join) {
                                    $join->on('c.CustomerNameE', '=', 'd.CustomerNameE');
                                })
                                ->where('c.id_route_import_tempo', $id_route_import_tempo)
                                ->get();

        //
        foreach ($clients as $client) {

            $AvailableBrands_AssocArray                 =   json_decode($client->AvailableBrands, true); // Convert JSON to associative array
            $client->AvailableBrands_array_formatted    =   array_values($AvailableBrands_AssocArray); // Extract values as an indexed array
            $client->AvailableBrands_string_formatted   =   implode(", ", $client->AvailableBrands_array_formatted);
        }

        //
        return $clients;
    }

    public static function getDoublesTelClients(int $id_route_import_tempo) {

        // build a subquery that returns only the duplicate Tels
        $duplicates     =   DB::table('clients_tempo')
                                ->select('Tel')
                                ->where('id_route_import_tempo', $id_route_import_tempo)
                                ->groupBy('Tel')
                                ->havingRaw('COUNT(*) > 1');

        // join that subquery back to the main table
        $clients        =   DB::table('clients_tempo as c')
                                ->joinSub($duplicates, 'd', function($join) {
                                    $join->on('c.Tel', '=', 'd.Tel');
                                })
                                ->where('c.id_route_import_tempo', $id_route_import_tempo)
                                ->get();

        //
        foreach ($clients as $client) {

            $AvailableBrands_AssocArray                 =   json_decode($client->AvailableBrands, true); // Convert JSON to associative array
            $client->AvailableBrands_array_formatted    =   array_values($AvailableBrands_AssocArray); // Extract values as an indexed array
            $client->AvailableBrands_string_formatted   =   implode(", ", $client->AvailableBrands_array_formatted);
        }

        //
        return $clients;
    } 

    public static function getDoublesGPSClients(int $id_route_import_tempo) {

        // 1) Build a subquery that returns only the duplicate coordinate pairs
        $duplicates     =   DB::table('clients_tempo')
                                ->select('Latitude', 'Longitude')
                                ->where('id_route_import_tempo', $id_route_import_tempo)
                                ->groupBy('Latitude', 'Longitude')
                                ->havingRaw('COUNT(*) > 1');

        // 2) Join that back to get all the full rows for those pairs
        $clients        =   DB::table('clients_tempo as c')
                                ->joinSub($duplicates, 'd', function ($join) {
                                    $join->on('c.Latitude',  '=', 'd.Latitude')
                                        ->on('c.Longitude', '=', 'd.Longitude');
                                })
                                ->where('c.id_route_import_tempo', $id_route_import_tempo)
                                ->get();

        //
        foreach ($clients as $client) {

            $AvailableBrands_AssocArray                 =   json_decode($client->AvailableBrands, true); // Convert JSON to associative array
            $client->AvailableBrands_array_formatted    =   array_values($AvailableBrands_AssocArray); // Extract values as an indexed array
            $client->AvailableBrands_string_formatted   =   implode(", ", $client->AvailableBrands_array_formatted);
        }

        //
        return $clients;
    } 

    //

    public static function getDoublesCustomerCodeClients_old(int $id_route_import_tempo) {

        $clients    =   DB::table('clients_tempo')
                            ->where('id_route_import_tempo', $id_route_import_tempo)
                            ->whereIn('CustomerCode', function ($query) use ($id_route_import_tempo) {
                                $query->select('CustomerCode')
                                    ->from('clients_tempo')
                                    ->where('id_route_import_tempo', $id_route_import_tempo)
                                    ->groupBy('CustomerCode')
                                    ->havingRaw('COUNT(*) > 1');
                            })
                            ->get();

        //
        foreach ($clients as $client) {

            $AvailableBrands_AssocArray                 =   json_decode($client->AvailableBrands, true); // Convert JSON to associative array
            $client->AvailableBrands_array_formatted    =   array_values($AvailableBrands_AssocArray); // Extract values as an indexed array
            $client->AvailableBrands_string_formatted   =   implode(", ", $client->AvailableBrands_array_formatted);
        }

        //
        return $clients;
    }

    public static function getDoublesCustomerNameEClients_old(int $id_route_import_tempo) {

        $clients    =   DB::table('clients_tempo')
                            ->where('id_route_import_tempo', $id_route_import_tempo)
                            ->whereIn('CustomerNameE', function ($query) use ($id_route_import_tempo) {
                                $query->select('CustomerNameE')
                                    ->from('clients_tempo')
                                    ->where('id_route_import_tempo', $id_route_import_tempo)
                                    ->groupBy('CustomerNameE')
                                    ->havingRaw('COUNT(*) > 1');
                            })
                            ->get();

        //
        foreach ($clients as $client) {

            $AvailableBrands_AssocArray                 =   json_decode($client->AvailableBrands, true); // Convert JSON to associative array
            $client->AvailableBrands_array_formatted    =   array_values($AvailableBrands_AssocArray); // Extract values as an indexed array
            $client->AvailableBrands_string_formatted   =   implode(", ", $client->AvailableBrands_array_formatted);
        }

        //
        return $clients;
    }

    public static function getDoublesTelClients_old(int $id_route_import_tempo) {

        $clients    =   DB::table('clients_tempo')
                            ->where('id_route_import_tempo', $id_route_import_tempo)
                            ->whereIn('Tel', function ($query) use ($id_route_import_tempo) {
                                $query->select('Tel')
                                    ->from('clients_tempo')
                                    ->where('id_route_import_tempo', $id_route_import_tempo)
                                    ->groupBy('Tel')
                                    ->havingRaw('COUNT(*) > 1');
                            })
                            ->get();

        //
        foreach ($clients as $client) {

            $AvailableBrands_AssocArray                 =   json_decode($client->AvailableBrands, true); // Convert JSON to associative array
            $client->AvailableBrands_array_formatted    =   array_values($AvailableBrands_AssocArray); // Extract values as an indexed array
            $client->AvailableBrands_string_formatted   =   implode(", ", $client->AvailableBrands_array_formatted);
        }

        //
        return $clients;
    }

    public static function getDoublesGPSClients_old(int $id_route_import_tempo) {

        $clients    =   DB::table('clients_tempo')
                            ->where('id_route_import_tempo', $id_route_import_tempo)
                            ->whereIn(DB::raw('(Latitude, Longitude)'), function ($query) use ($id_route_import_tempo) {
                                $query->select('Latitude', 'Longitude')
                                    ->from('clients_tempo')
                                    ->where('id_route_import_tempo', $id_route_import_tempo)
                                    ->groupBy('Latitude', 'Longitude')
                                    ->havingRaw('COUNT(*) > 1');
                            })
                            ->get();

        //
        foreach ($clients as $client) {

            $AvailableBrands_AssocArray                 =   json_decode($client->AvailableBrands, true); // Convert JSON to associative array
            $client->AvailableBrands_array_formatted    =   array_values($AvailableBrands_AssocArray); // Extract values as an indexed array
            $client->AvailableBrands_string_formatted   =   implode(", ", $client->AvailableBrands_array_formatted);
        }

        //
        return $clients;
    } 
}
