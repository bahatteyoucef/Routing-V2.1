<?php

namespace App\Models;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use Laravel\Passport\HasApiTokens;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Fluent;
use Illuminate\Validation\Rule;

use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Role;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $guarded      = [];
    protected $table        = 'users';
    protected $primaryKey   = 'id';
    public    $timestamps   = false;

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /*********************
     * Relationships
     *********************/

    // pivot users_route_import: id_user, id_route_import
    public function routeImports()
    {
        return $this->belongsToMany(RouteImport::class, 'users_route_import', 'id_user', 'id_route_import');
    }

    public function userRouteImports()
    {
        return $this->hasMany(UserRouteImport::class, 'id_user', 'id');
    }

    /*********************
     * Index & combos
     *********************/

    public static function indexUser()
    {
        $me = Auth::user();
        if (! $me || (! $me->hasRole('Super Admin') && ! $me->hasRole('BU Manager'))) {
            throw new Exception('Unauthorized', 403);
        }

        // Build base select to reuse
        $select = [
            'users.id',
            'users.username',
            'users.first_name',
            'users.last_name',
            'users.email',
            'users.tel',
            'users.company',
            'users.type_user',
            'users.accuracy',
            'users.max_route_import',
            'users.password_non_hashed',
            'users.owner',
        ];

        $query = DB::table('users')->select($select);

        if ($me->hasRole('BU Manager')) {
            $query->where('users.owner', $me->id);
        }

        return $query->get();
    }

    public static function comboUser()
    {
        $me = Auth::user();

        $select = [
            'users.id',
            'users.username',
            'users.first_name',
            'users.last_name',
            'users.email',
            'users.tel',
            'users.company',
            'users.type_user',
            'users.accuracy',
            'users.max_route_import',
            'users.password_non_hashed',
            'users.owner',
        ];

        $query = DB::table('users')->select($select);

        if ($me && $me->hasRole('BU Manager')) {
            $query->where('users.owner', $me->id);
        } elseif ($me && $me->hasRole('BackOffice')) {
            // Get route ids once
            $routeIds = DB::table('users_route_import')->where('id_user', $me->id)->pluck('id_route_import')->toArray();
            if (empty($routeIds)) {
                return collect(); // no accessible users
            }

            $query->join('users_route_import as uri', 'uri.id_user', '=', 'users.id')
                  ->whereIn('uri.id_route_import', $routeIds)
                  ->groupBy('users.id');
        }

        return $query->get();
    }

    public static function comboBackOffice()
    {
        $me = Auth::user();

        // Start from users with BackOffice role via role table join (keeps original intent)
        $select = [
            'users.id',
            'users.username',
            'users.first_name',
            'users.last_name',
            'users.email',
            'users.tel',
            'users.company',
            'users.type_user',
            'users.accuracy',
            'users.max_route_import',
            'users.password_non_hashed',
            'users.owner',
        ];

        $query = DB::table('users')
            ->select($select)
            ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->where([['roles.name', 'BackOffice'], ['model_has_roles.model_type', 'App\Models\User']]);

        if ($me && $me->hasRole('BU Manager')) {
            $query->where('users.owner', $me->id);
        } elseif ($me && $me->hasRole('BackOffice')) {
            // Limit to route imports assigned to this backoffice user
            $routeIds = DB::table('users_route_import')->where('id_user', $me->id)->pluck('id_route_import')->toArray();
            if (! empty($routeIds)) {
                $query->join('users_route_import as uri', 'uri.id_user', '=', 'users.id')
                      ->whereIn('uri.id_route_import', $routeIds);
            } else {
                return collect();
            }
        }

        return $query->get();
    }

    /*********************
     * Validation & store/update
     *********************/

    public static function validateStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => ['required', 'alpha_num', Rule::unique('users', 'username')],
            'first_name' => ['required', 'max:255'],
            'last_name' => ['required', 'max:255'],
            'email' => ['required', Rule::unique('users')->whereNotNull('email'), 'email', 'max:255'],
            'tel' => ['required', 'max:255'],
            'company' => ['required', 'max:255'],
            'type_user' => ['required', 'max:255'],
            'password' => ['required', 'confirmed', 'min:6', 'max:255'],
        ]);

        $validator->sometimes('max_route_import', ['required', 'integer'], function (Fluent $input) {
            return ($input->type_user == 'BU Manager');
        });

        $validator->sometimes('accuracy', ['required', 'numeric', 'min:0'], function (Fluent $input) {
            return ($input->type_user == 'FrontOffice');
        });

        return $validator;
    }

    public static function storeUser(Request $request)
    {
        $me = Auth::user();
        if (! $me) throw new Exception('Unauthorized', 403);

        DB::transaction(function () use ($request, $me) {
            $userData = [
                'username' => $request->input('username'),
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'email' => $request->input('email'),
                'tel' => $request->input('tel'),
                'company' => $request->input('company'),
                'type_user' => $request->input('type_user'),
                'password_non_hashed' => $request->input('password'),
                'password' => Hash::make($request->input('password')),
                'owner' => $me->id,
            ];

            $user = self::create($userData);

            // Handle assignment of route imports in bulk (if provided)
            $liste_route_import = $request->input('liste_route_import');
            $routeIds = [];
            if ($liste_route_import) {
                // Accept JSON string or array
                $routeIds = is_string($liste_route_import) ? json_decode($liste_route_import, true) : (array)$liste_route_import;
                $routeIds = array_filter($routeIds);
            }

            // Role assignment and specific attributes per type_user
            $type = $request->input('type_user');

            switch ($type) {
                case 'BU Manager':
                    $user->syncRoles(['BU Manager']);
                    // set max_route_import if provided
                    if ($request->has('max_route_import')) {
                        $user->max_route_import = (int) $request->input('max_route_import');
                        $user->save();
                    }
                    // assign route imports pivot in bulk
                    if (! empty($routeIds)) {
                        $insert = [];
                        foreach ($routeIds as $rid) {
                            $insert[] = ['id_user' => $user->id, 'id_route_import' => $rid];
                        }
                        UserRouteImport::insert($insert);
                    }
                    break;

                case 'BackOffice':
                    $user->syncRoles(['BackOffice']);
                    if (! empty($routeIds)) {
                        $insert = [];
                        foreach ($routeIds as $rid) {
                            $insert[] = ['id_user' => $user->id, 'id_route_import' => $rid];
                        }
                        UserRouteImport::insert($insert);
                    }
                    break;

                case 'Viewer':
                    $user->syncRoles(['Viewer']);
                    if (! empty($routeIds)) {
                        $insert = [];
                        foreach ($routeIds as $rid) {
                            $insert[] = ['id_user' => $user->id, 'id_route_import' => $rid];
                        }
                        UserRouteImport::insert($insert);
                    }
                    break;

                case 'FrontOffice':
                    $user->syncRoles(['FrontOffice']);
                    if ($request->filled('selected_route_import') && $request->input('selected_route_import') !== 'null') {
                        UserRouteImport::create([
                            'id_user' => $user->id,
                            'id_route_import' => $request->input('selected_route_import'),
                        ]);
                    }
                    if ($request->has('accuracy')) {
                        $user->accuracy = $request->input('accuracy');
                        $user->save();
                    }
                    break;

                default:
                    // If an unknown type is passed, leave role assignment empty (or handle accordingly)
                    break;
            }
        });
    }

    public static function validateUpdate(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'username' => ['required', 'alpha_num', Rule::unique('users', 'username')->ignore($id)],
            'first_name' => ['required', 'max:255'],
            'last_name' => ['required', 'max:255'],
            'email' => ['required', Rule::unique('users')->ignore($id), 'email', 'max:255'],
            'tel' => ['required', 'max:255'],
            'company' => ['required', 'max:255'],
            'type_user' => ['required', 'max:255'],
        ]);

        $validator->sometimes('max_route_import', ['required', 'integer'], function (Fluent $input) {
            return ($input->type_user == 'BU Manager');
        });

        $validator->sometimes('accuracy', ['required', 'numeric', 'min:0'], function (Fluent $input) {
            return ($input->type_user == 'FrontOffice');
        });

        return $validator;
    }

    public static function updateUser(Request $request, int $id)
    {
        $user = self::findOrFail($id);

        DB::transaction(function () use ($request, $user) {
            // mass assign safe properties explicitly
            $fields = ['username', 'first_name', 'last_name', 'email', 'tel', 'company', 'type_user', 'status'];
            foreach ($fields as $f) {
                if ($request->has($f)) $user->{$f} = $request->input($f);
            }
            $user->save();

            // Reset user->route_import relations
            UserRouteImport::where('id_user', $user->id)->delete();

            // Re-attach according to type_user
            $type = $request->input('type_user');

            $liste_route_import = $request->input('liste_route_import');
            $routeIds = [];
            if ($liste_route_import) {
                $routeIds = is_string($liste_route_import) ? json_decode($liste_route_import, true) : (array)$liste_route_import;
                $routeIds = array_filter($routeIds);
            }

            switch ($type) {
                case 'BU Manager':
                    if (!empty($routeIds)) {
                        $insert = array_map(fn($rid) => ['id_user' => $user->id, 'id_route_import' => $rid], $routeIds);
                        UserRouteImport::insert($insert);
                    }
                    $user->syncRoles(['BU Manager']);
                    if ($request->has('max_route_import')) {
                        $user->max_route_import = (int)$request->input('max_route_import');
                        $user->save();
                    }
                    break;

                case 'BackOffice':
                    if (!empty($routeIds)) {
                        $insert = array_map(fn($rid) => ['id_user' => $user->id, 'id_route_import' => $rid], $routeIds);
                        UserRouteImport::insert($insert);
                    }
                    $user->syncRoles(['BackOffice']);
                    break;

                case 'Viewer':
                    if (!empty($routeIds)) {
                        $insert = array_map(fn($rid) => ['id_user' => $user->id, 'id_route_import' => $rid], $routeIds);
                        UserRouteImport::insert($insert);
                    }
                    $user->syncRoles(['Viewer']);
                    break;

                case 'FrontOffice':
                    if ($request->filled('selected_route_import') && $request->input('selected_route_import') !== 'null') {
                        UserRouteImport::create([
                            'id_user' => $user->id,
                            'id_route_import' => $request->input('selected_route_import'),
                        ]);
                    }
                    $user->syncRoles(['FrontOffice']);
                    if ($request->has('accuracy')) {
                        $user->accuracy = $request->input('accuracy');
                        $user->save();
                    }
                    break;

                default:
                    // no special handling
                    break;
            }
        });
    }

    public static function showUser(int $id)
    {
        $user = self::findOrFail($id);
        // use the belongsToMany relation to retrieve route imports
        $user->liste_route_import = $user->routeImports()->get();
        return $user;
    }

    /*********************
     * Password helpers
     *********************/

    public static function validatechangePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => ['required', 'current_password:api'],
            'new_password' => ['required', 'confirmed', 'min:6', 'max:255'],
        ]);
        return $validator;
    }

    public static function changePassword(Request $request, int $id)
    {
        $user = self::findOrFail($id);
        $user->password = Hash::make($request->input('new_password'));
        $user->save();
    }

    /*********************
     * Pointings (report)
     *********************/

    public static function pointings(Request $request)
    {
        // parse filters
        $route_links_raw = $request->get('route_links');
        $selected_users_raw = $request->get('selected_users');

        $route_links = $route_links_raw ? (array) json_decode($route_links_raw, true) : [];
        $selected_users = $selected_users_raw ? (array) json_decode($selected_users_raw, true) : [];

        $route_links = array_values(array_filter($route_links, fn($v) => $v !== null && $v !== ''));
        $selected_users = array_values(array_filter($selected_users, fn($v) => $v !== null && $v !== ''));

        try {
            $startDate = Carbon::parse($request->get('start_date'))->startOfDay();
            $endDate   = Carbon::parse($request->get('end_date'))->endOfDay();
        } catch (\Throwable $e) {
            return response()->json([], 200);
        }

        // Build base client query
        $baseClientQuery = Client::query()
            ->whereBetween('clients.updated_at', [$startDate, $endDate]);

        if (!empty($route_links)) {
            $baseClientQuery->whereIn('id_route_import', $route_links);
        }

        if (!empty($selected_users)) {
            $baseClientQuery->whereIn('owner_bo', $selected_users);
            $ownerIds = $selected_users;
        } else {
            // determine owners with BackOffice or BU Manager roles (limit scope)
            $ownerIds = User::role(['BackOffice', 'BU Manager'])->pluck('id')->toArray();
        }

        if (empty($ownerIds)) {
            return response()->json([], 200);
        }

        // per-user totals
        $totalsQuery = (clone $baseClientQuery)
            ->whereIn('owner_bo', $ownerIds)
            ->select([
                'owner_bo',
                DB::raw("SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as number_clients_pending"),
                DB::raw("SUM(CASE WHEN status = 'confirmed' THEN 1 ELSE 0 END) as number_clients_confirmed"),
                DB::raw("SUM(CASE WHEN status = 'validated' THEN 1 ELSE 0 END) as number_clients_validated"),
                DB::raw("SUM(CASE WHEN status = 'nonvalidated' THEN 1 ELSE 0 END) as number_clients_nonvalidated"),
                DB::raw("SUM(CASE WHEN status = 'visible' THEN 1 ELSE 0 END) as number_clients_visible"),
                DB::raw("SUM(CASE WHEN status = 'ferme' THEN 1 ELSE 0 END) as number_clients_ferme"),
                DB::raw("SUM(CASE WHEN status = 'refus' THEN 1 ELSE 0 END) as number_clients_refus"),
                DB::raw("SUM(CASE WHEN status = 'introuvable' THEN 1 ELSE 0 END) as number_clients_introuvable"),
                DB::raw("TIME_FORMAT(TIME(MIN(clients.updated_at)), '%H:%i:%s') as start_time"),
                DB::raw("TIME_FORMAT(TIME(MAX(clients.updated_at)), '%H:%i:%s') as end_time"),
            ])
            ->groupBy('owner_bo');

        $perUserTotals = $totalsQuery->get()->keyBy('owner_bo');

        // per-user per-day
        $perDayQuery = (clone $baseClientQuery)
            ->whereIn('owner_bo', $ownerIds)
            ->select([
                'owner_bo',
                DB::raw('DATE(clients.updated_at) as day'),
                DB::raw("SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as number_clients_pending"),
                DB::raw("SUM(CASE WHEN status = 'confirmed' THEN 1 ELSE 0 END) as number_clients_confirmed"),
                DB::raw("SUM(CASE WHEN status = 'validated' THEN 1 ELSE 0 END) as number_clients_validated"),
                DB::raw("SUM(CASE WHEN status = 'nonvalidated' THEN 1 ELSE 0 END) as number_clients_nonvalidated"),
                DB::raw("SUM(CASE WHEN status = 'visible' THEN 1 ELSE 0 END) as number_clients_visible"),
                DB::raw("SUM(CASE WHEN status = 'ferme' THEN 1 ELSE 0 END) as number_clients_ferme"),
                DB::raw("SUM(CASE WHEN status = 'refus' THEN 1 ELSE 0 END) as number_clients_refus"),
                DB::raw("SUM(CASE WHEN status = 'introuvable' THEN 1 ELSE 0 END) as number_clients_introuvable"),
                DB::raw("TIME_FORMAT(TIME(MIN(clients.updated_at)), '%H:%i:%s') as start_time"),
                DB::raw("TIME_FORMAT(TIME(MAX(clients.updated_at)), '%H:%i:%s') as end_time"),
            ])
            ->groupBy('owner_bo', DB::raw('DATE(clients.updated_at)'));

        $perUserPerDay = $perDayQuery->get()->groupBy('owner_bo');

        // determine owners to include as User models
        $users = self::whereIn('id', $ownerIds)->get();

        // Build date period
        $period = CarbonPeriod::create($startDate, $endDate)->toArray();

        // Map into final pointings
        $pointings = $users->map(function ($u) use ($perUserTotals, $perUserPerDay, $period, $route_links, $startDate, $endDate) {
            $owner = $u->id;

            $tot = $perUserTotals->get($owner);
            $u->details = $tot ? $tot : (object)[
                'number_clients_pending' => 0,
                'number_clients_confirmed' => 0,
                'number_clients_validated' => 0,
                'number_clients_nonvalidated' => 0,
                'number_clients_visible' => 0,
                'number_clients_ferme' => 0,
                'number_clients_refus' => 0,
                'number_clients_introuvable' => 0,
                'start_time' => null,
                'end_time' => null,
            ];

            // fetch clients for this owner (apply route filter if present)
            $clientsQuery = Client::where('owner_bo', $owner)
                ->whereBetween('clients.updated_at', [$startDate, $endDate])
                ->join('users as bo', 'clients.owner_bo', '=', 'bo.id')
                ->select('clients.*', 'bo.username as owner_username');

            if (!empty($route_links)) {
                $clientsQuery->whereIn('clients.id_route_import', $route_links);
            }

            $u->clients = $clientsQuery->get();

            // days fill
            $ownerDays = $perUserPerDay->has($owner) ? $perUserPerDay->get($owner)->keyBy('day') : collect();
            $days = [];
            foreach ($period as $date) {
                $d = $date->format('Y-m-d');
                if ($ownerDays->has($d)) {
                    $r = $ownerDays->get($d);
                    $days[] = (object)[
                        'day' => $d,
                        'number_clients_pending' => (int)$r->number_clients_pending,
                        'number_clients_confirmed' => (int)$r->number_clients_confirmed,
                        'number_clients_validated' => (int)$r->number_clients_validated,
                        'number_clients_nonvalidated' => (int)$r->number_clients_nonvalidated,
                        'number_clients_visible' => (int)$r->number_clients_visible,
                        'number_clients_ferme' => (int)$r->number_clients_ferme,
                        'number_clients_refus' => (int)$r->number_clients_refus,
                        'number_clients_introuvable' => (int)$r->number_clients_introuvable,
                        'start_time' => $r->start_time,
                        'end_time' => $r->end_time,
                    ];
                } else {
                    $days[] = (object)[
                        'day' => $d,
                        'number_clients_pending' => 0,
                        'number_clients_confirmed' => 0,
                        'number_clients_validated' => 0,
                        'number_clients_nonvalidated' => 0,
                        'number_clients_visible' => 0,
                        'number_clients_ferme' => 0,
                        'number_clients_refus' => 0,
                        'number_clients_introuvable' => 0,
                        'start_time' => null,
                        'end_time' => null,
                    ];
                }
            }

            $u->days = $days;

            $u->total_clients =
                (int)($u->details->number_clients_pending ?? 0) +
                (int)($u->details->number_clients_confirmed ?? 0) +
                (int)($u->details->number_clients_validated ?? 0) +
                (int)($u->details->number_clients_nonvalidated ?? 0) +
                (int)($u->details->number_clients_visible ?? 0) +
                (int)($u->details->number_clients_ferme ?? 0) +
                (int)($u->details->number_clients_refus ?? 0) +
                (int)($u->details->number_clients_introuvable ?? 0);

            return $u;
        })->sortByDesc('total_clients')->values();

        return $pointings;
    }
}