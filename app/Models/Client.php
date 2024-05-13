<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Fluent;
use stdClass;

class Client extends Model
{
    use HasFactory;

    protected $guarded      =   [];

    protected $table        =   'clients';
    protected $primaryKey   =   'id';

    public    $timestamps   =   false;

    //

    protected static function boot()
    {
        parent::boot();

        // Event hook to set the date before storing a new object in the database
        static::creating(function ($client) {
            $client->created_at = Carbon::now()->format('d F Y');;
        });
    }

    //

    public static function storeClients(Request $request, int $id_route_import) {

        //
        $start_adding_date      =   Carbon::now();
        $finish_adding_date     =   Carbon::now();

        $adding_duration        =   $start_adding_date->diff($finish_adding_date);

        $start_adding_time      =   $start_adding_date->format('H:i:s');
        $adding_duration        =   $adding_duration->format('%H:%I:%S');
        //

        $clients    =   json_decode($request->get("data"));

        foreach ($clients as $client_elem) {

            // Client

            $client         =   new Client([
                'CustomerCode'          =>  $client_elem->CustomerCode              ,
                'CustomerNameE'         =>  $client_elem->CustomerNameE             ,
                'CustomerNameA'         =>  $client_elem->CustomerNameA             ,
                'Latitude'              =>  $client_elem->Latitude                  ,
                'Longitude'             =>  $client_elem->Longitude                 ,
                'Address'               =>  $client_elem->Address                   ,
                'DistrictNo'            =>  $client_elem->DistrictNo                ,
                'DistrictNameE'         =>  $client_elem->DistrictNameE             ,
                'CityNo'                =>  $client_elem->CityNo                    ,
                'CityNameE'             =>  $client_elem->CityNameE                 ,
                'Tel'                   =>  $client_elem->Tel                       ,
                'CustomerType'          =>  $client_elem->CustomerType              ,

                'Neighborhood'          =>  $client_elem->Neighborhood              ,
                'Landmark'              =>  $client_elem->Landmark                  ,
                'BrandAvailability'     =>  0                                       ,
                'BrandSourcePurchase'   =>  ""                                      ,

                'start_adding_time'     =>  $start_adding_time                      ,
                'adding_duration'       =>  $adding_duration                        ,
                'comment'               =>  ""                                      ,

                'id_route_import'       =>  $id_route_import                        ,
                'status'                =>  "nonvalidated"                          ,
                'nonvalidated_details'  =>  ""                                      ,
                'created_at'            =>  1,
                'owner'                 =>  Auth::user()->id
            ]);

            if($client_elem->JPlan      !=  null) {
    
                $client->JPlan              =   $client_elem->JPlan;
            }

            else {

                $client->JPlan              =   "";
            }

            if($client_elem->Journee    !=  null) {

                $client->Journee            =   $client_elem->Journee;
            }

            else {

                $client->Journee            =   "";
            }


            $client->save();
        }
    }

    //

    public static function changeRouteClients(Request $request, int $id_route_import) {

        $liste_clients_elem =   json_decode($request->get("clients"));

        foreach ($liste_clients_elem    as  $client_elem) {

            $client                 =   Client::find($client_elem->id);

            // Set DistrictNo
            if(isset($client_elem->owner)) {

                $client->owner          =   $client_elem->owner;
            }

            // Set DistrictNo
            if((isset($client_elem->DistrictNo))&&(isset($client_elem->DistrictNameE))) {

                $client->DistrictNo     =   $client_elem->DistrictNo;
                $client->DistrictNameE  =   $client_elem->DistrictNameE;
            }

            // Set CityNo
            if((isset($client_elem->CityNo))&&(isset($client_elem->CityNameE))) {

                $client->CityNo         =   $client_elem->CityNo;
                $client->CityNameE      =   $client_elem->CityNameE;
            }

            // Set JPlan
            if(isset($client_elem->JPlan)) {

                $client->JPlan          =   $client_elem->JPlan;
            }

            // Set Journee
            if(isset($client_elem->Journee)) {

                $client->Journee        =   $client_elem->Journee;
            }

            $client->save();
        }
    }

    //

