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
use App\Models\User;
use stdClass;

class ClientTempo extends Model
{
    use HasFactory;

    protected $guarded      = [];
    protected $table        = 'clients_tempo';
    protected $primaryKey   = 'id';
    public    $timestamps   = false;

    // Cast AvailableBrands to array when accessed via Eloquent attribute
    protected $casts = [
        'AvailableBrands' => 'array',
    ];

    /**
     * Relationship to owner user (for eager loading owner username)
     */
    public function ownerUser()
    {
        return $this->belongsTo(User::class, 'owner');
    }

    /**
     * Index: list clients tempo for given tempo id (with owner username and formatted brands)
     */
    public static function index(int $id_route_import_tempo)
    {
        $clients = self::where('id_route_import_tempo', $id_route_import_tempo)
            ->with('ownerUser')
            ->get();

        // Format owners and brands
        $clients->each(function ($client) {
            $client->owner_username = $client->ownerUser->username ?? null;
            \App\Models\Client::appendFormattedBrands($client); // reuse Client helper (public static)
        });

        return $clients;
    }

    /**
     * Store clients (batch insert) for a given tempo id.
     * Request 'data' can be a JSON string or array of objects.
     */
    public static function storeClients(Request $request, int $id_route_import_tempo)
    {
        // Remove previous tempo clients for this user
        self::deleteClients();

        // Accept either an array or JSON string
        $clientsData = $request->input('data');
        if (is_string($clientsData)) {
            $clientsData = json_decode($clientsData, true);
        }

        if (empty($clientsData) || !is_array($clientsData)) {
            return;
        }

        $now = Carbon::now();
        $createdAt = $now->toDateTimeString();
        $createdBy = Auth::id();

        // Precompute user map for 'owner' usernames to user IDs
        $ownerUsernames = collect($clientsData)
            ->pluck('owner')
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        $userMap = [];
        if (!empty($ownerUsernames)) {
            $userMap = User::whereIn('username', $ownerUsernames)->pluck('id', 'username')->toArray();
        }

        $rows = [];

        foreach ($clientsData as $clientElem) {
            // Use array access safely
            $ownerUsername = $clientElem['owner'] ?? null;

            // Resolve owner id
            $ownerId = $userMap[$ownerUsername] ?? $createdBy; // fallback to current user

            // Validate CustomerCode
            $customerCode = $clientElem['CustomerCode'] ?? '';
            if (!empty($customerCode) && preg_match('/[\/\\\\:*?"<>|& ]/', $customerCode)) {
                throw new Exception("Le champ CustomerCode contient des caractères interdits : " . $customerCode);
            }

            // Format AvailableBrands: 'val1, val2' => {"brand_0":"val1","brand_1":"val2"}
            $brandsRaw = $clientElem['AvailableBrands'] ?? '';
            $brandsArray = is_array($brandsRaw) ? $brandsRaw : array_filter(array_map('trim', explode(',', (string)$brandsRaw)), fn($v) => $v !== '');
            $brandAssoc = [];
            foreach (array_values($brandsArray) as $idx => $val) {
                $brandAssoc["brand_$idx"] = $val;
            }
            $availableBrandsJson = json_encode($brandAssoc, JSON_UNESCAPED_UNICODE);

            // Prepare row for bulk insert (use created_at consistent format)
            $rows[] = [
                'NewCustomer'               => $clientElem['NewCustomer']            ?? '',
                'CustomerIdentifier'        => $clientElem['CustomerIdentifier']     ?? '',
                'CustomerCode'              => $customerCode                           ,
                'OpenCustomer'              => $clientElem['OpenCustomer']           ?? '',
                'CustomerNameE'             => isset($clientElem['CustomerNameE']) ? mb_strtoupper($clientElem['CustomerNameE'], 'UTF-8') : '',
                'CustomerNameA'             => isset($clientElem['CustomerNameA']) ? mb_strtoupper($clientElem['CustomerNameA'], 'UTF-8') : '',
                'Latitude'                  => $clientElem['Latitude']               ?? 0,
                'Longitude'                 => $clientElem['Longitude']              ?? 0,
                'Address'                   => $clientElem['Address']                ?? '',
                'DistrictNo'                => $clientElem['DistrictNo']             ?? '',
                'DistrictNameE'             => $clientElem['DistrictNameE']          ?? '',
                'CityNo'                    => $clientElem['CityNo']                 ?? '',
                'CityNameE'                 => $clientElem['CityNameE']              ?? '',
                'Tel'                       => $clientElem['Tel']                    ?? '',
                'tel_comment'               => $clientElem['tel_comment']            ?? '',
                'tel_status'                => $clientElem['tel_status']             ?? '',
                'CustomerType'              => $clientElem['CustomerType']           ?? '',
                'status'                    => $clientElem['status']                 ?? '',
                'Neighborhood'              => $clientElem['Neighborhood']           ?? '',
                'Landmark'                  => $clientElem['Landmark']               ?? '',
                'BrandAvailability'         => $clientElem['BrandAvailability']      ?? '',
                'BrandSourcePurchase'       => $clientElem['BrandSourcePurchase']    ?? '',
                'Frequency'                 => $clientElem['Frequency']              ?? '',
                'SuperficieMagasin'         => $clientElem['SuperficieMagasin']      ?? '',
                'NbrAutomaticCheckouts'     => $clientElem['NbrAutomaticCheckouts']  ?? '',
                'AvailableBrands'           => $availableBrandsJson,
                'start_adding_time'         => $clientElem['start_adding_time']      ?? '',
                'adding_duration'           => $clientElem['adding_duration']        ?? '',
                'JPlan'                     => $clientElem['JPlan']                  ?? '',
                'Journee'                   => $clientElem['Journee']                ?? '',
                'comment'                   => $clientElem['comment']                ?? '',
                'created_at'                => $clientElem['created_at'] ?? $createdAt,
                'id_route_import_tempo'     => $id_route_import_tempo,
                'owner'                     => $ownerId,
                'created_by'                => $createdBy,
            ];
        }

        // Insert in chunks
        DB::transaction(function () use ($rows) {
            $chunks = array_chunk($rows, 500);
            foreach ($chunks as $chunk) {
                self::insert($chunk);
            }
        });
    }

