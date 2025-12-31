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

    protected $table        =   'route_import';
    protected $primaryKey   =   'id';
    protected $guarded      =   [];

    public $timestamps      =   false;

    //  //  //  //  //
    //  //  //  //  //  Scopes
    //  //  //  //  //

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

    //  //  //  //  //
    //  //  //  //  //  Relationships
    //  //  //  //  //

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

    public function journeyPlanTerritories()
    {
        return $this->hasMany(JourneyPlanTerritory::class, 'id_route_import', 'id');
    }

    public function journeeTerritories()
    {
        return $this->hasMany(JourneeTerritory::class, 'id_route_import', 'id');
    }

    //  //  //  //  //
    //  //  //  //  //  Listings
    //  //  //  //  //

    public static function indexRouteImport()
    {
        return self::orderBy('id', 'desc')->permittedByRole()->get();
    }

    public static function comboRouteImport()
    {
        return self::orderBy('id', 'desc')->permittedByRole()->get();
    }

    public static function detailsRouteImport()
    {
        // Eager load clients and owner username via join to avoid N+1
        $routeImports   =   self::with(['clientsRelation' => function ($q) {
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

    public static function showRouteImport(int $id_route_import)
    {
        $routeImport = self::with(['files'])->findOrFail($id_route_import);

        // Eager load clients with owner username
        $clients    =   Client::where('id_route_import', $id_route_import)
                            ->with('ownerUser')   // ownerUser relationship on Client (add in Client.php)
                            ->get();

        $clients->each(function ($client) {
            $client->owner_username = $client->ownerUser->username ?? null;
            Client::appendFormattedBrands($client);
        });

        $routeImport->clients = $clients;
        $routeImport->liste_journey_plan = JourneyPlanTerritory::where('id_route_import', $id_route_import)->get();
        $routeImport->liste_journee = JourneeTerritory::where('id_route_import', $id_route_import)->get();

        return $routeImport;
    }

    //  //  //  //  //
    //  //  //  //  //  Store/Update/Delete
    //  //  //  //  //


    public static function validateStore(Request $request)
    {
        return Validator::make($request->all(), [
            'id_route_import_tempo' => ['required', 'max:255'],
            'libelle' => ['required', 'max:255'],
            'districts' => ['required'],
        ]);
    }

    public static function storeRouteImport(Request $request)
    {
        $userId = Auth::id();

        $routeImport = self::create([
            'libelle' => $request->input('libelle'),
            'owner' => $userId,
        ]);

        // Move uploaded file from tempo to route_import directory (use Storage when possible)
        $tempoId = $request->get('id_route_import_tempo');
        // $tempo = RouteImportTempo::find($tempoId);

        // if ($tempo && $tempo->file) {
        //     $from = public_path("uploads/route_import_tempo/{$userId}/{$tempo->file}");
        //     $toDir = public_path("uploads/route_import/{$userId}");

        //     if (! File::exists($toDir)) {
        //         File::makeDirectory($toDir, 0755, true);
        //     }

        //     $to = $toDir . '/' . $tempo->file;

        //     if (File::exists($from)) {
        //         File::move($from, $to);
        //     }

        //     RouteImportFile::create([
        //         'id_route_import' => $routeImport->id,
        //         'file' => $tempo->file,
        //     ]);
        // }

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

    public static function validateUpdate(Request $request)
    {
        return Validator::make($request->all(), [
            // 'file' => ['required', 'file', 'mimes:xlsx,xls'],
        ]);
    }

    public static function updateRouteImport(Request $request, int $id_route_import)
    {
        // $fileName = uniqid() . '.' . $request->file->getClientOriginalExtension();
        // $request->file->move(public_path("uploads/route_import/" . Auth::id()), $fileName);

        // RouteImportFile::create([
        //     'id_route_import' => $id,
        //     'file' => $fileName,
        // ]);

        $request->merge([
            'clients' => json_decode($request->get('data'), true),
        ]);

        self::updateData($request, $id_route_import);
    }

    public static function deleteRouteImport(int $id_route_import)
    {
        $routeImport = self::findOrFail($id_route_import);

        DB::transaction(function () use ($routeImport, $id_route_import) {
            $routeImport->delete();
            self::deleteData($id_route_import);
        });
    }

    //  //  //  //  //
    //  //  //  //  //  Delete Clients
    //  //  //  //  //

    public static function deleteData(int $id_route_import)
    {
        // $routeImportFiles = RouteImportFile::where('id_route_import', $id)->get();

        // foreach ($routeImportFiles as $file) {
        //     if ($file->file) {
        //         RouteImportFile::deleteRouteImportFile($file->file);
        //     }
        //     $file->delete();
        // }

        // Delete clients and related assets in chunks
        Client::where('id_route_import', $id_route_import)->chunkById(100, function ($clients) {
            foreach ($clients as $client) {
                // If you want to delete uploaded directories, use Storage or File::deleteDirectory
                $client->delete();
            }
        });

        RouteImportDistrict::where('id_route_import', $id_route_import)->delete();
        JourneyPlanTerritory::where('id_route_import', $id_route_import)->delete();
        JourneeTerritory::where('id_route_import', $id_route_import)->delete();
    }

    //  //  //  //  //
    //  //  //  //  //  Obs
    //  //  //  //  //

    public static function obsDetailsRouteImport(int $id_route_import)
    {
        return self::showRouteImport($id_route_import);
    }

    public static function obsDetailsRouteImportFrontOffice(int $id_route_import)
    {
        $routeImport = self::findOrFail($id_route_import);

        $data = Client::query()
            ->where('clients.id_route_import', $id_route_import)
            ->where(function ($q) {
                $q->where('clients.owner', Auth::id())
                    ->orWhere(function ($q2) {
                        $q2->where('clients.status', 'visible')
                            ->whereIn('clients.CityNo', function ($sub) {
                                $sub->select('CityNo')
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

    //  //  //  //  //
    //  //  //  //  //  Clients
    //  //  //  //  //

    public static function clients(int $id_route_import)
    {
        $request = request();

        $start = $request->filled('start_date') ? Carbon::parse($request->start_date)->format('Y-m-d') : null;
        $end = $request->filled('end_date') ? Carbon::parse($request->end_date)->format('Y-m-d') : null;

        $query = Client::where('id_route_import', $id_route_import);

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
                $sub->select('CityNo')->from('users_cities')->where('id_user', Auth::id());
            });
        }

        $clients = $query->orderBy('id', 'desc')->get();

        $clients->each(function ($client) {
            $client->owner_username = $client->ownerUser->username ?? null;
            Client::appendFormattedBrands($client);
        });

        return $clients;
    }

    //  //  //  //  //
    //  //  //  //  //  Users
    //  //  //  //  //

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

    //  //  //  //  //
    //  //  //  //  //  Cities/Willayas
    //  //  //  //  //

    public static function routeImportCities(int $id_route_import)
    {
        return  DB::table("RTM_City")
                    ->select("RTM_City.*")
                    ->join("RTM_Willaya"            , "RTM_City.DistrictNo"         , "RTM_Willaya.DistrictNo")
                    ->join("route_import_districts" , "RTM_Willaya.DistrictNo"      , "route_import_districts.DistrictNo")
                    ->where('route_import_districts.id_route_import', $id_route_import)
                    ->orderByRaw('CAST(RTM_City.CityNo AS SIGNED INTEGER)')
                    ->get();
    }

    public static function routeImportDistricts(int $id_route_import)
    {
        return  DB::table("RTM_Willaya")
                    ->join("route_import_districts" , "RTM_Willaya.DistrictNo"          , "route_import_districts.DistrictNo")
                    ->where('route_import_districts.id_route_import', $id_route_import)
                    ->orderBy('RTM_Willaya.DistrictNameE')
                    ->get();
    }

    //  //  //  //  //
    //  //  //  //  //  Downloads
    //  //  //  //  //

    public static function downloadImages(Request $request, string $type = 'all')
    {
        // Configuration mapping for handling different image types
        $IMAGE_CONFIG = [
            'all' => [
                'columns' => ['CustomerBarCode_image', 'in_store_image', 'facade_image'],
                'zip_suffix' => 'Images',
                'folder_prefix' => 'Images'
            ],
            'customer_code' => [
                'columns' => ['CustomerBarCode_image'],
                'zip_suffix' => 'Customer Code Images',
                'folder_prefix' => 'CustomerBarCode Images'
            ],
            'facade' => [
                'columns' => ['facade_image'],
                'zip_suffix' => 'Facade Images',
                'folder_prefix' => 'Facade Images'
            ],
            'in_store' => [
                'columns' => ['in_store_image'],
                'zip_suffix' => 'In Store Images',
                'folder_prefix' => 'In Store Images'
            ],
        ];

        // 1. Validate Type
        $config = $IMAGE_CONFIG[$type] ?? $IMAGE_CONFIG['all'];

        // 2. Fetch Route Info
        $routeImport = self::findOrFail($request->get('id_route_import'));
        
        // 3. Gather Paths from DB
        $paths = self::gatherImagePaths($request->get('id_route_import'), $config['columns']);

        // 4. Generate Zip Name
        $zipFileName = $routeImport->libelle . ' ' . $config['zip_suffix'] . '.zip';

        // 5. Create Zip
        return self::makeZipFromPaths($paths, $zipFileName, $config['folder_prefix']);
    }

    protected static function gatherImagePaths(int $routeId, array $columns): array
    {
        $validPaths = [];

        // Always select ID plus the dynamic columns requested
        $selectColumns = array_merge(['id'], $columns);

        // chunkById is used to prevent memory exhaustion on large datasets
        Client::where('id_route_import', $routeId)
            ->select($selectColumns)
            ->chunkById(500, function ($clients) use ($columns, &$validPaths) {
                foreach ($clients as $client) {
                    foreach ($columns as $col) {
                        // Check if DB column has value
                        if (!empty($client->$col)) {
                            $path = '/uploads/clients/' . $client->id . '/' . $client->$col;
                            // Check if physical file exists (expensive operation, handle with care)
                            if (file_exists(public_path($path))) {
                                $validPaths[] = $path;
                            }
                        }
                    }
                }
            });

        return $validPaths;
    }

    protected static function makeZipFromPaths(array $paths, string $zipFile, string $folderNamePrefix = ''): string
    {
        $zip = new ZipArchive();
        $zipPath = public_path($zipFile);

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new Exception('Unable to create zip file: ' . $zipPath);
        }

        foreach ($paths as $path) {
            $abs = public_path($path);
            // We already checked file_exists in gatherImagePaths, but a double check doesn't hurt
            if (!file_exists($abs)) continue;
            
            $name = basename($abs);
            // Ensure clean folder path inside zip
            $pathInZip = trim($folderNamePrefix, '/') . '/' . $name;
            $zip->addFile($abs, $pathInZip);
        }

        $zip->close();

        return $zipPath;
    }
}