    public static function validateStore(Request $request) 
    {

        $validator = Validator::make($request->all(), [
            'CustomerCode'          =>  ["required", "max:255"],
            'CustomerNameE'         =>  ["required", "max:255"],
            'CustomerNameA'         =>  ["required", "max:255"],
            'Tel'                   =>  ["required", "max:255"],
            'Latitude'              =>  ["required", "max:255"],
            'Longitude'             =>  ["required", "max:255"],
            'Address'               =>  ["required", "max:255"],
            'DistrictNo'            =>  ["required", "max:255"],
            'DistrictNameE'         =>  ["required", "max:255"],
            'CityNo'                =>  ["required", "max:255"],
            'CityNameE'             =>  ["required", "max:255"],
            'CustomerType'          =>  ["required", "max:255"],
            'status'                =>  ["required", "max:255"],
        ]);

        $validator->sometimes('nonvalidated_details',  ["required"] , function (Fluent $input) {
    
            return $input->status    ==  "nonvalidated";
        });

        //

        $validator->sometimes(['CustomerBarCode_image'],  ["file"] , function (Fluent $input) {
    
            return $input->CustomerBarCode_image_original_name      !=  "";
        });
        
        $validator->sometimes(['facade_image'],  ["file"] , function (Fluent $input) {
    
            return $input->facade_image_original_name               !=  "";
        });

        $validator->sometimes(['in_store_image'],  ["file"] , function (Fluent $input) {
    
            return $input->in_store_image_original_name             !=  "";
        });

        //

        return $validator;
    }

    public static function storeClient(Request $request, int $id_route_import) {
        
        //
        $start_adding_date      =   Carbon::parse($request->get("start_adding_date"));
        $finish_adding_date     =   Carbon::parse($request->get("finish_adding_date"));

        $adding_duration        =   $start_adding_date->diff($finish_adding_date);

        $start_adding_time      =   $start_adding_date->format('H:i:s');
        $adding_duration        =   $adding_duration->format('%H:%I:%S');
        //

        //
        $client     =   new Client([
            'CustomerCode'                  =>  $request->input("CustomerCode")                 ,
            'CustomerNameE'                 =>  $request->input("CustomerNameE")                ,
            'CustomerNameA'                 =>  $request->input("CustomerNameA")                ,
            'Latitude'                      =>  $request->input("Latitude")                     ,
            'Longitude'                     =>  $request->input("Longitude")                    ,
            'Address'                       =>  $request->input("Address")                      ,
            'DistrictNo'                    =>  $request->input("DistrictNo")                   ,
            'DistrictNameE'                 =>  $request->input("DistrictNameE")                ,
            'CityNo'                        =>  $request->input("CityNo")                       ,
            'CityNameE'                     =>  $request->input("CityNameE")                    ,
            'Tel'                           =>  $request->input("Tel")                          ,
            'CustomerType'                  =>  $request->input("CustomerType")                 ,

            'Neighborhood'                  =>  $request->input("Neighborhood")                 ,
            'Landmark'                      =>  $request->input("Landmark")                     ,
            'BrandAvailability'             =>  $request->input("BrandAvailability")            ,
            'BrandSourcePurchase'           =>  $request->input("BrandSourcePurchase")          ,

            'start_adding_time'             =>  $start_adding_time                              ,
            'adding_duration'               =>  $adding_duration                                ,
            'comment'                       =>  $request->input("comment")                      ,

            'id_route_import'               =>  $id_route_import                                ,  

            'owner'                         =>  Auth::user()->id
        ]);

        $client->save();

        //

        if($request->input("JPlan") !=  null) {

            $client->JPlan      =   $request->input("JPlan");
        }

        else {

            $client->JPlan      =   "";
        }

        //

        if($request->input("Journee") !=  null) {

            $client->Journee      =   $request->input("Journee");
        }

        else {

            $client->Journee      =   "";
        }

        //

        if($request->hasFile("CustomerBarCode_image")) {

            $fileName                           =   $client->DistrictNo."_".$client->CityNo."_".$client->id."_CUSTOMERCODE_".$client->CustomerCode.'.'.$request->file("CustomerBarCode_image")->getClientOriginalExtension();

            $request->file("CustomerBarCode_image")->move(public_path('uploads/clients/'.$client->id), $fileName);

            $client->CustomerBarCode_image      =   $fileName;
        } 

        else {

            $client->CustomerBarCode_image      =   "";
        }

        //

        if($request->hasFile("facade_image")) {

            $fileName                   =   $client->DistrictNo."_".$client->CityNo."_".$client->id."_FACADE_".$client->CustomerCode.'.'.$request->file("facade_image")->getClientOriginalExtension();

            $request->file("facade_image")->move(public_path('uploads/clients/'.$client->id), $fileName);

            $client->facade_image       =   $fileName;
        } 

        else {

            $client->facade_image       =   "";
        }

        //

        if($request->hasFile("in_store_image")) {

            $fileName                   =   $client->DistrictNo."_".$client->CityNo."_".$client->id."_IN_STORE_".$client->CustomerCode.'.'.$request->file("in_store_image")->getClientOriginalExtension();

            $request->file("in_store_image")->move(public_path('uploads/clients/'.$client->id), $fileName);

            $client->in_store_image     =   $fileName;
        }

        else {

            $client->in_store_image     =   "";
        }

        //

        if($request->input("CustomerBarCode_image_original_name") !=  null) {

            $client->CustomerBarCode_image_original_name    =   $request->input("CustomerBarCode_image_original_name");
        }

        else {

            $client->CustomerBarCode_image_original_name    =   "";
        }

        //

        if($request->input("facade_image_original_name") !=  null) {

            $client->facade_image_original_name             =   $request->input("facade_image_original_name");
        }

        else {

            $client->facade_image_original_name             =   "";
        }

        //

        if($request->input("in_store_image_original_name") !=  null) {

            $client->in_store_image_original_name           =   $request->input("in_store_image_original_name");
        }

        else {

            $client->in_store_image_original_name           =   "";
        }

        //

        if($request->input("status") !=  null) {

            $client->status      =   $request->input("status");
        }

        else {

            $client->status      =   "";
        }

        //

        if($request->input("nonvalidated_details") !=  null) {

            $client->nonvalidated_details      =   $request->input("nonvalidated_details");
        }

        else {

            $client->nonvalidated_details      =   "";
        }

        //

        $client->save();

        return $client;
    }

