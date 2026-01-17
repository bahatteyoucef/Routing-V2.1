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
    //  //  //  //  //  Boot
    //  //  //  //  //

    protected static function boot() {
        parent::boot();

        //
        static::creating(function ($model) {
            if (empty($model->created_at)) {
                $model->created_at = Carbon::now()->format('d F Y'); 
            }
        });
    }

    //  //  //  //  //
    //  //  //  //  //  Relationships
    //  //  //  //  //

    public function ownerUser() {
        return $this->belongsTo(User::class, 'owner');
    }

    //  //  //  //  //
    //  //  //  //  //  Index
    //  //  //  //  //

    public static function index(int $id_route_import_tempo) {
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
            return $input->status == "nonvalidated";
        });

        $validator->sometimes(['AvailableBrands'],  ["required_if:OpenCustomer,Ouvert"] , function (Fluent $input) {
            return $input->BrandAvailability == "";
        });

        return $validator;
    }

    public static function updateClient(Request $request, int $id_route_import_tempo, int $id_client_tempo) {
        $client = self::find($id_client_tempo);
        if (!$client) return null;

        // --- FIX START: Handle JSON string from Vue correctly ---
        $rawBrands = $request->input('AvailableBrands');
        $brandsArray = [];

        // 1. Try to decode if it's a JSON string (This handles your Vue JSON.stringify data)
        if (is_string($rawBrands)) {
            $decoded = json_decode($rawBrands, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $brandsArray = $decoded;
            } else {
                // Fallback: It's a simple comma-separated string
                $brandsArray = array_filter(array_map('trim', explode(',', $rawBrands)), fn($v) => $v !== '');
            }
        } elseif (is_array($rawBrands)) {
            $brandsArray = $rawBrands;
        }
        // --- FIX END ---

        $AvailableBrands = [];
        foreach (array_values($brandsArray) as $index => $value) {
            $AvailableBrands["brand_$index"] = $value;
        }

        // Mass assign simple fields
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
                $client->{$f} = $request->get($f) ?? '';
            }
        }

        $client->CustomerNameE = mb_strtoupper($request->get('CustomerNameE') ?? '', 'UTF-8');
        $client->CustomerNameA = mb_strtoupper($request->get('CustomerNameA') ?? '', 'UTF-8');
        $client->JPlan  = mb_strtoupper($request->input('JPlan') ?? '', 'UTF-8');

        // --- FIX START: Assign the ARRAY directly ---
        // Laravel's $casts will handle the json_encoding automatically.
        // Assigning json_encode() output here would cause "Double Encoding".
        $client->AvailableBrands = $AvailableBrands; 
        // --- FIX END ---

        $client->owner          =   $request->get('owner')          ?? $client->owner;
        $client->tel_status     =   $request->get('tel_status')     ?? $client->tel_status;
        $client->tel_comment    =   $request->get('tel_comment')    ?? $client->tel_comment;

        $client->save();

        self::appendFormattedBrands($client);

        return $client;
    }

    public static function deleteClient(int $id_route_import_tempo, int $id_client_tempo) {
        $client = self::find($id_client_tempo);
        if ($client) $client->delete();
    }

    //  //  //  //  //
    //  //  //  //  //  Store/Delete Clients
    //  //  //  //  //

    public static function storeClients(Request $request, int $id_route_import_tempo) {
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
        $defaultCreatedAt = $now->toDateTimeString();
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

            // Validations
            $customerCode           = ClientTempo::normalizeCustomerCode($clientElem['CustomerCode'] ?? '');
            $createdAtValue         = ClientTempo::normalizeCreatedAt($clientElem['created_at'] ?? null, $defaultCreatedAt);
            $availableBrandsJson    = ClientTempo::normalizeAvailableBrands($clientElem['AvailableBrands'] ?? '[]');

            // Prepare row for bulk insert (use created_at consistent format)
            $rows[] = [
                'NewCustomer'               => $clientElem['NewCustomer']            ?? '',
                'CustomerIdentifier'        => $clientElem['CustomerIdentifier']     ?? '',
                'CustomerCode'              => $customerCode                            ,
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
                'start_adding_time'         => $clientElem['start_adding_time']      ?? '',
                'adding_duration'           => $clientElem['adding_duration']        ?? '',
                'JPlan'                     => $clientElem['JPlan']                  ?? '',
                'Journee'                   => $clientElem['Journee']                ?? '',
                'comment'                   => $clientElem['comment']                ?? '',

                'AvailableBrands'           => $availableBrandsJson                       ,
                'created_at'                => $createdAtValue,
                'id_route_import_tempo'     => $id_route_import_tempo,
                'owner'                     => $ownerId,
                'created_by'                => $createdBy,
            ];
        }

        // Insert in chunks
        $chunks = array_chunk($rows, 500);
        foreach ($chunks as $chunk) {
            self::insert($chunk);
        }
    }

    public static function deleteClients() {
        self::where('created_by', Auth::id())->delete();
    }

    //  //  //  //  //
    //  //  //  //  //  Update Resume
    //  //  //  //  //

    public static function updateResumeClients(Request $request) {
        $clients = $request->input('data');
        if (is_string($clients)) $clients = json_decode($clients, true);
        if (empty($clients) || !is_array($clients)) return;

        $now = Carbon::now()->toDateTimeString(); // Y-m-d H:i:s
        $chunks = array_chunk($clients, 500);

        $tableName = (new self)->getTable();
        $pdo = DB::getPdo();

        foreach ($chunks as $chunk) {
            // collect ids
            $ids = [];
            $caseJPlanParts = [];
            $caseJourneeParts = [];

            foreach ($chunk as $c) {
                if (!isset($c['id'])) continue;
                $id = intval($c['id']);
                $ids[] = $id;

                // JPlan -> uppercase fallback to empty string
                $jPlan = !empty($c['JPlan']) ? mb_strtoupper($c['JPlan'], 'UTF-8') : '';
                $caseJPlanParts[] = "WHEN {$id} THEN " . $pdo->quote($jPlan);

                // Journee -> keep as provided (fallback to empty string)
                $journee = !empty($c['Journee']) ? $c['Journee'] : '';
                $caseJourneeParts[] = "WHEN {$id} THEN " . $pdo->quote($journee);
            }

            if (empty($ids)) continue;

            $sets = [];

            if (!empty($caseJPlanParts)) {
                $sets[] = "`JPlan` = CASE `id` " . implode(' ', $caseJPlanParts) . " ELSE `JPlan` END";
            }

            if (!empty($caseJourneeParts)) {
                $sets[] = "`Journee` = CASE `id` " . implode(' ', $caseJourneeParts) . " ELSE `Journee` END";
            }

            // always update updated_at
            $sets[] = "`updated_at` = " . $pdo->quote($now);

            $idsList = implode(',', array_map('intval', array_unique($ids)));
            $sql = "UPDATE `{$tableName}` SET " . implode(', ', $sets) . " WHERE `id` IN ({$idsList})";

            DB::statement($sql);
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
        // 1. Retrieve the raw value based on whether client is Array or Object
        $rawBrands = is_array($client) 
            ? ($client['AvailableBrands'] ?? []) 
            : ($client->AvailableBrands ?? []);

        // 2. Normalize: If it's a string (JSON), decode it. 
        //    If it's already an array (due to Model $casts), this skips.
        if (is_string($rawBrands)) {
            $assoc = json_decode($rawBrands, true);
        } else {
            $assoc = $rawBrands;
        }

        // 3. Safety Check: Ensure $assoc is actually an array before using array_values
        //    This handles cases where json_decode returns null, or the DB had bad data.
        if (!is_array($assoc)) {
            $assoc = [];
        }

        // 4. Process the values
        $values = array_values($assoc);
        $stringFormatted = implode(", ", $values);

        // 5. Assign back to client
        if (is_array($client)) {
            $client['AvailableBrands']  = $values;
            $client['AvailableBrands_array_formatted']  = $values;
            $client['AvailableBrands_string_formatted'] = $stringFormatted;
        } else {
            $client->AvailableBrands                    = $values;
            $client->AvailableBrands_array_formatted    = $values;
            $client->AvailableBrands_string_formatted   = $stringFormatted;
        }

        return $client;
    }

    //  //  //  //  //
    //  //  //  //  //  Validators
    //  //  //  //  //

    public static function normalizeCustomerCode($code) {
        $code = (string) ($code ?? '');
        $code = trim($code);

        // If empty, return empty string (no validation needed)
        if ($code === '') {
            return $code;
        }

        // Forbidden characters: / \ : * ? " < > | & and space
        if (preg_match('/[\/\\\\:*?"<>|& ]/', $code)) {
            throw new \Exception("Le champ CustomerCode contient des caractères interdits : " . $code);
        }

        return $code;
    }

    protected static function normalizeCreatedAt($value, ?string $fallback = null) {
        // If nothing provided, use fallback (already expected in the target format)
        if (empty($value)) {
            if ($fallback === null) {
                throw new \Exception('No created_at provided and no fallback supplied.');
            }
            return $fallback;
        }

        $str = (string)$value;

        // 1) Try strict datetime first (common exact DB format)
        $dt = \DateTime::createFromFormat('Y-m-d H:i:s', $str);
        $errors = \DateTime::getLastErrors();
        if ($dt !== false && empty($errors['warning_count']) && empty($errors['error_count'])) {
            return Carbon::instance($dt)->format('d F Y');
        }

        // 2) Try Carbon::parse (lenient)
        try {
            $c = Carbon::parse($str);
            return $c->format('d F Y');
        } catch (\Exception $e) {
            // continue to explicit formats
        }

        // 3) Try a list of common formats
        $commonFormats = [
            'd F Y',        // 18 August 2025
            'd M Y',        // 18 Aug 2025
            'd/m/Y',        // 18/08/2025
            'd-m-Y',        // 18-08-2025
            'Y-m-d',        // 2025-08-18
            'Y/m/d',        // 2025/08/18
            'm/d/Y',        // 08/18/2025
            'm-d-Y',        // 08-18-2025
            'd F Y H:i',    // 18 August 2025 14:30
            'Y-m-d\TH:i:sP' // ISO 8601 with timezone
        ];

        foreach ($commonFormats as $fmt) {
            $dt = \DateTime::createFromFormat($fmt, $str);
            $errors = \DateTime::getLastErrors();
            if ($dt !== false && empty($errors['warning_count']) && empty($errors['error_count'])) {
                return Carbon::instance($dt)->format('d F Y');
            }
        }

        // nothing worked
        throw new \Exception(
            "Invalid created_at value. Expected a parseable date (e.g. '2025-08-18 14:30:00', '2025-08-18', or '18 August 2025'). Provided: {$str}"
        );
    }

    public static function normalizeAvailableBrands($raw) {
        if (is_string($raw)) {
            $decoded = json_decode($raw, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('AvailableBrands contains invalid JSON: ' . json_last_error_msg());
            }
        } elseif (is_array($raw)) {
            $decoded = $raw;
        } else {
            throw new \Exception('AvailableBrands must be an array or a JSON string.');
        }

        if (empty($decoded)) {
            return '[]';
        }

        foreach ($decoded as $key => $value) {
            if (!is_string($key) || !preg_match('/^brand_\d+$/', $key)) {
                throw new \Exception("AvailableBrands keys must be 'brand_N' (e.g. 'brand_0'). Invalid key: " . var_export($key, true));
            }

            if (!is_string($value) || trim($value) === '') {
                throw new \Exception("AvailableBrands values must be non-empty strings. Invalid value for '{$key}': " . var_export($value, true));
            }
        }

        $json = json_encode($decoded, JSON_UNESCAPED_UNICODE);
        if ($json === false) {
            throw new \Exception('Failed to encode AvailableBrands to JSON.');
        }

        return $json;
    }
}