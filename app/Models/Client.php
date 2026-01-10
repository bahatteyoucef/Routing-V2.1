<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

use Illuminate\Support\Fluent;

class Client extends Model
{
    use HasFactory;

    protected $guarded      =   [];

    protected $table        =   'clients';
    protected $primaryKey   =   'id';

    public    $timestamps   =   false;

    //  //  //  //  //
    //  //  //  //  //  Casts
    //  //  //  //  //

    protected $casts = [
        'AvailableBrands' => 'array',
    ];

    //  //  //  //  //
    //  //  //  //  //  Boot
    //  //  //  //  //

    protected static function boot() {
        parent::boot();

        //        
        $userId         =   Auth::check() ? Auth::user()->id : null;
        $isFrontOffice  =   Auth::check() && Auth::user()->hasRole('FrontOffice');

        static::creating(function ($model) use ($userId, $isFrontOffice) {
            //
            if (empty($model->created_at)) {
                // WARNING: Storing 'd F Y' in a DATETIME field will fail. Assuming the field is a STRING.
                $model->created_at = Carbon::now()->format('d F Y'); 
            }
            $model->updated_at = Carbon::now()->format('Y-m-d H:i:s');
            
            // Use the pre-calculated variable
            if (!$isFrontOffice) {
                $model->owner_bo = $userId;
            }
        });

        static::updating(function ($model) use ($userId, $isFrontOffice) {
            // Laravel's default timestamp handling covers this, but we keep it if you need the custom format
            $model->updated_at = Carbon::now()->format('Y-m-d H:i:s');
            
            // Use the pre-calculated variable
            if (!$isFrontOffice) {
                $model->owner_bo = $userId;
            }
        });
    }

    //  //  //  //  //
    //  //  //  //  //  Relationships
    //  //  //  //  //

    public function ownerUser()
    {
        return $this->belongsTo(User::class, 'owner');
    }

    //  //  //  //  //
    //  //  //  //  //  Store/Update/Delete/Show Client
    //  //  //  //  //