    //

    public static function validateUpdate(Request $request) 
    {

        $validator = Validator::make($request->all(), [
            'CustomerCode'          =>  ["required", "max:255"],
            'CustomerNameE'         =>  ["required", "max:255"],
            'CustomerNameA'         =>  ["required", "max:255"],
            'Tel'                   =>  ["required", "max:255"],
            'Latitude'              =>  ["required", "max:255"],
            'Longitude'             =>  ["required", "max:255"],
            'Address'               =>  ["required", "max:255"],
            'DistrictNo'            =>  ["required", "max:255"],
            'DistrictNameE'         =>  ["required", "max:255"],
            'CityNo'                =>  ["required", "max:255"],
            'CityNameE'             =>  ["required", "max:255"],
            'CustomerType'          =>  ["required", "max:255"],
            'status'                =>  ["required", "max:255"],
        ]);

        $validator->sometimes('nonvalidated_details',  ["required"] , function (Fluent $input) {
    
            return $input->status    ==  "nonvalidated";
        });

        //

        $validator->sometimes(['CustomerBarCode_image'],  ["file"] , function (Fluent $input) {
    
            return (($input->CustomerBarCode_image_original_name    !=  "")&&($input->CustomerBarCode_image_updated     ==  "true"));
        });
        
        $validator->sometimes(['facade_image'],  ["file"] , function (Fluent $input) {
    
            return (($input->facade_image_original_name             !=  "")&&($input->facade_image_updated              ==  "true"));
        });

        $validator->sometimes(['in_store_image'],  ["file"] , function (Fluent $input) {
    
            return (($input->in_store_image_original_name           !=  "")&&($input->in_store_image_updated            ==  "true"));
        });

        return $validator;
    }

