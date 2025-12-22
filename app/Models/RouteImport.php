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

    protected $table = 'route_import';
    protected $primaryKey = 'id';

    // For guarded/mass assignment: prefer fillable in future if you want stricter control
    protected $guarded = [];

    public $timestamps = false;

    /*
     |--------------------------------------------------------------------------
     | Relationships
     |--------------------------------------------------------------------------
     */
    public function clientsRelation()
    {
        return $this->hasMany(Client::class, 'id_route_import', 'id');
    }

    public function files()
    {
        return $this->hasMany(RouteImportFile::class, 'id_route_import', 'id');
    }

    public function districts()
    {
        return $this->hasMany(RouteImportDistrict::class, 'id_route_import', 'id');
    }

    public function journeyPlans()
    {
        return $this->hasMany(JourneyPlan::class, 'id_route_import', 'id');
    }

    public function journees()
    {
        return $this->hasMany(Journee::class, 'id_route_import', 'id');
    }

    /*
     |--------------------------------------------------------------------------
     | Scopes
     |--------------------------------------------------------------------------
     */
    public function scopePermittedByRole(Builder $query, string $type = 'id')
    {
        $user = Auth::user();

        // If no authenticated user, return empty (do not expose data)
        if (! $user) {
            return $query->whereRaw('0 = 1');
        }

        if ($user->hasRole('Super Admin')) {
            return $query; // full access
        }

        $limitedRoles = ['BU Manager', 'BackOffice', 'Viewer', 'FrontOffice'];

        if ($user->hasAnyRole($limitedRoles)) {
            $allowedIds = UserRouteImport::where('id_user', $user->id)
                ->distinct()
                ->pluck('id_route_import')
                ->toArray();

            if (empty($allowedIds)) {
                return $query->whereRaw('0 = 1');
            }

            return $query->whereIn("{$this->getTable()}.{$type}", $allowedIds);
        }

        // Default: restrict to nothing (safe)
        return $query->whereRaw('0 = 1');
    }

    /*
     |--------------------------------------------------------------------------
     | Listing / show helpers (use eager loading and avoid N+1)
     |--------------------------------------------------------------------------
     */
    public static function listForIndex(): \Illuminate\Database\Eloquent\Collection
    {
        // Eager load clients and owner username via join to avoid N+1
        $routeImports = self::with(['clientsRelation' => function ($q) {
            $q->join('users', 'clients.owner', '=', 'users.id')
                ->select('clients.*', 'users.username as owner_username');
        }])->orderBy('id', 'desc')->permittedByRole()->get();

        // Format AvailableBrands using collection mapping rather than modifying DB model consistently
        $routeImports->each(function ($ri) {
            $ri->clientsRelation->each(function ($client) {
                Client::appendFormattedBrands($client);
            });
        });

        return $routeImports;
    }

    // Backwards-compatible wrappers
    public static function indexRouteImport()
    {
        return self::listForIndex();
    }

    public static function headerRouteImports()
    {
        return self::orderBy('id', 'desc')->permittedByRole()->get();
    }

    public static function statsRouteImports()
    {
        return self::headerRouteImports();
    }

    public static function indexRouteImports()
    {
        return self::headerRouteImports();
    }

    public static function comboRouteImport()
    {
        return self::headerRouteImports();
    }

    /*
     |--------------------------------------------------------------------------
     | Validation helpers
     |--------------------------------------------------------------------------
     */
    public static function validateStore(Request $request)
    {
        return Validator::make($request->all(), [
            'id_route_import_tempo' => ['required', 'max:255'],
            'libelle' => ['required', 'max:255'],
            'districts' => ['required'],
        ]);
    }

    public static function validateUpdate(Request $request)
    {
        return Validator::make($request->all(), [
            'file' => ['required', 'file', 'mimes:xlsx,xls'],
        ]);
    }

    /*
     |--------------------------------------------------------------------------
     | Create / update / delete operations with transaction safety and helper
     |--------------------------------------------------------------------------
     */
    public static function storeRouteImport(Request $request)
    {
        $userId = Auth::id();

        $routeImport = self::create([
            'libelle' => $request->input('libelle'),
            'owner' => $userId,
        ]);

        // Move uploaded file from tempo to route_import directory (use Storage when possible)
        $tempoId = $request->get('id_route_import_tempo');
        $tempo = RouteImportTempo::find($tempoId);

        if ($tempo && $tempo->file) {
            $from = public_path("uploads/route_import_tempo/{$userId}/{$tempo->file}");
            $toDir = public_path("uploads/route_import/{$userId}");

            if (! File::exists($toDir)) {
                File::makeDirectory($toDir, 0755, true);
            }

            $to = $toDir . '/' . $tempo->file;

            if (File::exists($from)) {
                File::move($from, $to);
            }

            RouteImportFile::create([
                'id_route_import' => $routeImport->id,
                'file' => $tempo->file,
            ]);
        }

        // Districts
        $districts = json_decode($request->get('districts'), true) ?: [];
        foreach ($districts as $district) {
            RouteImportDistrict::create([
                'DistrictNo' => $district,
                'id_route_import' => $routeImport->id,
                'owner' => $userId,
            ]);
        }

        // Clients (use a helper allowing chunking to avoid OOM)
        $clients = ClientTempo::where('id_route_import_tempo', $tempoId)->cursor();

        $request->merge(['clients' => $clients]);
        self::storeData($request, $routeImport->id);

        // Delete tempo safely
        RouteImportTempo::deleteRouteImportTempo();

        // Link user
        UserRouteImport::create([
            'id_user' => $userId,
            'id_route_import' => $routeImport->id,
        ]);

        DB::commit();

        return $routeImport;
    }

    public static function updateRouteImport(Request $request, int $id)
    {
        $fileName = uniqid() . '.' . $request->file->getClientOriginalExtension();
        $request->file->move(public_path("uploads/route_import/" . Auth::id()), $fileName);

        RouteImportFile::create([
            'id_route_import' => $id,
            'file' => $fileName,
        ]);

        $request->merge([
            'clients' => json_decode($request->get('data'), true),
        ]);

        self::updateData($request, $id);
    }

    public static function showRouteImport(int $id)
    {
        $routeImport = self::with(['files'])->findOrFail($id);

        // Eager load clients with owner username
        $clients    =   Client::where('id_route_import', $id)
                            ->with('ownerUser')   // ownerUser relationship on Client (add in Client.php)
                            ->get();

        $clients->each(function ($client) {
            $client->owner_username = $client->ownerUser->username ?? null;
            Client::appendFormattedBrands($client);
        });

        $routeImport->clients = $clients;
        $routeImport->liste_journey_plan = JourneyPlan::where('id_route_import', $id)->get();
        $routeImport->liste_journee = Journee::where('id_route_import', $id)->get();

        return $routeImport;
    }

    public static function indexedDBShowRouteImport(int $id)
    {
        $routeImport = self::findOrFail($id);

        $clients    =   Client::where('id_route_import', $id)
                            ->with('ownerUser')   // ownerUser relationship on Client (add in Client.php)
                            ->get();

        $clients->each(function ($client) {
            $client->owner_username = $client->ownerUser->username ?? null;
            Client::appendFormattedBrands($client);
        });

        $routeImport->clients = $clients;

        return $routeImport;
    }

    public static function deleteRouteImport(int $id)
    {
        $routeImport = self::findOrFail($id);

        DB::transaction(function () use ($routeImport, $id) {
            $routeImport->delete();
            self::deleteData($id);
        });
    }

    /*
     |--------------------------------------------------------------------------
     | deleteData: ensure actual deletion of related records and files
     |--------------------------------------------------------------------------
     */
    public static function deleteData(int $id)
    {
        $routeImportFiles = RouteImportFile::where('id_route_import', $id)->get();

        foreach ($routeImportFiles as $file) {
            if ($file->file) {
                RouteImportFile::deleteRouteImportFile($file->file);
            }
            $file->delete();
        }

        // Delete clients and related assets in chunks
        Client::where('id_route_import', $id)->chunkById(100, function ($clients) {
            foreach ($clients as $client) {
                // If you want to delete uploaded directories, use Storage or File::deleteDirectory
                $client->delete();
            }
        });

        RouteImportDistrict::where('id_route_import', $id)->delete();
        JourneyPlan::where('id_route_import', $id)->delete();
        Journee::where('id_route_import', $id)->delete();
    }

    /*
     |--------------------------------------------------------------------------
     | Helpers: set willayas / cites - optimized by using associative mapping
     |--------------------------------------------------------------------------
     */
    public static function setWillayasCites(Request $request)
    {
        $clients = json_decode($request->get('clients'), true) ?: [];

        // Preload willayas and cites to avoid queries in the loop
        $districtNames = collect($clients)->pluck('DistrictNameE')->unique()->filter()->values();
        $willayas = RTMWillaya::whereIn('DistrictNameE', $districtNames)->get()->keyBy('DistrictNameE');

        $cityTuples = collect($clients)->map(function ($c) {
            return isset($c['CityNameE'], $c['DistrictNameE']) ? [$c['CityNameE'], $c['DistrictNameE']] : null;
        })->filter()->unique()->values();

        $cites = RTMCite::whereIn('CityNameE', $cityTuples->pluck(0)->unique()->values())
            ->whereIn('DistrictNo', $willayas->pluck('DistrictNo')->unique()->values())
            ->get()
            ->groupBy(function ($row) { return $row->CityNameE . '||' . $row->DistrictNo; });

        foreach ($clients as &$client) {
            $districtName = $client['DistrictNameE'] ?? null;
            $cityName = $client['CityNameE'] ?? null;

            if ($districtName && isset($willayas[$districtName])) {
                $client['DistrictNo'] = $willayas[$districtName]->DistrictNo;

                $key = ($cityName ?? '') . '||' . $client['DistrictNo'];

                if (isset($cites[$key]) && $cites[$key]->first()) {
                    $client['CityNo'] = $cites[$key]->first()->CITYNO;
                } else {
                    $client['CityNo'] = 'UND';
                }
            } else {
                $client['DistrictNo'] = 'UND';
                $client['CityNo'] = 'UND';
            }
        }

        return $clients;
    }

    /*
     |--------------------------------------------------------------------------
     | Routing / Observability helpers
     |--------------------------------------------------------------------------
     */
    public static function obsDetailsRouteImport(string $id)
    {
        return self::showRouteImport((int) $id);
    }

    public static function obsDetailsRouteImportFrontOffice(string $id)
    {
        $routeImport = self::findOrFail($id);

        $data = Client::query()
            ->where('clients.id_route_import', $id)
            ->where(function ($q) {
                $q->where('clients.owner', Auth::id())
                    ->orWhere(function ($q2) {
                        $q2->where('clients.status', 'visible')
                            ->whereIn('clients.CityNo', function ($sub) {
                                $sub->select('CITYNO')
                                    ->from('users_cities')
                                    ->where('id_user', Auth::id());
                            });
                    });
            })
            ->join('users', 'clients.owner', '=', 'users.id')
            ->select('clients.*', 'users.username as owner_username')
            ->get();

        $data->each(function ($client) {
            Client::appendFormattedBrands($client);
        });

        $routeImport->data = $data;

        return $routeImport;
    }

    /*
     |--------------------------------------------------------------------------
     | Clients listing with filters: avoid repeated json_decode and use when/closure
     |--------------------------------------------------------------------------
     */
    public static function clients(int $id)
    {
        $request = request();

        $start = $request->filled('start_date') ? Carbon::parse($request->start_date)->format('Y-m-d') : null;
        $end = $request->filled('end_date') ? Carbon::parse($request->end_date)->format('Y-m-d') : null;

        $query = Client::where('id_route_import', $id);

        if ($request->filled('status')) {
            $query->where('clients.status', $request->get('status'));
        }

        if ($start || $end) {
            // Note: if created_at is datetime use whereDate; leaving STR_TO_DATE fallback for legacy
            if ($start && $end) {
                $query->whereRaw("STR_TO_DATE(clients.created_at, '%d %M %Y') BETWEEN ? AND ?", [$start, $end]);
            } elseif ($start) {
                $query->whereRaw("STR_TO_DATE(clients.created_at, '%d %M %Y') >= ?", [$start]);
            } else {
                $query->whereRaw("STR_TO_DATE(clients.created_at, '%d %M %Y') <= ?", [$end]);
            }
        }

        if ($request->has('selected_CustomerTypes')) {
            $CustomerTypes = json_decode($request->get('selected_CustomerTypes'), true) ?: [];
            $query->whereIn('clients.CustomerType', $CustomerTypes);
        }

        if ($request->has('selected_NbrVitrines')) {
            $NbrVitrines = json_decode($request->get('selected_NbrVitrines'), true) ?: [];
            $query->whereIn('clients.NbrRideauxFacade', $NbrVitrines);
        }

        if ($request->has('selected_SuperficieMagasins')) {
            $SuperficieMagasins = json_decode($request->get('selected_SuperficieMagasins'), true) ?: [];
            $query->whereIn('clients.SuperficieMagasin', $SuperficieMagasins);
        }

        $clients = $query->join('users', 'clients.owner', 'users.id')
            ->select('clients.*', 'users.username as owner_name')
            ->get();

        return $clients;
    }

    /*
     |--------------------------------------------------------------------------
     | sync / storeData / updateData: centralized image helper and chunking
     |--------------------------------------------------------------------------
     */
    protected static function saveBase64Image(?string $base64, string $targetDir): ?string
    {
        if (empty($base64)) return null;

        $base64 = preg_replace('#^data:image/[^;]+;base64,#', '', $base64);
        $base64 = str_replace(' ', '+', $base64);

        $fileName = uniqid() . '.png';
        $targetPath = public_path(trim($targetDir, '/') . '/' . $fileName);

        if (! File::isDirectory(dirname($targetPath))) {
            File::makeDirectory(dirname($targetPath), 0775, true);
        }

        file_put_contents($targetPath, base64_decode($base64));

        return $fileName;
    }

    public static function sync(Request $request)
    {
        $updated_clients = json_decode($request->get('updated_clients'), true) ?: [];

        foreach ($updated_clients as $client_elem) {
            $client = Client::find($client_elem['id']);

            if (! $client) continue;

            // Bulk assign allowed fields only (avoid mass assignment of unsafe fields)
            $assignable = [
                'NewCustomer','CustomerIdentifier','CustomerCode','OpenCustomer','CustomerNameE','CustomerNameA',
                'Latitude','Longitude','Address','CityNo','DistrictNo','CityNameE','DistrictNameE','Tel','CustomerType',
                'Neighborhood','Landmark','BrandAvailability','BrandSourcePurchase','Frequency','SuperficieMagasin',
                'NbrAutomaticCheckouts','AvailableBrands','id_route_import'
            ];

            foreach ($assignable as $field) {
                if (array_key_exists($field, $client_elem)) {
                    $client->{$field} = $client_elem[$field];
                }
            }

            $client->owner = Auth::id();

            $client->CustomerBarCode_image_original_name = $client_elem['CustomerBarCode_image_original_name'] ?? '';
            $client->facade_image_original_name = $client_elem['facade_image_original_name'] ?? '';
            $client->in_store_image_original_name = $client_elem['in_store_image_original_name'] ?? '';

            // Images: only if flagged as updated
            if (($client_elem['CustomerBarCode_image_updated'] ?? '') === 'true') {
                $client->CustomerBarCode_image = self::saveBase64Image($client_elem['CustomerBarCode_image'] ?? null, 'uploads/clients/' . $client->id) ?? '';
            }

            if (($client_elem['facade_image_updated'] ?? '') === 'true') {
                $client->facade_image = self::saveBase64Image($client_elem['facade_image'] ?? null, 'uploads/clients/' . $client->id) ?? '';
            }

            if (($client_elem['in_store_image_updated'] ?? '') === 'true') {
                $client->in_store_image = self::saveBase64Image($client_elem['in_store_image'] ?? null, 'uploads/clients/' . $client->id) ?? '';
            }

            $client->nonvalidated_details = $client_elem['nonvalidated_details'] ?? '';
            $client->JPlan = $client_elem['JPlan'] ?? '';
            $client->Journee = $client_elem['Journee'] ?? '';

            $client->save();
        }

        // Added clients
        $added_clients = json_decode($request->get('added_clients'), true) ?: [];

        foreach ($added_clients as $client_elem) {
            $data = collect($client_elem)->only([
                'NewCustomer','CustomerIdentifier','CustomerCode','OpenCustomer','CustomerNameE','CustomerNameA',
                'Latitude','Longitude','Address','CityNo','DistrictNo','CityNameE','DistrictNameE','Tel','CustomerType',
                'Neighborhood','Landmark','BrandAvailability','BrandSourcePurchase','Frequency','SuperficieMagasin',
                'NbrAutomaticCheckouts','AvailableBrands','id_route_import','status'
            ])->toArray();

            $data['owner'] = Auth::id();

            $client = Client::create($data);

            $client->CustomerBarCode_image_original_name = $client_elem['CustomerBarCode_image_original_name'] ?? '';
            $client->facade_image_original_name = $client_elem['facade_image_original_name'] ?? '';
            $client->in_store_image_original_name = $client_elem['in_store_image_original_name'] ?? '';

            $client->CustomerBarCode_image = self::saveBase64Image($client_elem['CustomerBarCode_image'] ?? null, 'uploads/clients/' . $client->id) ?? '';
            $client->facade_image = self::saveBase64Image($client_elem['facade_image'] ?? null, 'uploads/clients/' . $client->id) ?? '';
            $client->in_store_image = self::saveBase64Image($client_elem['in_store_image'] ?? null, 'uploads/clients/' . $client->id) ?? '';

            $client->nonvalidated_details = $client_elem['nonvalidated_details'] ?? '';
            $client->JPlan = $client_elem['JPlan'] ?? '';
            $client->Journee = $client_elem['Journee'] ?? '';

            $client->save();
        }

        // Deleted clients
        $deleted_clients = json_decode($request->get('deleted_clients'), true) ?: [];

        foreach ($deleted_clients as $client_elem) {
            if (isset($client_elem['id'])) {
                $client = Client::find($client_elem['id']);
                if ($client) $client->delete();
            }
        }
    }

    /*
     |--------------------------------------------------------------------------
     | Front / Users helpers
     |--------------------------------------------------------------------------
     */
    public static function frontOffice(int $id_route_import)
    {
        return DB::table('users')
            ->select([
                'users.id as id',
                'users.username as username',
                'users.first_name as first_name',
                'users.last_name as last_name',
                'users.email as email',
                'users.tel as tel',
                'users.company as company',
                'users.type_user as type_user',
                'users.accuracy as accuracy',
                'users.max_route_import as max_route_import',
                'users_route_import.id_route_import as id_route_import',
            ])
            ->join('users_route_import', 'users.id', 'users_route_import.id_user')
            ->where([['users_route_import.id_route_import', $id_route_import], ['users.type_user', 'FrontOffice']])
            ->get();
    }

    public static function users(int $id_route_import)
    {
        $query = DB::table('users')
            ->select([
                'users.id as id',
                'users.username as username',
                'users.first_name as first_name',
                'users.last_name as last_name',
                'users.email as email',
                'users.tel as tel',
                'users.company as company',
                'users.type_user as type_user',
                'users.accuracy as accuracy',
                'users.max_route_import as max_route_import',
                'users_route_import.id_route_import as id_route_import',
            ])
            ->join('users_route_import', 'users.id', 'users_route_import.id_user')
            ->where('users_route_import.id_route_import', $id_route_import);

        if (Auth::user()->hasAnyRole(['BackOffice', 'BU Manager'])) {
            $query->whereIn('users.type_user', ['FrontOffice', 'BackOffice', 'BU Manager']);
        }

        return $query->get();
    }

    /*
     |--------------------------------------------------------------------------
     | clientsByStatus: unified conditional logic
     |--------------------------------------------------------------------------
     */
    public static function clientsByStatus(Request $request, int $id_route_import)
    {
        $status = $request->get('status');

        $query  =   Client::where('clients.id_route_import', $id_route_import)
                        ->where('clients.status', $status)
                        ->with('ownerUser');   // ownerUser relationship on Client (add in Client.php)

        if ($status !== 'visible') {
            $query->where('clients.owner', Auth::id());
        } else {
            $query->whereIn('clients.CityNo', function ($sub) {
                $sub->select('CITYNO')->from('users_cities')->where('id_user', Auth::id());
            });
        }

        $clients = $query->get();

        $clients->each(function ($client) {
            $client->owner_username = $client->ownerUser->username ?? null;
            Client::appendFormattedBrands($client);
        });

        return $clients;
    }

    /*
     |--------------------------------------------------------------------------
     | City helpers (left join with route_import_cities)
     |--------------------------------------------------------------------------
     */
    public static function routeImportCities(int $id_route_import, string $DistrictNo)
    {
        return DB::table('RTM_City')
            ->select('RTM_City.*', 'route_import_cities.expected_clients', 'route_import_cities.id_route_import')
            ->leftJoin('route_import_cities', function ($join) use ($id_route_import) {
                $join->on('RTM_City.CityNo', '=', 'route_import_cities.CityNo')
                    ->where('route_import_cities.id_route_import', $id_route_import);
            })
            ->where('RTM_City.DistrictNo', $DistrictNo)
            ->get();
    }

    /*
     |--------------------------------------------------------------------------
     | Downloads / Images: unified generic zipper for types
     |--------------------------------------------------------------------------
     */
    protected static function makeZipFromPaths(array $paths, string $zipFile, string $folderNamePrefix = ''): string
    {
        $zip = new ZipArchive();

        $zipPath = public_path($zipFile);

        if ($zip->open($zipPath, ZipArchive::CREATE) !== true) {
            throw new Exception('Unable to create zip file: ' . $zipPath);
        }

        foreach ($paths as $path) {
            $abs = public_path($path);
            if (! file_exists($abs)) continue;
            $name = basename($abs);
            $pathInZip = trim($folderNamePrefix, '/') . '/' . $name;
            $zip->addFile($abs, $pathInZip);
        }

        $zip->close();

        return $zipPath;
    }

    public static function downloadImages(Request $request)
    {
        $routeImport = self::findOrFail($request->get('id_route_import'));
        $images = self::prepareImagesExport($request);

        $paths = array_merge($images->customer_bar_code_images, $images->in_store_images, $images->facade_images);

        $zipFile = $routeImport->libelle . ' Images.zip';

        return self::makeZipFromPaths($paths, $zipFile, 'Images');
    }

    // Specifics
    public static function downloadCustomerCodeImages(Request $request)
    {
        $routeImport = self::findOrFail($request->get('id_route_import'));
        $images = self::prepareCustomerCodeImagesExport($request);

        $zipFile = $routeImport->libelle . ' Customer Code Images.zip';

        return self::makeZipFromPaths($images->customer_bar_code_images, $zipFile, 'CustomerBarCode Images');
    }

    public static function downloadFacadeImages(Request $request)
    {
        $routeImport = self::findOrFail($request->get('id_route_import'));
        $images = self::prepareFacadeImagesExport($request);

        $zipFile = $routeImport->libelle . ' Facade Images.zip';

        return self::makeZipFromPaths($images->facade_images, $zipFile, 'Facade Images');
    }

    public static function downloadInStoreImages(Request $request)
    {
        $routeImport = self::findOrFail($request->get('id_route_import'));
        $images = self::prepareInStoreImagesExport($request);

        $zipFile = $routeImport->libelle . ' In Store Images.zip';

        return self::makeZipFromPaths($images->in_store_images, $zipFile, 'In Store Images');
    }

    // Prepare helpers unchanged except array initialization simplified
    public static function prepareImagesExport(Request $request)
    {
        $images = new stdClass();
        $images->customer_bar_code_images = [];
        $images->in_store_images = [];
        $images->facade_images = [];

        $routeId = $request->get('id_route_import');

        Client::where('id_route_import', $routeId)
            ->select(['id','CustomerBarCode_image','in_store_image','facade_image'])
            ->chunkById(500, function($clients) use (&$images) {
                foreach ($clients as $client) {
                    if (!empty($client->CustomerBarCode_image)) {
                        $path = '/uploads/clients/'.$client->id.'/'.$client->CustomerBarCode_image;
                        if (file_exists(public_path($path))) $images->customer_bar_code_images[] = $path;
                    }
                    if (!empty($client->in_store_image)) {
                        $path = '/uploads/clients/'.$client->id.'/'.$client->in_store_image;
                        if (file_exists(public_path($path))) $images->in_store_images[] = $path;
                    }
                    if (!empty($client->facade_image)) {
                        $path = '/uploads/clients/'.$client->id.'/'.$client->facade_image;
                        if (file_exists(public_path($path))) $images->facade_images[] = $path;
                    }
                }
            });

        return $images;
    }

    public static function prepareCustomerCodeImagesExport(Request $request)
    {
        $images = new stdClass();
        $images->customer_bar_code_images = [];

        $clients = Client::clientsExport($request);

        foreach ($clients as $client) {
            if ($client->CustomerBarCode_image) {
                $path = '/uploads/clients/' . $client->id . '/' . $client->CustomerBarCode_image;
                if (file_exists(public_path($path))) $images->customer_bar_code_images[] = $path;
            }
        }

        return $images;
    }

    public static function prepareFacadeImagesExport(Request $request)
    {
        $images = new stdClass();
        $images->facade_images = [];

        $clients = Client::clientsExport($request);

        foreach ($clients as $client) {
            if ($client->facade_image) {
                $path = '/uploads/clients/' . $client->id . '/' . $client->facade_image;
                if (file_exists(public_path($path))) $images->facade_images[] = $path;
            }
        }

        return $images;
    }

    public static function prepareInStoreImagesExport(Request $request)
    {
        $images = new stdClass();
        $images->in_store_images = [];

        $clients = Client::clientsExport($request);

        foreach ($clients as $client) {
            if ($client->in_store_image) {
                $path = '/uploads/clients/' . $client->id . '/' . $client->in_store_image;
                if (file_exists(public_path($path))) $images->in_store_images[] = $path;
            }
        }

        return $images;
    }
}