    public static function validateUpdate(Request $request) {

        $validator = Validator::make($request->all(), [
            'NewCustomer'           =>  ["required", "max:255"],
            'OpenCustomer'          =>  ["required", "max:255"],
            'CustomerIdentifier'    =>  ["required", "max:255"],
            'CustomerCode'          =>  ["required_if:OpenCustomer,Ouvert", "max:255", function ($attribute, $value, $fail) {
                if (preg_match('/[\/\\\\:*?"<>|& ]/', $value)) {
                    $fail("Le champ $attribute contient des caractères interdits.");
                }
            }],
            // 'CustomerNameE'         =>  ["required_if:OpenCustomer,Ouvert", "max:255"],
            // 'CustomerNameA'         =>  ["required", "max:255"],
            // 'Tel'                   =>  ["required_if:OpenCustomer,Ouvert", "max:255"],
            'Latitude'              =>  ["required", "max:255"],
            'Longitude'             =>  ["required", "max:255"],
            // 'Address'               =>  ["required", "max:255"],
            // 'RvrsGeoAddress'        =>  ["required", "max:255"],
            'DistrictNo'            =>  ["required", "max:255"],
            'DistrictNameE'         =>  ["required", "max:255"],
            'CityNo'                =>  ["required", "max:255"],
            'CityNameE'             =>  ["required", "max:255"],
            // 'CustomerType'          =>  ["required", "max:255"],
            'status'                =>  ["required", "max:255"],
            // 'Frequency'             =>  ["required", "max:255"],
            // 'SuperficieMagasin'     =>  ["required_if:OpenCustomer,Ouvert", "max:255"],
            // 'NbrAutomaticCheckouts' =>  ["required_if:OpenCustomer,Ouvert", "max:255"],
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

    public static function updateClient(Request $request, int $id_route_import, int $id_client) {
        $client = Client::find($id_client);
        if (!$client) return null;

        // Security Check
        $user = Auth::user();
        $isFrontOffice = $user->hasRole("FrontOffice");
        
        if ($isFrontOffice && ($client->status == "validated" || $client->status == "confirmed" || ($client->status != "visible" && $client->owner != $user->id))) {
            throw new Exception("Unauthorized", 403);
        }

        // 1. Formatting Brands
        $brandsArray = json_decode($request->input("AvailableBrands"), true) ?? [];
        $formattedBrands = [];
        foreach ($brandsArray as $index => $value) {
            $formattedBrands["brand_$index"] = $value;
        }

        // 2. Mass Assignment (Fill simple fields)
        $client->fill($request->only([
            'NewCustomer', 'OpenCustomer', 'CustomerIdentifier', 'CustomerCode',
            'Latitude', 'Longitude', 'Address', 'RvrsGeoAddress', 'DistrictNo', 'DistrictNameE',
            'CityNo', 'CityNameE', 'Tel', 'CustomerType', 'Neighborhood', 
            'Landmark', 'BrandAvailability', 'BrandSourcePurchase', 'Frequency', 
            'SuperficieMagasin', 'NbrAutomaticCheckouts', 'comment', 'status', 
            'nonvalidated_details'
        ]));

        // 3. Set Complex Fields
        $client->CustomerNameE = mb_strtoupper($request->get("CustomerNameE") ?? '', 'UTF-8');
        $client->CustomerNameA = mb_strtoupper($request->get("CustomerNameA") ?? '', 'UTF-8');
        $client->AvailableBrands = json_encode($formattedBrands, JSON_UNESCAPED_UNICODE);
        $client->id_route_import = $id_route_import;
        
        // JPlan Logic
        $client->JPlan = $request->has("JPlan") ? mb_strtoupper($request->input("JPlan"), 'UTF-8') : "";
        $client->Journee = $request->input("Journee") ?? "";

        // Owner Logic
        if (Auth::user()->hasRole('FrontOffice')) {
            $client->owner = Auth::user()->id;
            $client->tel_status = 'pending';
            $client->tel_comment = '';
        } else {
            $client->owner = $request->get("owner");
            $client->tel_status = $request->get("tel_status");
            $client->tel_comment = $request->get("tel_comment");
        }

        // 4. IMAGE HANDLING (Reuse the helper!)
        self::handleClientImageUpload($request, $client, 'CustomerBarCode_image');
        self::handleClientImageUpload($request, $client, 'facade_image');
        self::handleClientImageUpload($request, $client, 'in_store_image');

        // 5. Handle Original Names (Simple string assignment)
        $client->CustomerBarCode_image_original_name = $request->input("CustomerBarCode_image_original_name") ?? "";
        $client->facade_image_original_name          = $request->input("facade_image_original_name") ?? "";
        $client->in_store_image_original_name        = $request->input("in_store_image_original_name") ?? "";

        // 6. Save
        $client->save();

        // 7. Format Response
        return self::appendFormattedBrands($client);
    }

    public static function validateStore(Request $request) {

        $validator = Validator::make($request->all(), [
            'NewCustomer'           =>  ["required", "max:255"],
            'OpenCustomer'          =>  ["required", "max:255"],
            'CustomerIdentifier'    =>  ["required", "max:255"],
            'CustomerCode'          =>  ["required_if:OpenCustomer,Ouvert", "max:255", function ($attribute, $value, $fail) {
                if (preg_match('/[\/\\\\:*?"<>|& ]/', $value)) {
                    $fail("Le champ $attribute contient des caractères interdits.");
                }
            }],
            // 'CustomerNameE'         =>  ["required_if:OpenCustomer,Ouvert", "max:255"],
            // 'CustomerNameA'         =>  ["required", "max:255"],
            // 'Tel'                   =>  ["required_if:OpenCustomer,Ouvert", "max:255"],
            // 'tel_status'            =>  ["sometimes"],
            'Latitude'              =>  ["required", "max:255"],
            'Longitude'             =>  ["required", "max:255"],
            // 'Address'               =>  ["required", "max:255"],
            // 'RvrsGeoAddress'        =>  ["required", "max:255"],
            'DistrictNo'            =>  ["required", "max:255"],
            'DistrictNameE'         =>  ["required", "max:255"],
            'CityNo'                =>  ["required", "max:255"],
            'CityNameE'             =>  ["required", "max:255"],
            // 'CustomerType'          =>  ["required", "max:255"],
            'status'                =>  ["required", "max:255"],
            // 'Frequency'             =>  ["required", "max:255"],
            // 'SuperficieMagasin'     =>  ["required_if:OpenCustomer,Ouvert", "max:255"],
            // 'NbrAutomaticCheckouts' =>  ["required_if:OpenCustomer,Ouvert", "max:255"]
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

        // --- Optimization 1: Consolidate Date/Time Calculations ---
        $start_adding_date      =   Carbon::parse($request->get("start_adding_date"));
        $finish_adding_date     =   Carbon::parse($request->get("finish_adding_date"));
        $adding_duration        =   $start_adding_date->diff($finish_adding_date)->format('%H:%I:%S');
        $start_adding_time      =   $start_adding_date->format('H:i:s');
        
        // --- Optimization 2: Simplify Brand Mapping ---
        $brandsArray            =   json_decode($request->input("AvailableBrands"), true) ?? [];
        $AvailableBrands        =   array_combine(
            array_map(fn($index)    =>  "brand_$index", array_keys($brandsArray)),
            $brandsArray
        );
        $AvailableBrandsJson    =   json_encode($AvailableBrands, JSON_UNESCAPED_UNICODE);
        
        // --- Optimization 3: Mass Assignment (Single save at the end) ---
        $client                 =   new Client([
            'CustomerIdentifier'            =>  $request->input("CustomerIdentifier")                       ?? ''   ,
            'NewCustomer'                   =>  $request->input("NewCustomer")                              ?? ''   ,
            'OpenCustomer'                  =>  $request->input("OpenCustomer")                             ?? ''   ,
            'CustomerCode'                  =>  $request->input("CustomerCode")                             ?? ''   ,
            'CustomerNameE'                 =>  mb_strtoupper($request->input("CustomerNameE"), 'UTF-8')    ?? ''   ,
            'CustomerNameA'                 =>  mb_strtoupper($request->input("CustomerNameA"), 'UTF-8')    ?? ''   ,
            'Latitude'                      =>  $request->input("Latitude")                                 ?? ''   ,
            'Longitude'                     =>  $request->input("Longitude")                                ?? ''   ,
            'Address'                       =>  $request->input("Address")                                  ?? ''   ,
            'RvrsGeoAddress'                =>  $request->input("RvrsGeoAddress")                           ?? ''   ,
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

            'AvailableBrands'               =>  $AvailableBrandsJson                                                ,
            'start_adding_time'             =>  $start_adding_time                                                  , // Use calculated value
            'adding_duration'               =>  $adding_duration                                                    , // Use calculated value
            'comment'                       =>  $request->input("comment")                                  ?? ''   ,
            'id_route_import'               =>  $id_route_import                                                    ,
            'owner'                         =>  Auth::user()->id                                                    ,
            'JPlan'                         =>  mb_strtoupper($request->input("JPlan")                      ?? ''   , 'UTF-8'),
            'Journee'                       =>  $request->input("Journee")                                  ?? ''   ,
            'status'                        =>  $request->input("status")                                   ?? ''   ,
            'nonvalidated_details'          =>  $request->input("nonvalidated_details")                     ?? ''   ,
            'tel_status'                    =>  'pending'                                                   ?? ''   , // Assuming these are NOT in the mass assignment $fillable array
            'tel_comment'                   =>  ''                                                          ?? ''   ,

            'facade_image_original_name'           => $request->input("facade_image_original_name")             ?? '',
            'CustomerBarCode_image_original_name'  => $request->input("CustomerBarCode_image_original_name")    ?? '',
            'in_store_image_original_name'         => $request->input("in_store_image_original_name")           ?? '',
        ]);
        
        // Apply role-specific overrides after mass assignment
        if (!((Auth::user()->hasRole('FrontOffice')) || (Auth::user()->hasRole('Viewer')))) {
            // If not FrontOffice/Viewer, allow setting these
            $client->tel_status     =   $request->get("tel_status")     ?? $client->tel_status; 
            $client->tel_comment    =   $request->get("tel_comment")    ?? $client->tel_comment;
        }

        $client->save(); // The first save is crucial to get the client->id for file paths!

        // --- Optimization 4: Use Helper for Image Uploads ---
        self::handleClientImageUpload($request, $client, 'CustomerBarCode_image');
        self::handleClientImageUpload($request, $client, 'facade_image');
        self::handleClientImageUpload($request, $client, 'in_store_image');
        
        // The model is now updated with the final file paths. Save changes.
        $client->save(); 

        // --- Optimization 5: Move data formatting outside the model, or use accessors/mutators ---
        // The final data formatting should be handled by an API resource or accessor, not inside the function.        
        return $client;
    }

    public static function deleteClient(int $id_route_import, int $id_client) {
        // If you need file deletion, uncomment the directory logic here
        Client::destroy($id_client);
    }

    public static function showClient(Request $request, int $id_route_import, int $id_client) {
        // Eager load 'owner' relationship if 'User' model is related via 'owner' field
        $client = Client::with('ownerUser')->find($id_client); 
        
        if ($client) {
            // Assuming you have a relationship defined in Client model: public function ownerUser() { return $this->belongsTo(User::class, 'owner'); }
            if ($client->ownerUser) {
                $client->owner_username = $client->ownerUser->username;
            }
            
            // Use helper to format brands
            self::appendFormattedBrands($client);
        }
        
        return $client;
    }

    //  //  //  //  //
    //  //  //  //  //  Store/Update/Delete Clients
    //  //  //  //  //

    /*

    public static function storeClients(Request $request, int $id_route_import) {

        // Assume clients is a JSON string/array, not an array of objects for simplicity.
        // If it's an array of objects, convert it using array_map or json_decode(true).
        $clientsData    =   json_decode($request->get('clients'), true);
        if (empty($clientsData)) return;

        $now            =   Carbon::now();
        $createdAt      =   $now->format('d F Y'); // Custom format from boot()
        $updatedAt      =   $now->format('Y-m-d H:i:s');
        $ownerBo        =   Auth::check() && !Auth::user()->hasRole('FrontOffice') ? Auth::user()->id : null;
        
        $dataToInsert   =   [];

        DB::transaction(function () use ($clientsData, $id_route_import, $createdAt, $updatedAt, $ownerBo, &$dataToInsert) {
            foreach ($clientsData as $client_elem) {
                // 1. Validation and Error Check (Moved to top for efficiency)
                if (isset($client_elem['CustomerCode']) && preg_match('/[\/\\\\:*?"<>|& ]/', $client_elem['CustomerCode'])) {
                    // Must throw here as per original logic, stops the batch insert.
                    throw new \Exception("Le champ CustomerCode contient des caractères interdits : ".$client_elem['CustomerCode']);
                }
                
                // 2. Data Transformation (using null coalescing ?? and strtoupper)
                $dataToInsert[] = [
                    'NewCustomer'               =>  $client_elem['NewCustomer']                             ??  ''              ,
                    'CustomerIdentifier'        =>  $client_elem['CustomerIdentifier']                      ??  ''              ,
                    'CustomerCode'              =>  $client_elem['CustomerCode']                            ??  ''              ,
                    'OpenCustomer'              =>  $client_elem['OpenCustomer']                            ??  ''              ,
                    'CustomerNameE'             =>  mb_strtoupper($client_elem['CustomerNameE'], 'UTF-8')   ??  ''              ,
                    'CustomerNameA'             =>  mb_strtoupper($client_elem['CustomerNameA'], 'UTF-8')   ??  ''              ,
                    'Latitude'                  =>  $client_elem['Latitude']                                ??  0               ,
                    'Longitude'                 =>  $client_elem['Longitude']                               ??  0               ,
                    'Address'                   =>  $client_elem['Address']                                 ??  ''              ,
                    'RvrsGeoAddress'            =>  $client_elem['RvrsGeoAddress']                          ??  ''              ,
                    'DistrictNo'                =>  $client_elem['DistrictNo']                              ??  ''              ,
                    'DistrictNameE'             =>  $client_elem['DistrictNameE']                           ??  ''              ,
                    'CityNo'                    =>  $client_elem['CityNo']                                  ??  ''              ,
                    'CityNameE'                 =>  $client_elem['CityNameE']                               ??  ''              ,
                    'Tel'                       =>  $client_elem['Tel']                                     ??  ''              ,
                    'tel_status'                =>  $client_elem['tel_status']                              ??  ''              ,
                    'tel_comment'               =>  $client_elem['tel_comment']                             ??  ''              ,
                    'CustomerType'              =>  $client_elem['CustomerType']                            ??  ''              ,

                    'status'                    =>  $client_elem['status']                                  ??  ''              ,

                    'Neighborhood'              =>  $client_elem['Neighborhood']                            ??  ''              ,
                    'Landmark'                  =>  $client_elem['Landmark']                                ??  ''              ,
                    'BrandAvailability'         =>  $client_elem['BrandAvailability']                       ??  ''              ,
                    'BrandSourcePurchase'       =>  $client_elem['BrandSourcePurchase']                     ??  ''              ,

                    'Frequency'                 =>  $client_elem['Frequency']                               ??  ''              ,
                    'SuperficieMagasin'         =>  $client_elem['SuperficieMagasin']                       ??  ''              ,
                    'NbrAutomaticCheckouts'     =>  $client_elem['NbrAutomaticCheckouts']                   ??  ''              ,

                    'AvailableBrands'           =>  $client_elem['AvailableBrands']                         ??  ''              ,

                    'start_adding_time'         =>  $client_elem['start_adding_time']                       ??  ''              ,
                    'adding_duration'           =>  $client_elem['adding_duration']                         ??  ''              ,

                    'JPlan'                     =>  $client_elem['JPlan']                                   ??  ''              ,
                    'Journee'                   =>  $client_elem['Journee']                                 ??  ''              ,

                    'comment'                   =>  $client_elem['comment']                                 ??  ''              ,
                    
                    // Manually include the event/auth data
                    'created_at'                =>  $client_elem['created_at']                              ??  $createdAt      ,
                    'updated_at'                =>  $updatedAt                                                                  , // set on insert
                    'owner_bo'                  =>  $ownerBo                                                                    ,
                    'id_route_import'           =>  $id_route_import                                                            ,
                    'owner'                     =>  $client_elem['owner']                                   ??  (Auth::check() ? Auth::user()->id : null) // Assuming 'owner' is the request owner field
                ];
            }

            // 3. Batch Insert (Use chunking for large datasets, e.g., 500 records per query)
            $chunks     =   array_chunk($dataToInsert, 500);
            foreach ($chunks as $chunk) {
                Client::insert($chunk); // Use insert() which is much faster than save()
            }
        });
    }

    */

    public static function updateClients(Request $request, int $id_route_import) {
        // Ensure clients is decoded as an associative array for consistency
        $clientsToUpdate    =   json_decode($request->get("clients"), true); 
        if (empty($clientsToUpdate)) return;

        $createdAt          =   Carbon::now()->format('d F Y');
        $updatedAt          =   Carbon::now()->format('Y-m-d H:i:s');
        $ownerBo            =   Auth::check() && !Auth::user()->hasRole('FrontOffice') ? Auth::user()->id : null;

        $dataToUpsert       =   [];
        $updateColumns      =   ['created_at', 'updated_at', 'owner_bo'];
        
        foreach ($clientsToUpdate as $client_elem) {
            $row    =   [
                'id'            =>  $client_elem['id']  ,
                'created_at'    =>  $createdAt          ,
                'updated_at'    =>  $updatedAt          ,
                'owner_bo'      =>  $ownerBo            , // Update owner_bo on modification
            ];
            
            // Add optional fields and track which ones need to be updated
            if (isset($client_elem['owner'])) {
                $row['owner']   =   $client_elem['owner'];

                if (!in_array('owner', $updateColumns))         $updateColumns[]    =   'owner';
            }

            if (isset($client_elem['JPlan'])) {
                $row['JPlan']   =   mb_strtoupper($client_elem['JPlan'], 'UTF-8');
                if (!in_array('JPlan', $updateColumns))         $updateColumns[]    =   'JPlan';
            }

            if (isset($client_elem['DistrictNo']) && isset($client_elem['DistrictNameE'])) {
                $row['DistrictNo']      =   $client_elem['DistrictNo'];
                $row['DistrictNameE']   =   $client_elem['DistrictNameE'];

                if (!in_array('DistrictNo'      , $updateColumns)) $updateColumns[] =   'DistrictNo';
                if (!in_array('DistrictNameE'   , $updateColumns)) $updateColumns[] =   'DistrictNameE';
            }

            if (isset($client_elem['CityNo']) && isset($client_elem['CityNameE'])) {
                $row['CityNo']      =   $client_elem['CityNo'];
                $row['CityNameE']   =   $client_elem['CityNameE'];

                if (!in_array('CityNo'      , $updateColumns))  $updateColumns[]    =   'CityNo';
                if (!in_array('CityNameE'   , $updateColumns))  $updateColumns[]    =   'CityNameE';
            }

            if (isset($client_elem['CustomerType'])) {
                $row['CustomerType']    =   $client_elem['CustomerType'];
                if (!in_array('CustomerType', $updateColumns))  $updateColumns[]    =   'CustomerType';
            }

            if (isset($client_elem['status'])) {
                $row['status']          =   $client_elem['status'];
                if (!in_array('status', $updateColumns))        $updateColumns[]    =   'status';
            }

            if (isset($client_elem['Journee'])) {
                $row['Journee']         =   $client_elem['Journee'];
                if (!in_array('Journee', $updateColumns))       $updateColumns[]    =   'Journee';
            }

            $dataToUpsert[]     =   $row;
        }

        // --- Batch Update ---
        $chunks = array_chunk($dataToUpsert, 500);
        foreach ($chunks as $chunk) {
            Client::upsert(
                $chunk,
                ['id'], // Unique by ID (Primary Key)
                // Use the dynamically built list of columns to update
                array_unique($updateColumns) 
            );
        }
    }

    public static function deleteClients(Request $request, int $id_route_import) {
        $liste_clients_ids  =   json_decode($request->get("clients"), true);
        if (empty($liste_clients_ids)) return;

        // --- Optimization 1: Efficient Bulk Fetch for File Cleanup ---
        // Fetch only the IDs (or just the ID and the data needed for the directory path)
        // We still need the IDs for file cleanup
        $clientsToDelete    =   Client::whereIn('id', $liste_clients_ids)->get(['id']);
                    
        $ids                =   $clientsToDelete->pluck('id')->toArray();
        
        // 1. Delete all database records in one query
        Client::whereIn('id', $ids)->delete(); // One efficient query!

        // 2. Handle File Deletion (less critical, but still necessary)
        // Note: File operations are slow and should not be in the DB transaction if possible.
        // Since the original logic had it inside, we keep it here but acknowledge it's slow.
        // foreach ($clientsToDelete as $client) {
        //     $directory = public_path('uploads/clients/' . $client->id);
        //     if (File::exists($directory)) {
        //         File::deleteDirectory($directory); 
        //     }
        // }
    }

    //  //  //  //  //
    //  //  //  //  //  Update Resume
    //  //  //  //  // 

    public static function updateResumeClients(Request $request) {

        $clientsData    =   json_decode($request->get("data"), true);
        if (empty($clientsData)) return;

        $chunkSize      =   1000;
        
        // Pre-format dates exactly as you had them in your boot() method
        $createdAt      =   Carbon::now()->format('d F Y'); 
        $updatedAt      =   Carbon::now()->format('Y-m-d H:i:s');

        $chunks = array_chunk($clientsData, $chunkSize);

        foreach ($chunks as $chunk) {
            $upsertData     =   [];

            foreach ($chunk as $clientData) {
                // Logic for JPlan
                $jPlan      =   !empty($clientData['JPlan']) 
                                ? mb_strtoupper($clientData['JPlan'], 'UTF-8') 
                                : "";

                // Logic for Journee
                $journee    =   !empty($clientData['Journee']) 
                                ? mb_strtoupper($clientData['Journee'], 'UTF-8') 
                                : "";

                // Build the row
                $row = [
                    'id'            =>  $clientData['id'],
                    'JPlan'         =>  $jPlan,
                    'Journee'       =>  $journee,

                    // Manually add the timestamps and owner
                    'owner_bo'      =>  Auth::user()->id,
                    'created_at'    =>  $createdAt, 
                    'updated_at'    =>  $updatedAt,
                ];

                //
                $upsertData[] = $row;
            }

            // 2. Perform Upsert
            Client::upsert(
                $upsertData ,
                ['id']      , // Unique column
                
                ['JPlan', 'Journee', 'updated_at', 'owner_bo']
            );
        }
    }

    //  //  //  //  //
    //  //  //  //  //  Export Clients
    //  //  //  //  //

    public static function clientsExport(Request $request) {
        $status = $request->get("status");
        $routeId = $request->get("id_route_import");

        // 1. Build Query
        $query = Client::where("id_route_import", $routeId)
            ->with('ownerUser') // Use Eager Loading
            ->orderBy('id', 'desc');

        // 2. Apply Filters
        $query->when($status !== "All", function ($q) use ($status) {
            // Map frontend status strings to DB values if they differ, otherwise just use $status
            $dbStatus = match(strtolower($status)) {
                'validated' => 'validated',
                'pending' => 'pending',
                'nonvalidated' => 'nonvalidated',
                'visible' => 'visible',
                'ferme' => 'ferme',
                default => $status
            };
            return $q->where('status', $dbStatus);
        });

        $clients = $query->get()->makeHidden(['id_route_import']);

        // 3. Format Brands (Ideally use an API Resource, but looping here is acceptable for export)
        foreach ($clients as $client) {
            // Map owner name manually if not using API Resource
            if ($client->ownerUser) {
                $client->owner = $client->ownerUser->username; 
            }
            self::appendFormattedBrands($client);
        }

        return $clients;
    }

    //  //  //  //  //
    //  //  //  //  //  Duplicates
    //  //  //  //  //

    public static function getDoublesClients(Request $request, int $id_route_import) {
        return [
            'getDoublantCustomerCode'  => self::findDuplicates($request, $id_route_import, 'CustomerCode'),
            'getDoublantCustomerNameE' => self::findDuplicates($request, $id_route_import, 'CustomerNameE'),
            'getDoublantTel'           => self::findDuplicates($request, $id_route_import, 'Tel'),
            'getDoublantGPS'           => self::findDuplicates($request, $id_route_import, 'GPS'),
        ];
    }

    //  //  //  //  //
    //  //  //  //  //  Helpers
    //  //  //  //  //

    private static function handleClientImageUpload(Request $request, Client $client, string $field) {
        // 1. Check if this field needs processing
        // For 'Store', we usually just check hasFile.
        // For 'Update', the frontend sends a specific flag (e.g. "facade_image_updated")
        $isUpdated = $request->input($field.'_updated') === 'true';
        
        // If not updated and no file sent, do nothing.
        if (!$request->hasFile($field) && !$isUpdated) {
            return;
        }

        // $oldFileName = $client->$field;
        $type = strtoupper(str_replace('_image', '', $field)); // e.g. "FACADE"

        // 2. Handle New File Upload
        if ($request->hasFile($field)) {
            $file = $request->file($field);
            $extension = $file->getClientOriginalExtension();
            
            // Standard Naming: DISTRICT_CITY_ID_TYPE_CODE.ext
            $fileName = "{$client->DistrictNo}_{$client->CityNo}_{$client->id}_".uniqid()."_{$type}_{$client->CustomerCode}.{$extension}";
            
            $path = public_path("uploads/clients/{$client->id}");
            $file->move($path, $fileName);
            
            $client->$field = $fileName;
        } 
        // 3. Handle clearing the image (User deleted it in frontend without uploading new one)
        else if ($isUpdated) {
            $client->$field = "";
        }

        // 4. Cleanup Old File (Only for Update scenarios)
        // If we had a filename, and it has changed (or become empty), delete the physical old file.
        // if (!empty($oldFileName) && $oldFileName !== $client->$field) {
            // $oldFilePath = public_path("uploads/clients/{$client->id}/{$oldFileName}");
            // if (File::exists($oldFilePath)) {
            //     File::delete($oldFilePath);
            // }
        // }
    }

    private static function findDuplicates(Request $request, int $id_route_import, string $type) {
        // 1. Define columns based on type
        if ($type === 'GPS') {
            $groupBy = ['Latitude', 'Longitude'];
        } else {
            $groupBy = [$type];
        }

        // 2. Initialize the Subquery
        $duplicatesSub = DB::table('clients')
            ->select($groupBy)
            ->where('id_route_import', $id_route_import)
            ->whereIn('status', ['validated', 'confirmed']);

        // 3. Conditional Date Logic
        // Check if dates exist in the request before parsing or applying filters
        $startInput = $request->input('start_date');
        $endInput   = $request->input('end_date');

        if (!empty($startInput) && !empty($endInput)) {
            $startDate = Carbon::parse($startInput)->format('Y-m-d');
            $endDate   = Carbon::parse($endInput)->format('Y-m-d');

            $duplicatesSub->whereRaw("STR_TO_DATE(created_at, '%d %M %Y') BETWEEN ? AND ?", [$startDate, $endDate]);
        }

        // 4. Finish the Subquery (Group By and Having)
        $duplicatesSub->groupBy($groupBy)
                    ->havingRaw('COUNT(*) > 1');

        // 5. Join the subquery (Fast SQL, no PHP looping)
        $query = DB::table('clients as c')
            ->joinSub($duplicatesSub, 'd', function ($join) use ($type) {
                if ($type === 'GPS') {
                    $join->on('c.Latitude', '=', 'd.Latitude')
                        ->on('c.Longitude', '=', 'd.Longitude');
                } else {
                    $join->on('c.'.$type, '=', 'd.'.$type);
                }
            })
            ->where('c.id_route_import', $id_route_import)
            ->whereIn('c.status', ['validated', 'confirmed']);

        // Fetch results
        $rows = $query->get();

        // 6. Format Brands
        foreach ($rows as $client) {
            self::appendFormattedBrands($client);
        }

        return $rows;
    }

    public static function appendFormattedBrands($client) {
        $brandsJson = is_array($client) ? ($client['AvailableBrands'] ?? '{}') : ($client->AvailableBrands ?? '{}');
        $assoc = is_array($brandsJson) ? $brandsJson : (json_decode($brandsJson, true) ?? []);
        $values = array_values($assoc);

        if (is_array($client)) {
            $client['AvailableBrands_array_formatted'] = $values;
            $client['AvailableBrands_string_formatted'] = implode(", ", $values);
        } else {
            $client->AvailableBrands_array_formatted = $values;
            $client->AvailableBrands_string_formatted = implode(", ", $values);
        }
        return $client;
    }
}