    public static function updateClient(Request $request, int $id_route_import, int $id) {

        $client                     =   Client::find($id);

        if($client) {

            $client->CustomerCode                   =   $request->get("CustomerCode")           ;
            $client->CustomerNameE                  =   $request->get("CustomerNameE")          ;
            $client->CustomerNameA                  =   $request->get("CustomerNameA")          ;
            $client->Latitude                       =   $request->get("Latitude")               ;
            $client->Longitude                      =   $request->get("Longitude")              ;
            $client->Address                        =   $request->get("Address")                ;
            $client->DistrictNo                     =   $request->get("DistrictNo")             ;
            $client->DistrictNameE                  =   $request->get("DistrictNameE")          ;
            $client->CityNo                         =   $request->get("CityNo")                 ;
            $client->CityNameE                      =   $request->get("CityNameE")              ;
            $client->Tel                            =   $request->get("Tel")                    ;
            $client->CustomerType                   =   $request->get("CustomerType")           ;

            $client->Neighborhood                   =   $request->get("Neighborhood")           ;
            $client->Landmark                       =   $request->get("Landmark")               ;
            $client->BrandAvailability              =   $request->get("BrandAvailability")      ;
            $client->BrandSourcePurchase            =   $request->get("BrandSourcePurchase")    ;

            $client->comment                        =   $request->input("comment")              ;

            $client->id_route_import                =   $id_route_import                        ;

            //

            if($request->input("JPlan") !=  null) {

                $client->JPlan      =   $request->input("JPlan");
            }

            else {

                $client->JPlan      =   "";
            }

            //

            if($request->input("Journee") !=  null) {

                $client->Journee      =   $request->input("Journee");
            }

            else {

                $client->Journee      =   "";
            }

            //

            if($request->get("CustomerBarCode_image_updated")    ==  "true") {

                $old_CustomerBarCode_image  =   $client->CustomerBarCode_image;                             

                //

                if($request->hasFile("CustomerBarCode_image")) {

                    $fileName                           =   $client->DistrictNo."_".$client->CityNo."_".$client->id."_CUSTOMERCODE_".$client->CustomerCode.'.'.$request->file("CustomerBarCode_image")->getClientOriginalExtension();

                    $request->file("CustomerBarCode_image")->move(public_path('uploads/clients/'.$client->id), $fileName);

                    $client->CustomerBarCode_image      =   $fileName;
                } 

                else {

                    $client->CustomerBarCode_image      =   "";
                }

                // Delete Old CustomerBarCode_image
                if(($old_CustomerBarCode_image  !=  "")&&($old_CustomerBarCode_image    !=  $client->CustomerBarCode_image)) {

                    $filePath       =   public_path('uploads/clients/'.$client->id.'/'.$old_CustomerBarCode_image);  

                    if (file_exists($filePath)) {

                        unlink($filePath);
                    }
                }
            }

            //

            if($request->get("facade_image_updated")    ==  "true") {

                $old_facade_image  =   $client->facade_image;                             

                //

                if($request->hasFile("facade_image")) {

                    $fileName                   =   $client->DistrictNo."_".$client->CityNo."_".$client->id."_FACADE_".$client->CustomerCode.'.'.$request->file("facade_image")->getClientOriginalExtension();

                    $request->file("facade_image")->move(public_path('uploads/clients/'.$client->id), $fileName);

                    $client->facade_image       =   $fileName;
                } 

                else {

                    $client->facade_image       =   "";
                }

                // Delete Old facade_image
                if(($old_facade_image   !=  "")&&($old_facade_image  !=  $client->facade_image)) {

                    $filePath       =   public_path('uploads/clients/'.$client->id.'/'.$old_facade_image);  

                    if (file_exists($filePath)) {

                        unlink($filePath);
                    }
                }
            }

            //

            if($request->get("in_store_image_updated")  ==  "true") {

                $old_in_store_image  =   $client->in_store_image;                             

                //

                if($request->hasFile("in_store_image")) {

                    $fileName                   =   $client->DistrictNo."_".$client->CityNo."_".$client->id."_IN_STORE_".$client->CustomerCode.'.'.$request->file("in_store_image")->getClientOriginalExtension();

                    $request->file("in_store_image")->move(public_path('uploads/clients/'.$client->id), $fileName);

                    $client->in_store_image     =   $fileName;
                }

                else {

                    $client->in_store_image     =   "";
                }

                // Delete Old in_store_image
                if(($old_in_store_image   !=  "")&&($old_in_store_image  !=  $client->in_store_image)) {

                    $filePath       =   public_path('uploads/clients/'.$client->id.'/'.$old_in_store_image);  

                    if (file_exists($filePath)) {

                        unlink($filePath);
                    }
                }
            }

            //

            if($request->input("CustomerBarCode_image_original_name") !=  null) {

                $client->CustomerBarCode_image_original_name      =   $request->input("CustomerBarCode_image_original_name");
            }

            else {

                $client->CustomerBarCode_image_original_name      =   "";
            }

            //

            if($request->input("facade_image_original_name") !=  null) {

                $client->facade_image_original_name      =   $request->input("facade_image_original_name");
            }

            else {

                $client->facade_image_original_name      =   "";
            }

            //

            if($request->input("in_store_image_original_name") !=  null) {

                $client->in_store_image_original_name      =   $request->input("in_store_image_original_name");
            }

            else {

                $client->in_store_image_original_name      =   "";
            }

            //

            if($request->input("status") !=  null) {

                $client->status      =   $request->input("status");
            }

            else {

                $client->status      =   "";
            }

            //

            if($request->input("nonvalidated_details") !=  null) {

                $client->nonvalidated_details      =   $request->input("nonvalidated_details");
            }

            else {

                $client->nonvalidated_details      =   "";
            }

            $client->save();
        }

        return $client;
    }