    /**
     * Validate update rules (unchanged logic, returned as-is)
     */
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
            return $input->status == "nonvalidated";
        });

        $validator->sometimes(['AvailableBrands'],  ["required_if:OpenCustomer,Ouvert"] , function (Fluent $input) {
            return $input->BrandAvailability == "";
        });

        $validator->sometimes(['CustomerBarCode_image'],  ["required_if:OpenCustomer,Ouvert"] , function (Fluent $input) {
            return (($input->CustomerBarCode_image_original_name != "") && ($input->CustomerBarCode_image_updated == "true"));
        });

        $validator->sometimes(['facade_image'],  ["file"] , function (Fluent $input) {
            return (($input->facade_image_original_name != "") && ($input->facade_image_updated == "true"));
        });

        $validator->sometimes(['in_store_image'],  ["required_if:OpenCustomer,Ouvert"] , function (Fluent $input) {
            return (($input->in_store_image_original_name != "") && ($input->in_store_image_updated == "true"));
        });

        return $validator;
    }

    /**
     * Update a single ClientTempo record
     */
    public static function updateClient(Request $request, int $id_route_import_tempo, int $id)
    {
        $client = self::find($id);
        if (!$client) return null;

        // Build AvailableBrands JSON
        $brandsArray = $request->input('AvailableBrands');
        if (!is_array($brandsArray)) {
            $brandsArray = is_string($brandsArray) ? array_filter(array_map('trim', explode(',', $brandsArray)), fn($v) => $v !== '') : [];
        }

        $AvailableBrands = [];
        foreach (array_values($brandsArray) as $index => $value) {
            $AvailableBrands["brand_$index"] = $value;
        }

        // Mass assign simple fields (explicit list)
        $fields = [
            'NewCustomer', 'OpenCustomer', 'CustomerIdentifier', 'CustomerCode',
            'Latitude', 'Longitude', 'Address', 'DistrictNo', 'DistrictNameE',
            'CityNo', 'CityNameE', 'Tel', 'CustomerType', 'Neighborhood',
            'Landmark', 'BrandAvailability', 'BrandSourcePurchase', 'Frequency',
            'SuperficieMagasin', 'NbrAutomaticCheckouts', 'comment', 'Journee',
            'status', 'nonvalidated_details'
        ];

        foreach ($fields as $f) {
            if ($request->has($f)) {
                $client->{$f} = $request->get($f);
            }
        }

        $client->CustomerNameE = mb_strtoupper($request->get('CustomerNameE') ?? '', 'UTF-8');
        $client->CustomerNameA = mb_strtoupper($request->get('CustomerNameA') ?? '', 'UTF-8');
        $client->JPlan = mb_strtoupper($request->input('JPlan') ?? '', 'UTF-8');

        $client->AvailableBrands = json_encode($AvailableBrands, JSON_UNESCAPED_UNICODE);

        if (Auth::user()->hasRole('FrontOffice')) {
            $client->owner = Auth::user()->id;
        } else {
            $client->owner = $request->get('owner') ?? $client->owner;
            $client->tel_status = $request->get('tel_status') ?? $client->tel_status;
            $client->tel_comment = $request->get('tel_comment') ?? $client->tel_comment;
        }

        $client->save();

        // Format brands for response
        \App\Models\Client::appendFormattedBrands($client);

        return $client;
    }

    /**
     * Delete a single ClientTempo row
     */
    public static function deleteClient(int $id_route_import_tempo, int $id)
    {
        $client = self::find($id);
        if ($client) $client->delete();
    }

    /**
     * Update resume clients (batch upsert of JPlan/Journee by id)
     */
    public static function updateResumeClients(Request $request)
    {
        $clients = $request->input('data');
        if (is_string($clients)) $clients = json_decode($clients, true);
        if (empty($clients) || !is_array($clients)) return;

        $now = Carbon::now()->toDateTimeString();
        $chunks = array_chunk($clients, 500);

        foreach ($chunks as $chunk) {
            $upsertData = [];
            foreach ($chunk as $c) {
                $upsertData[] = [
                    'id' => $c['id'],
                    'JPlan' => !empty($c['JPlan']) ? mb_strtoupper($c['JPlan'], 'UTF-8') : '',
                    'Journee' => !empty($c['Journee']) ? $c['Journee'] : '',
                    'owner_bo' => Auth::id(),
                    'created_at' => $now,
                ];
            }

            self::upsert($upsertData, ['id'], ['JPlan', 'Journee', 'owner_bo', 'created_at']);
        }
    }

    /**
     * Delete tempo clients for the current user
     */
    public static function deleteClients()
    {
        self::where('created_by', Auth::id())->delete();
    }

    /**
     * Get duplicates summary (wrapper)
     */
    public static function getDoublesClients(int $id_route_import_tempo)
    {
        $getDoublant = new stdClass();
        $getDoublant->getDoublantCustomerCode = self::getDoublesCustomerCodeClients($id_route_import_tempo);
        $getDoublant->getDoublantCustomerNameE = self::getDoublesCustomerNameEClients($id_route_import_tempo);
        $getDoublant->getDoublantTel = self::getDoublesTelClients($id_route_import_tempo);
        $getDoublant->getDoublantGPS = self::getDoublesGPSClients($id_route_import_tempo);
        return $getDoublant;
    }

    /**
     * Helper: format AvailableBrands for a collection/array of stdClass rows
     */
    protected static function formatBrandsOnCollection($collection)
    {
        foreach ($collection as $row) {
            \App\Models\Client::appendFormattedBrands($row);
        }
        return $collection;
    }

    /**
     * Duplicate finders (optimized to use joinSub and reuse formatting)
     */
    public static function getDoublesCustomerCodeClients(int $id_route_import_tempo)
    {
        $duplicates = DB::table('clients_tempo')
            ->select('CustomerCode')
            ->where('id_route_import_tempo', $id_route_import_tempo)
            ->groupBy('CustomerCode')
            ->havingRaw('COUNT(*) > 1');

        $clients = DB::table('clients_tempo as c')
            ->joinSub($duplicates, 'd', function ($join) {
                $join->on('c.CustomerCode', '=', 'd.CustomerCode');
            })
            ->where('c.id_route_import_tempo', $id_route_import_tempo)
            ->get();

        return self::formatBrandsOnCollection($clients);
    }

    public static function getDoublesCustomerNameEClients(int $id_route_import_tempo)
    {
        $duplicates = DB::table('clients_tempo')
            ->select('CustomerNameE')
            ->where('id_route_import_tempo', $id_route_import_tempo)
            ->groupBy('CustomerNameE')
            ->havingRaw('COUNT(*) > 1');

        $clients = DB::table('clients_tempo as c')
            ->joinSub($duplicates, 'd', function ($join) {
                $join->on('c.CustomerNameE', '=', 'd.CustomerNameE');
            })
            ->where('c.id_route_import_tempo', $id_route_import_tempo)
            ->get();

        return self::formatBrandsOnCollection($clients);
    }

    public static function getDoublesTelClients(int $id_route_import_tempo)
    {
        $duplicates = DB::table('clients_tempo')
            ->select('Tel')
            ->where('id_route_import_tempo', $id_route_import_tempo)
            ->groupBy('Tel')
            ->havingRaw('COUNT(*) > 1');

        $clients = DB::table('clients_tempo as c')
            ->joinSub($duplicates, 'd', function ($join) {
                $join->on('c.Tel', '=', 'd.Tel');
            })
            ->where('c.id_route_import_tempo', $id_route_import_tempo)
            ->get();

        return self::formatBrandsOnCollection($clients);
    }

    public static function getDoublesGPSClients(int $id_route_import_tempo)
    {
        $duplicates = DB::table('clients_tempo')
            ->select('Latitude', 'Longitude')
            ->where('id_route_import_tempo', $id_route_import_tempo)
            ->groupBy('Latitude', 'Longitude')
            ->havingRaw('COUNT(*) > 1');

        $clients = DB::table('clients_tempo as c')
            ->joinSub($duplicates, 'd', function ($join) {
                $join->on('c.Latitude', '=', 'd.Latitude')
                     ->on('c.Longitude', '=', 'd.Longitude');
            })
            ->where('c.id_route_import_tempo', $id_route_import_tempo)
            ->get();

        return self::formatBrandsOnCollection($clients);
    }
}