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

    //  //  //  //  //
    //  //  //  //  //  Casts
    //  //  //  //  //

    protected $casts = [
        'AvailableBrands' => 'array',
    ];

    //  //  //  //  //
    //  //  //  //  //  Relationships
    //  //  //  //  //

    public function ownerUser()
    {
        return $this->belongsTo(User::class, 'owner');
    }

    //  //  //  //  //
    //  //  //  //  //  Index
    //  //  //  //  //

    public static function index(int $id_route_import_tempo)
    {
        $clients = self::where('id_route_import_tempo', $id_route_import_tempo)
            ->with('ownerUser')
            ->get();

        // Format owners and brands
        $clients->each(function ($client) {
            $client->owner_username = $client->ownerUser->username ?? null;
            self::appendFormattedBrands($client); // reuse Client helper (public static)
        });

        return $clients;
    }

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

    public static function updateClient(Request $request, int $id_route_import_tempo, int $id_client_tempo)
    {
        $client = self::find($id_client_tempo);
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
            'Latitude', 'Longitude', 'Address', 'RvrsGeoAddress', 'DistrictNo', 'DistrictNameE',
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
        $client->JPlan  = mb_strtoupper($request->input('JPlan') ?? '', 'UTF-8');

        $client->AvailableBrands = json_encode($AvailableBrands, JSON_UNESCAPED_UNICODE);

        $client->owner          =   $request->get('owner')          ?? $client->owner;
        $client->tel_status     =   $request->get('tel_status')     ?? $client->tel_status;
        $client->tel_comment    =   $request->get('tel_comment')    ?? $client->tel_comment;

        $client->save();

        // Format brands for response
        self::appendFormattedBrands($client);

        return $client;
    }

    public static function deleteClient(int $id_route_import_tempo, int $id_client_tempo)
    {
        $client = self::find($id_client_tempo);
        if ($client) $client->delete();
    }

    //  //  //  //  //
    //  //  //  //  //  Store/Delete Clients
    //  //  //  //  //

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
                'RvrsGeoAddress'            => $clientElem['RvrsGeoAddress']         ?? '',
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

    public static function deleteClients()
    {
        self::where('created_by', Auth::id())->delete();
    }

    //  //  //  //  //
    //  //  //  //  //  Update Resume
    //  //  //  //  //

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

    //  //  //  //  //
    //  //  //  //  //  Duplicates
    //  //  //  //  //

    public static function getDoublesClients(Request $request, int $id_route_import_tempo) {
        return [
            'getDoublantCustomerCode'  => self::findDuplicates($request, $id_route_import_tempo, 'CustomerCode'),
            'getDoublantCustomerNameE' => self::findDuplicates($request, $id_route_import_tempo, 'CustomerNameE'),
            'getDoublantTel'           => self::findDuplicates($request, $id_route_import_tempo, 'Tel'),
            'getDoublantGPS'           => self::findDuplicates($request, $id_route_import_tempo, 'GPS'),
        ];
    }

    //  //  //  //  //
    //  //  //  //  //  Helpers
    //  //  //  //  //

    public static function findDuplicates(Request $request, int $id_route_import_tempo, string $type) {
        // 1. Define columns based on type
        if ($type === 'GPS') {
            $groupBy = ['Latitude', 'Longitude'];
        } else {
            $groupBy = [$type];
        }

        // 2. Initialize the Subquery
        $duplicatesSub = DB::table('clients_tempo')
            ->select($groupBy)
            ->where('id_route_import_tempo', $id_route_import_tempo);

        // 3. Conditionally apply Date Filter
        // Only apply if both dates are present and not empty
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

        // 5. Join the subquery on the TEMPO table
        $query = DB::table('clients_tempo as c')
            ->joinSub($duplicatesSub, 'd', function ($join) use ($type) {
                if ($type === 'GPS') {
                    $join->on('c.Latitude', '=', 'd.Latitude')
                        ->on('c.Longitude', '=', 'd.Longitude');
                } else {
                    $join->on('c.'.$type, '=', 'd.'.$type);
                }
            })
            ->where('c.id_route_import_tempo', $id_route_import_tempo);

        // Fetch results
        $rows = $query->get();

        // 6. Format Brands
        foreach ($rows as $client) {
            self::appendFormattedBrands($client);
        }

        return $rows;
    }

    public static function appendFormattedBrands($client) {
        $brandsJson =   is_array($client)       ? ($client['AvailableBrands'] ?? '{}')  : ($client->AvailableBrands ?? '{}');
        $assoc      =   is_array($brandsJson)   ? $brandsJson                           : (json_decode($brandsJson, true) ?? []);
        $values     =   array_values($assoc);

        if (is_array($client)) {
            $client['AvailableBrands_array_formatted']  =   $values;
            $client['AvailableBrands_string_formatted'] =   implode(", ", $values);
        } else {
            $client->AvailableBrands_array_formatted    =   $values;
            $client->AvailableBrands_string_formatted   =   implode(", ", $values);
        }

        return $client;
    }
}