    //

    public static function showClient(Request $request, int $id_route_import, int $id) {

        // return  Client::where("clients.id_route_import", $id_route_import)
        //         ->where("clients.id", $id)
        //         ->join('users', 'clients.owner', '=', 'users.id')
        //         ->select('clients.*', 'users.nom as owner_name')
        //         ->first();
        
        $client =   Client::find($id);
        
        if($client) {
            
            $user   =   User::find($client->id);
            
            if($user) {
                
                $client->owner_name     =   $user->nom;
            }            
        }
        
        return $client;
    }

    //

    public static function validateClient(int $id_route_import, int $id) {

        $client                     =   Client::find($id);

        if($client) {

            $client->status         =   "validated";
            $client->save();
        }
    }

    //

    public static function updateResumeClients(Request $request) {

        $clients    =   json_decode($request->get("data"));

        foreach ($clients as $client_tempo) {

            // Client

            $client                             =   Client::find($client_tempo->id);

            if($client_tempo->JPlan     !=  null) {
    
                $client->JPlan                      =   $client_tempo->JPlan;
            }

            else {

                $client->JPlan                      =   "";
            }

            if($client_tempo->Journee   !=  null) {

                $client->Journee                    =   $client_tempo->Journee;
            }

            else {

                $client->Journee                    =   "";
            }

            $client->save();
        }
    }

    //

    public static function updateClients(Request $request, int $id_route_import) {

        $clients    =   json_decode($request->get("data"));

        foreach ($clients as $client_tempo) {

            // Client

            $client                             =   Client::find($client_tempo->id);

            $client->CustomerCode               =   $client_tempo->CustomerCode;
            $client->CustomerNameE              =   $client_tempo->CustomerNameE;
            $client->CustomerNameA              =   $client_tempo->CustomerNameA;
            $client->Latitude                   =   $client_tempo->Latitude;
            $client->Longitude                  =   $client_tempo->Longitude;
            $client->Address                    =   $client_tempo->Address;
            $client->DistrictNo                 =   $client_tempo->DistrictNo;
            $client->DistrictNameE              =   $client_tempo->DistrictNameE;
            $client->CityNo                     =   $client_tempo->CityNo;
            $client->CityNameE                  =   $client_tempo->CityNameE;
            $client->Tel                        =   $client_tempo->Tel;
            $client->CustomerType               =   $client_tempo->CustomerType;

            $client->Neighborhood               =   $client_tempo->Neighborhood;
            $client->Landmark                   =   $client_tempo->Landmark;
            $client->BrandAvailability          =   $client_tempo->BrandAvailability;
            $client->BrandSourcePurchase        =   $client_tempo->BrandSourcePurchase;

            if($client_tempo->JPlan     !=  null) {
    
                $client->JPlan                      =   $client_tempo->JPlan;
            }

            else {

                $client->JPlan                      =   "";
            }

            if($client_tempo->Journee   !=  null) {

                $client->Journee                    =   $client_tempo->Journee;
            }

            else {

                $client->Journee                    =   "";
            }

            $client->save();
        }
    }

    //

    public static function deleteClient(int $id_route_import, int $id) {

        $client     =   Client::find($id);

        $directory  =   public_path('uploads/clients/'.$client->id);

        if (File::exists($directory)) {

            File::deleteDirectory($directory);
        }

        $client->delete();
    }

    //

