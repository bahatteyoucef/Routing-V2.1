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

        $clients                =   ClientTempo::where("id_route_import_tempo", $request->get("id_route_import_tempo"))->get();;

        foreach ($clients as $client_elem) {

            //
            if (preg_match('/[\/\\\\:*?"<>|& ]/', $client_elem->CustomerCode)) {
                throw new Exception("Le champ CustomerCode contient des caractères interdits : ".$client_elem->CustomerCode);
            }

            //  //  //  //  //

            // Client
            $client         =   new Client([
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

                'AvailableBrands'           =>  $client_elem->AvailableBrands                           ?? ''               ,

                'start_adding_time'         =>  $start_adding_time                                      ?? ''               ,
                'adding_duration'           =>  $adding_duration                                        ?? ''               ,

                'JPlan'                     =>  $client_elem->JPlan                                     ?? ''               ,
                'Journee'                   =>  $client_elem->Journee                                   ?? ''               ,

                'comment'                   =>  $client_elem->comment                                   ?? ''               ,

                'created_at'                =>  $client_elem->created_at                                ?? Carbon::now()    ,

                'id_route_import'           =>  $id_route_import                                        ?? ''               ,

                'owner'                     =>  $client_elem->owner                                     ?? ''               
            ]);

            //
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

                $client->JPlan          =   mb_strtoupper($client_elem->JPlan, 'UTF-8');
            }

            // Set Journee
            if(isset($client_elem->Journee)) {

                $client->Journee        =   $client_elem->Journee;
            }

            // Set Status
            if(isset($client_elem->status)) {

                $client->status         =   $client_elem->status;
            }

            $client->save();
        }
    }

    public static function deleteClients(Request $request, int $id_route_import) {

        $liste_clients_ids  =   json_decode($request->get("clients"), true);

        //
        if (!empty($liste_clients_ids)) {

            $clients        =   Client::whereIn('id', $liste_clients_ids)->get(); // Fetch clients first

            foreach ($clients as $client) {

                // $directory = public_path('uploads/clients/' . $client->id);

                // if (File::exists($directory)) {
                //     File::deleteDirectory($directory); // Delete the entire directory
                // }

                $client->delete(); // Delete client record
            }
        }
    }

    //

    public static function validateStore(Request $request) 
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
            'tel_status'            =>  ["sometimes"],
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
            'NbrAutomaticCheckouts' =>  ["required_if:OpenCustomer,Ouvert", "max:255"]
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
    
            return $input->CustomerBarCode_image_original_name      !=  "";
        });
        
        $validator->sometimes(['facade_image'],  ["file"] , function (Fluent $input) {
    
            return $input->facade_image_original_name               !=  "";
        });

        $validator->sometimes(['in_store_image'],  ["required_if:OpenCustomer,Ouvert"] , function (Fluent $input) {
    
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
        $brandsArray            =   json_decode($request->input("AvailableBrands"), true);

        $AvailableBrands        =   [];
        foreach ($brandsArray as $index => $value) {
            $AvailableBrands["brand_$index"] = $value;
        }
        //

        //
        $client     =   new Client([
            'CustomerIdentifier'            =>  $request->input("CustomerIdentifier")                       ?? ''   ,
            'NewCustomer'                   =>  $request->input("NewCustomer")                              ?? ''   ,
            'OpenCustomer'                  =>  $request->input("OpenCustomer")                             ?? ''   ,
            'CustomerCode'                  =>  $request->input("CustomerCode")                             ?? ''   ,
            'CustomerNameE'                 =>  mb_strtoupper($request->input("CustomerNameE"), 'UTF-8')    ?? ''   ,
            'CustomerNameA'                 =>  mb_strtoupper($request->input("CustomerNameA"), 'UTF-8')    ?? ''   ,
            'Latitude'                      =>  $request->input("Latitude")                                 ?? ''   ,
            'Longitude'                     =>  $request->input("Longitude")                                ?? ''   ,
            'Address'                       =>  $request->input("Address")                                  ?? ''   ,
            'DistrictNo'                    =>  $request->input("DistrictNo")                               ?? ''   ,
            'DistrictNameE'                 =>  $request->input("DistrictNameE")                            ?? ''   ,
            'CityNo'                        =>  $request->input("CityNo")                                   ?? ''   ,
            'CityNameE'                     =>  $request->input("CityNameE")                                ?? ''   ,
            'Tel'                           =>  $request->input("Tel")                                      ?? ''   ,
            'CustomerType'                  =>  $request->input("CustomerType")                             ?? ''   ,

            'Neighborhood'                  =>  $request->input("Neighborhood")                             ?? ''   ,
            'Landmark'                      =>  $request->input("Landmark")                                 ?? ''   ,
            'BrandAvailability'             =>  $request->input("BrandAvailability")                        ?? ''   ,
            'BrandSourcePurchase'           =>  $request->input("BrandSourcePurchase")                      ?? ''   ,

            'Frequency'                     =>  $request->input("Frequency")                                ?? ''   ,
            'SuperficieMagasin'             =>  $request->input("SuperficieMagasin")                        ?? ''   ,
            'NbrAutomaticCheckouts'         =>  $request->input("NbrAutomaticCheckouts")                    ?? ''   ,
            'AvailableBrands'               =>  json_encode($AvailableBrands, JSON_UNESCAPED_UNICODE)       ?? ''   ,

            'start_adding_time'             =>  $start_adding_time                                          ?? ''   ,
            'adding_duration'               =>  $adding_duration                                            ?? ''   ,
            'comment'                       =>  $request->input("comment")                                  ?? ''   ,

            'id_route_import'               =>  $id_route_import                                            ?? ''   ,  

            'owner'                         =>  Auth::user()->id
        ]);

        if(!((Auth::user()->hasRole('FrontOffice'))||(Auth::user()->hasRole('Viewer')))) {

            $client->tel_status                     =   $request->get("tel_status")     ?? ''   ;
            $client->tel_comment                    =   $request->get("tel_comment")    ?? ''   ;
        }

        //

        $client->save();

        //

        if($request->input("JPlan") !=  null) {

            $client->JPlan      =   mb_strtoupper($request->input("JPlan"), 'UTF-8');
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

        //
        $AvailableBrands_AssocArray                     =   json_decode($client->AvailableBrands, true); // Convert JSON to associative array
        $client->AvailableBrands_array_formatted        =   array_values($AvailableBrands_AssocArray); // Extract values as an indexed array
        $client->AvailableBrands_string_formatted       =   implode(", ", $client->AvailableBrands_array_formatted);

        //
        return $client;
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

    public static function updateClient(Request $request, int $id_route_import, int $id) {

        $client                     =   Client::find($id);

        if($client) {

            if( ((Auth::user()->hasRole("FrontOffice"))&&($client->status ==  "validated"   ))                                              ||
                ((Auth::user()->hasRole("FrontOffice"))&&($client->status !=  "visible"     )&&($client->owner  !=  Auth::user()->id)))     {

                throw new Exception("Unauthorized", 403);
            }

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
            $client->CustomerNameE                  =   mb_strtoupper($request->get("CustomerNameE"), 'UTF-8')  ?? ''   ;
            $client->CustomerNameA                  =   mb_strtoupper($request->get("CustomerNameA"), 'UTF-8')  ?? ''   ;
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
            $client->AvailableBrands                =   json_encode($AvailableBrands, JSON_UNESCAPED_UNICODE)           ;

            $client->comment                        =   $request->input("comment")                              ?? ''   ;

            $client->id_route_import                =   $id_route_import                                                ;

            if(Auth::user()->hasRole('FrontOffice')) {

                $client->owner                          =   Auth::user()->id;
            }

            else {

                $client->owner                          =   $request->get("owner");
                $client->tel_status                     =   $request->get("tel_status");
                $client->tel_comment                    =   $request->get("tel_comment");
            }

            //

            if($request->input("JPlan") !=  null) {

                $client->JPlan      =   mb_strtoupper($request->input("JPlan"), 'UTF-8');
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

    public static function showClient(Request $request, int $id_route_import, int $id) {

        $client =   Client::find($id);
        
        if($client) {
            
            $user   =   User::find($client->id);
            
            if($user) {
                
                $client->owner_name                         =   $user->nom;
            }            

            //
            $AvailableBrands_AssocArray                     =   json_decode($client->AvailableBrands, true); // Convert JSON to associative array
            $client->AvailableBrands_array_formatted        =   array_values($AvailableBrands_AssocArray); // Extract values as an indexed array
            $client->AvailableBrands_string_formatted       =   implode(", ", $client->AvailableBrands_array_formatted);
        }
        
        return $client;
    }

    //

    public static function validateClient(int $id_route_import, int $id) {

        $client                     =   Client::find($id);

        if($client) {

            $client->status         =   "validated";
            $client->owner          =   Auth::user()->id;

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
    
                $client->JPlan                      =   mb_strtoupper($client_tempo->JPlan, 'UTF-8');
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

            //
            $client->save();
        }
    }

    //

    public static function updateClients(Request $request, int $id_route_import) {

        $clients    =   json_decode($request->get("data"));

        foreach ($clients as $client_tempo) {

            // Client
            $client                             =   Client::find($client_tempo->id);

            $client->NewCustomer                =   $client_tempo->NewCustomer;
            $client->CustomerIdentifier         =   $client_tempo->CustomerIdentifier;
            $client->CustomerCode               =   $client_tempo->CustomerCode;
            $client->OpenCustomer               =   $client_tempo->OpenCustomer;
            $client->CustomerNameE              =   mb_strtoupper($client_tempo->CustomerNameE, 'UTF-8');
            $client->CustomerNameA              =   mb_strtoupper($client_tempo->CustomerNameA, 'UTF-8');
            $client->Latitude                   =   $client_tempo->Latitude;
            $client->Longitude                  =   $client_tempo->Longitude;
            $client->Address                    =   $client_tempo->Address;
            $client->DistrictNo                 =   $client_tempo->DistrictNo;
            $client->DistrictNameE              =   $client_tempo->DistrictNameE;
            $client->CityNo                     =   $client_tempo->CityNo;
            $client->CityNameE                  =   $client_tempo->CityNameE;
            $client->Tel                        =   $client_tempo->Tel;
            $client->CustomerType               =   $client_tempo->CustomerType;

            $client->Frequency                  =   $client_tempo->Frequency;
            $client->SuperficieMagasin          =   $client_tempo->SuperficieMagasin;
            $client->NbrAutomaticCheckouts      =   $client_tempo->NbrAutomaticCheckouts;

            $client->Neighborhood               =   $client_tempo->Neighborhood;
            $client->Landmark                   =   $client_tempo->Landmark;
            $client->BrandAvailability          =   $client_tempo->BrandAvailability;
            $client->BrandSourcePurchase        =   $client_tempo->BrandSourcePurchase;

            if($client_tempo->JPlan     !=  null) {
    
                $client->JPlan                      =   mb_strtoupper($client_tempo->JPlan, 'UTF-8');
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

        // $directory  =   public_path('uploads/clients/'.$client->id);

        // if (File::exists($directory)) {

        //     File::deleteDirectory($directory);
        // }

        $client->delete();
    }

    //

    public static function clientsExport(Request $request) {

        if($request->get("status") ==  "All") {

            $clients    =   Client::where("id_route_import", $request->get("id_route_import"))
                                ->select("clients.*", "users.nom as owner")
                                ->join("users", "clients.owner", "users.id")
                                ->orderBy('clients.id', 'desc')
                                ->get()
                                ->makeHidden(['id_route_import']);

            //
            foreach ($clients as $client) {

                $AvailableBrands_AssocArray                 =   json_decode($client->AvailableBrands, true); // Convert JSON to associative array
                $client->AvailableBrands_array_formatted    =   array_values($AvailableBrands_AssocArray); // Extract values as an indexed array
                $client->AvailableBrands_string_formatted   =   implode(", ", $client->AvailableBrands_array_formatted);
            }
        }

        if($request->get("status") ==  "Validated") {

            $clients    =   Client::where([["status", "validated"]    , ["id_route_import", $request->get("id_route_import")]])
                                ->select("clients.*", "users.nom as owner")
                                ->join("users", "clients.owner", "users.id")
                                ->orderBy('clients.id', 'desc')
                                ->get()
                                ->makeHidden(['id_route_import']);

            //
            foreach ($clients as $client) {

                $AvailableBrands_AssocArray                 =   json_decode($client->AvailableBrands, true); // Convert JSON to associative array
                $client->AvailableBrands_array_formatted    =   array_values($AvailableBrands_AssocArray); // Extract values as an indexed array
                $client->AvailableBrands_string_formatted   =   implode(", ", $client->AvailableBrands_array_formatted);
            }
        }

        if($request->get("status") ==  "Pending") {

            $clients    =   Client::where([["status", "pending"]      , ["id_route_import", $request->get("id_route_import")]])
                                ->select("clients.*", "users.nom as owner")
                                ->join("users", "clients.owner", "users.id")
                                ->orderBy('clients.id', 'desc')
                                ->get()
                                ->makeHidden(['id_route_import']);

            //
            foreach ($clients as $client) {

                $AvailableBrands_AssocArray                 =   json_decode($client->AvailableBrands, true); // Convert JSON to associative array
                $client->AvailableBrands_array_formatted    =   array_values($AvailableBrands_AssocArray); // Extract values as an indexed array
                $client->AvailableBrands_string_formatted   =   implode(", ", $client->AvailableBrands_array_formatted);
            }
        }

        if($request->get("status") ==  "NonValidated") {

            $clients    =   Client::where([["status", "nonvalidated"] , ["id_route_import", $request->get("id_route_import")]])
                                ->select("clients.*", "users.nom as owner")
                                ->join("users", "clients.owner", "users.id")
                                ->orderBy('clients.id', 'desc')
                                ->get()
                                ->makeHidden(['id_route_import']);

            //
            foreach ($clients as $client) {

                $AvailableBrands_AssocArray                 =   json_decode($client->AvailableBrands, true); // Convert JSON to associative array
                $client->AvailableBrands_array_formatted    =   array_values($AvailableBrands_AssocArray); // Extract values as an indexed array
                $client->AvailableBrands_string_formatted   =   implode(", ", $client->AvailableBrands_array_formatted);
            }
        }

        if($request->get("status") ==  "Visible") {

            $clients    =   Client::where([["status", "visible"]    , ["id_route_import", $request->get("id_route_import")]])
                                ->select("clients.*", "users.nom as owner")
                                ->join("users", "clients.owner", "users.id")
                                ->orderBy('clients.id', 'desc')
                                ->get()
                                ->makeHidden(['id_route_import']);

            //
            foreach ($clients as $client) {

                $AvailableBrands_AssocArray                 =   json_decode($client->AvailableBrands, true); // Convert JSON to associative array
                $client->AvailableBrands_array_formatted    =   array_values($AvailableBrands_AssocArray); // Extract values as an indexed array
                $client->AvailableBrands_string_formatted   =   implode(", ", $client->AvailableBrands_array_formatted);
            }
        }

        if($request->get("status") ==  "ferme") {

            $clients    =   Client::where([["status", "ferme"]      , ["id_route_import", $request->get("id_route_import")]])
                                ->select("clients.*", "users.nom as owner")
                                ->join("users", "clients.owner", "users.id")
                                ->orderBy('clients.id', 'desc')
                                ->get()
                                ->makeHidden(['id_route_import']);

            //
            foreach ($clients as $client) {

                $AvailableBrands_AssocArray                 =   json_decode($client->AvailableBrands, true); // Convert JSON to associative array
                $client->AvailableBrands_array_formatted    =   array_values($AvailableBrands_AssocArray); // Extract values as an indexed array
                $client->AvailableBrands_string_formatted   =   implode(", ", $client->AvailableBrands_array_formatted);
            }
        }

        return $clients;
    }

    //

    public static function getDoublesClients(Request $request, int $id_route_import) {

        $getDoublant    =   new stdClass();

        $getDoublant->getDoublantCustomerCode           =   Client::getDoublesCustomerCodeClients($request, $id_route_import);
        $getDoublant->getDoublantCustomerNameE          =   Client::getDoublesCustomerNameEClients($request, $id_route_import);
        $getDoublant->getDoublantTel                    =   Client::getDoublesTelClients($request, $id_route_import);
        $getDoublant->getDoublantGPS                    =   Client::getDoublesGPSClients($request, $id_route_import);

        return $getDoublant;
    }

    public static function getDoublesCustomerCodeClients(Request $request, int $id_route_import) {

        $clients    =   DB::table('clients')

                            ->join('users', 'clients.owner', 'users.id')

                            ->when(
                                $request->filled('start_date') && $request->filled('end_date'),
                                function ($q) use ($request) {
                                    $q->whereBetween(
                                        DB::raw('STR_TO_DATE(clients.created_at, "%d %M %Y")'),
                                        [$request->get("start_date"), $request->get("end_date")]
                                    );
                                }
                            )

                            ->where([['id_route_import', $id_route_import], ['clients.status', 'validated']])

                            ->whereIn('clients.CustomerCode', function ($query) use ($request, $id_route_import) {
                                $query->select('CustomerCode')
                                    ->from('clients')

                                    ->where([['id_route_import', $id_route_import], ['clients.status', 'validated']])
                                    ->groupBy('CustomerCode')
                                    ->havingRaw('COUNT(*) > 1');
                            })

                            ->select('clients.*', 'users.nom as owner_name')

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

    public static function getDoublesCustomerNameEClients(Request $request, int $id_route_import) {

        $clients    =   DB::table('clients')

                            ->join('users', 'clients.owner', 'users.id')

                            ->when(
                                $request->filled('start_date') && $request->filled('end_date'),
                                function ($q) use ($request) {
                                    $q->whereBetween(
                                        DB::raw('STR_TO_DATE(clients.created_at, "%d %M %Y")'),
                                        [$request->get("start_date"), $request->get("end_date")]
                                    );
                                }
                            )

                            ->where([['id_route_import', $id_route_import], ['clients.status', 'validated']])

                            ->whereIn('clients.CustomerNameE', function ($query) use ($request, $id_route_import) {
                                $query->select('CustomerNameE')
                                    ->from('clients')

                                    ->where([['id_route_import', $id_route_import], ['clients.status', 'validated']])
                                    ->groupBy('CustomerNameE')
                                    ->havingRaw('COUNT(*) > 1');
                            })

                            ->select('clients.*', 'users.nom as owner_name')

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

    public static function getDoublesTelClients(Request $request, int $id_route_import) {

        $clients    =   DB::table('clients')

                            ->join('users', 'clients.owner', 'users.id')

                            ->when(
                                $request->filled('start_date') && $request->filled('end_date'),
                                function ($q) use ($request) {
                                    $q->whereBetween(
                                        DB::raw('STR_TO_DATE(clients.created_at, "%d %M %Y")'),
                                        [$request->get("start_date"), $request->get("end_date")]
                                    );
                                }
                            )

                            ->where([['id_route_import', $id_route_import], ['clients.status', 'validated']])
                            ->whereIn('clients.Tel', function ($query) use ($request, $id_route_import) {
                                $query->select('Tel')
                                    ->from('clients')

                                    ->where([['id_route_import', $id_route_import], ['clients.status', 'validated']])
                                    ->groupBy('Tel')
                                    ->havingRaw('COUNT(*) > 1');
                            })

                            ->select('clients.*', 'users.nom as owner_name')

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

    public static function getDoublesGPSClients(Request $request, int $id_route_import) {

        $clients    =   DB::table('clients')

                            ->join('users', 'clients.owner', 'users.id')

                            ->when(
                                $request->filled('start_date') && $request->filled('end_date'),
                                function ($q) use ($request) {
                                    $q->whereBetween(
                                        DB::raw('STR_TO_DATE(clients.created_at, "%d %M %Y")'),
                                        [$request->get("start_date"), $request->get("end_date")]
                                    );
                                }
                            )

                            ->where([['id_route_import', $id_route_import], ['clients.status', 'validated']])

                            ->whereIn(DB::raw('(clients.Latitude, clients.Longitude)'), function ($query) use ($request, $id_route_import) {
                                $query->select('Latitude', 'Longitude')
                                    ->from('clients')

                                    ->where([['id_route_import', $id_route_import], ['clients.status', 'validated']])
                                    ->groupBy('Latitude', 'Longitude')
                                    ->havingRaw('COUNT(*) > 1');
                            })

                            ->select('clients.*', 'users.nom as owner_name')

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