    public static function clientsExport(Request $request) {

        if($request->get("CustomerType") ==  "All") {

            $clients    =   Client::where("id_route_import", $request->get("id_route_import"))
                                ->select("clients.*", "users.nom as owner")
                                ->join("users", "clients.owner", "users.id")
                                ->orderBy('clients.id', 'desc')
                                ->get()
                                ->makeHidden(['id_route_import']);
        }

        if($request->get("CustomerType") ==  "Validated") {

            $clients    =   Client::where([["CustomerType", "validated"]    , ["id_route_import", $request->get("id_route_import")]])
                                ->select("clients.*", "users.nom as owner")
                                ->join("users", "clients.owner", "users.id")
                                ->orderBy('clients.id', 'desc')
                                ->get()
                                ->makeHidden(['id_route_import']);
        }

        if($request->get("CustomerType") ==  "Pending") {

            $clients    =   Client::where([["CustomerType", "pending"]      , ["id_route_import", $request->get("id_route_import")]])
                                ->select("clients.*", "users.nom as owner")
                                ->join("users", "clients.owner", "users.id")
                                ->orderBy('clients.id', 'desc')
                                ->get()
                                ->makeHidden(['id_route_import']);
        }

        if($request->get("CustomerType") ==  "NonValidated") {

            $clients    =   Client::where([["CustomerType", "nonvalidated"] , ["id_route_import", $request->get("id_route_import")]])
                                ->select("clients.*", "users.nom as owner")
                                ->join("users", "clients.owner", "users.id")
                                ->orderBy('clients.id', 'desc')
                                ->get()
                                ->makeHidden(['id_route_import']);
        }

        return $clients;
    }

    //

    public static function getDoublesClients(int $id_route_import) {

        $getDoublant    =   new stdClass();

        $getDoublant->getDoublantCustomerCode           =   Client::getDoublesCustomerCodeClients($id_route_import);
        $getDoublant->getDoublantCustomerNameE          =   Client::getDoublesCustomerNameEClients($id_route_import);
        $getDoublant->getDoublantTel                    =   Client::getDoublesTelClients($id_route_import);
        $getDoublant->getDoublantLatitudeLongitude      =   Client::getDoublesGPSClients($id_route_import);

        return $getDoublant;
    }

    public static function getDoublesTelClients(int $id_route_import) {

        return  DB::table('clients')

                    ->join('users', 'clients.owner', 'users.id')

                    ->where('id_route_import', $id_route_import)
                    ->whereIn('clients.Tel', function ($query) use ($id_route_import) {
                        $query->select('Tel')
                            ->from('clients')
                            ->where('id_route_import', $id_route_import)
                            ->groupBy('Tel')
                            ->havingRaw('COUNT(*) > 1');
                    })

                    ->select('clients.*', 'users.nom as owner_name')

                    ->get();
    } 

    public static function getDoublesCustomerCodeClients(int $id_route_import) {

        return  DB::table('clients')

                    ->join('users', 'clients.owner', 'users.id')

                    ->where('id_route_import', $id_route_import)
                    ->whereIn('clients.CustomerCode', function ($query) use ($id_route_import) {
                        $query->select('CustomerCode')
                            ->from('clients')
                            ->where('id_route_import', $id_route_import)
                            ->groupBy('CustomerCode')
                            ->havingRaw('COUNT(*) > 1');
                    })

                    ->select('clients.*', 'users.nom as owner_name')

                    ->get();
    }

    public static function getDoublesCustomerNameEClients(int $id_route_import) {

        return  DB::table('clients')

                    ->join('users', 'clients.owner', 'users.id')

                    ->where('id_route_import', $id_route_import)
                    ->whereIn('clients.CustomerNameE', function ($query) use ($id_route_import) {
                        $query->select('CustomerNameE')
                            ->from('clients')
                            ->where('id_route_import', $id_route_import)
                            ->groupBy('CustomerNameE')
                            ->havingRaw('COUNT(*) > 1');
                    })

                    ->select('clients.*', 'users.nom as owner_name')

                    ->get();
    }

    public static function getDoublesGPSClients(int $id_route_import) {

        return  DB::table('clients')

                    ->join('users', 'clients.owner', 'users.id')

                    ->where('id_route_import', $id_route_import)
                    ->whereIn(DB::raw('(clients.Latitude, clients.Longitude)'), function ($query) use ($id_route_import) {
                        $query->select('Latitude', 'Longitude')
                            ->from('clients')
                            ->where('id_route_import', $id_route_import)
                            ->groupBy('Latitude', 'Longitude')
                            ->havingRaw('COUNT(*) > 1');
                    })

                    ->select('clients.*', 'users.nom as owner_name')

                    ->get();
    }
}