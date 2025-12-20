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

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $guarded      =   [];

    protected $table        =   'users';
    protected $primaryKey   =   'id';

    public    $timestamps   =   false;

    // 

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    //

    public static function indexUser() {
    
        if((Auth::user()->hasRole("Super Admin"))||(Auth::user()->hasRole("BU Manager"))) {

            $query  =   DB::table('users')

                            ->select([ 
                                'users.id                   as  id'                     , 

                                'users.nom                  as  nom'                    ,
                                'users.email                as  email'                  ,

                                'users.tel                  as  tel'                    ,
                                'users.company              as  company'                ,

                                'users.type_user            as  type_user'              ,

                                'users.accuracy             as  accuracy'               ,

                                'users.max_route_import     as  max_route_import'       ,

                                'users.password_non_hashed  as  password_non_hashed'    ,

                                'users.owner                as  owner'   
                            ]);

            if(Auth::user()->hasRole("BU Manager")) {

                $query  =   $query->where("owner", Auth::user()->id);
            }

            return $query->get();
        }

        else {

            throw new Exception("Unauthorized", 403);
        }
    }

    public static function comboUser() {
    
        $query  =   DB::table('users')
                        ->select([
                            'users.id                   as id',
                            'users.nom                  as nom',
                            'users.email                as email',
                            'users.tel                  as tel',
                            'users.company              as company',
                            'users.type_user            as type_user',
                            'users.accuracy             as accuracy',
                            'users.max_route_import     as max_route_import',
                            'users.password_non_hashed  as password_non_hashed',
                            'users.owner                as owner',
                        ]);

        //
        if (Auth::user()->hasRole('BU Manager')) {
            $query->where('users.owner', Auth::user()->id);
        }

        elseif (Auth::user()->hasRole('BackOffice')) {

            $routeIds   =   DB::table('users_route_import')
                                ->where('id_user', Auth::user()->id)
                                ->pluck('id_route_import');

            //
            $query
                ->join('users_route_import as uri', 'uri.id_user', '=', 'users.id')
                ->whereIn('uri.id_route_import', $routeIds)
                ->groupBy('users.id');
        }

        //
        $users = $query->get();

        //
        return $users;
    }

    public static function comboBackOffice() {
        $query  =   DB::table('users')
                        ->select([
                            'users.id as id',
                            'users.nom as nom',
                            'users.email as email',
                            'users.tel as tel',
                            'users.company as company',
                            'users.type_user as type_user',
                            'users.accuracy as accuracy',
                            'users.max_route_import as max_route_import',
                            'users.password_non_hashed as password_non_hashed',
                            'users.owner as owner',
                        ])
                        ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
                        ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                        ->where([['roles.name', 'BackOffice'], ['model_has_roles.model_type', 'App\Models\User']]);

        // Keep your existing logic if you still need to filter by owner/routes
        if (Auth::user()->hasRole('BU Manager')) {
            $query->where('users.owner', Auth::user()->id);

        } elseif (Auth::user()->hasRole('BackOffice')) {
            $routeIds   =   DB::table('users_route_import')
                                ->where('id_user', Auth::user()->id)
                                ->pluck('id_route_import');

            $query->join('users_route_import as uri', 'uri.id_user', '=', 'users.id')
                ->whereIn('uri.id_route_import', $routeIds);
        }

        // Group by ID to avoid duplicates if a user has multiple roles (unlikely here but safe)
        return $query->get();
    }

    public static function validateStore(Request $request) {

        $validator = Validator::make($request->all(), [
            'nom'               =>  ["required", "alpha_num", Rule::unique('users', 'nom')],
            'email'             =>  ["required", Rule::unique('users')  , "email", "max:255"            ],
            'tel'               =>  ["required", "max:255"                                              ],
            'company'           =>  ["required", "max:255"                                              ],
            'type_user'         =>  ["required", "max:255"                                              ],
            'password'          =>  ["required", "confirmed"            , "min:6", "max:255"            ]
        ]);

        $validator->sometimes('max_route_import',  ["required", "integer"] , function (Fluent $input) {
    
            return ($input->type_user    ==  "BU Manager");
        });

        $validator->sometimes('accuracy',  ["required", "numeric", "min:0"] , function (Fluent $input) {
    
            return ($input->type_user    ==  "FrontOffice");
        });

        //
        return $validator;
    }

    public static function storeUser(Request $request) {
        
        $user = new User([
            'nom'                   =>  $request->input('nom')                  ,
            'email'                 =>  $request->input('email')                ,
            'tel'                   =>  $request->input('tel')                  ,
            'company'               =>  $request->input('company')              ,
            'type_user'             =>  $request->input('type_user')            ,

            'password_non_hashed'   =>  $request->input('password')             ,
            'password'              =>  Hash::make($request->input('password')) ,
            'owner'                 =>  Auth::user()->id
        ]);

        $user->save();

        //

        if($request->get("type_user")   ==  "BU Manager") {

            $liste_route_import     =   json_decode($request->get("liste_route_import"));

            if($liste_route_import  !=  null) {

                foreach ($liste_route_import as $route_import_elem) {

                    $route_import           =   new UserRouteImport([

                        'id_user'           =>  $user->id           ,
                        'id_route_import'   =>  $route_import_elem
                    ]);

                    $route_import->save();
                } 
            }

            $BUManagerRole = Role::findByName('BU Manager');
            $user->assignRole($BUManagerRole);

            //
            $user->max_route_import     =   $request->input('max_route_import');
            $user->save();
        }

        if($request->get("type_user")   ==  "BackOffice") {

            $liste_route_import     =   json_decode($request->get("liste_route_import"));

            if($liste_route_import  !=  null) {

                foreach ($liste_route_import as $route_import_elem) {

                    $route_import           =   new UserRouteImport([

                        'id_user'           =>  $user->id           ,
                        'id_route_import'   =>  $route_import_elem
                    ]);

                    $route_import->save();
                } 
            }

            $BackOfficeRole = Role::findByName('BackOffice');
            $user->assignRole($BackOfficeRole);
        }

        if($request->get("type_user")   ==  "Viewer") {

            $liste_route_import     =   json_decode($request->get("liste_route_import"));

            if($liste_route_import  !=  null) {

                foreach ($liste_route_import as $route_import_elem) {

                    $route_import           =   new UserRouteImport([

                        'id_user'           =>  $user->id           ,
                        'id_route_import'   =>  $route_import_elem
                    ]);

                    $route_import->save();
                } 
            }

            $ViewerRole = Role::findByName('Viewer');
            $user->assignRole($ViewerRole);
        }

        if($request->get("type_user")   ==  "FrontOffice") {

            if($request->get("selected_route_import")   !=  "null") {

                $route_import           =   new UserRouteImport([

                    'id_user'           =>  $user->id                                   ,
                    'id_route_import'   =>  $request->get("selected_route_import")
                ]);

                $route_import->save();
            }

            $FrontOfficeRole = Role::findByName('FrontOffice');
            $user->assignRole($FrontOfficeRole);

            //
            $user->accuracy     =   $request->input('accuracy');
            $user->save();
        }

        //
    }

    public static function validateUpdate(Request $request, int $id) {

        $validator = Validator::make($request->all(), [
            'nom'               =>  ["required", "alpha_num", Rule::unique('users', 'nom')->ignore($id)],
            'email'             =>  ["required", Rule::unique('users')->ignore($id), "email", "max:255" ],
            'tel'               =>  ["required", "max:255"                                              ],
            'company'           =>  ["required", "max:255"                                              ],
            'type_user'         =>  ["required", "max:255"                                              ]
        ]);

        $validator->sometimes('max_route_import',  ["required", "integer"] , function (Fluent $input) {
    
            return ($input->type_user    ==  "BU Manager");
        });

        $validator->sometimes('accuracy',  ["required", "numeric", "min:0"] , function (Fluent $input) {
    
            return ($input->type_user    ==  "FrontOffice");
        });
    
        return $validator;
    }

    public static function updateUser(Request $request, int $id) {

        $user                       =   User::find($id);

        $user->nom                  =   $request->input('nom');
        $user->email                =   $request->input('email');
        $user->tel                  =   $request->input('tel');
        $user->company              =   $request->input('company');
        $user->type_user            =   $request->input('type_user');
        $user->status               =   $request->input('status');

        $user->save();

        //

        UserRouteImport::where("id_user", $user->id)->delete();

        //

        if($request->get("type_user")   ==  "BU Manager") {

            $liste_route_import     =   json_decode($request->get("liste_route_import"));

            if($liste_route_import  !=  null) {

                foreach ($liste_route_import as $route_import_elem) {
                    
                    $route_import           =   new UserRouteImport([

                        'id_user'           =>  $user->id               ,
                        'id_route_import'   =>  $route_import_elem
                    ]);

                    $route_import->save();
                }
            }

            //
            $user->syncRoles(['BU Manager']);

            //
            $user->max_route_import     =   $request->input('max_route_import');
            $user->save();
        }

        if($request->get("type_user")   ==  "BackOffice") {

            $liste_route_import     =   json_decode($request->get("liste_route_import"));

            if($liste_route_import  !=  null) {

                foreach ($liste_route_import as $route_import_elem) {
                    
                    $route_import           =   new UserRouteImport([

                        'id_user'           =>  $user->id               ,
                        'id_route_import'   =>  $route_import_elem
                    ]);

                    $route_import->save();
                }
            }

            //
            $user->syncRoles(['BackOffice']);
        }

        if($request->get("type_user")   ==  "Viewer") {

            $liste_route_import     =   json_decode($request->get("liste_route_import"));

            if($liste_route_import  !=  null) {

                foreach ($liste_route_import as $route_import_elem) {
                    
                    $route_import           =   new UserRouteImport([

                        'id_user'           =>  $user->id               ,
                        'id_route_import'   =>  $route_import_elem
                    ]);

                    $route_import->save();
                }
            }

            //
            $user->syncRoles(['Viewer']);
        }

        if($request->get("type_user")   ==  "FrontOffice") {

            if($request->get("selected_route_import")   !=  "null") {

                $route_import           =   new UserRouteImport([

                    'id_user'           =>  $user->id                                   ,
                    'id_route_import'   =>  $request->get("selected_route_import")
                ]);

                $route_import->save();
            }

            $user->syncRoles(['FrontOffice']);

            //
            $user->accuracy     =   $request->input('accuracy');
            $user->save();
        }
    }

    public static function showUser(int $id) {
    
        //
        $user                       =   User::find($id);

        //
        $user->liste_route_import   =   DB::table('route_import')
                                            // ->pluck('users_route_import.id_route_import') 
                                            // ->toArray();
                                            ->select('route_import.*')
                                            ->join('users_route_import', 'route_import.id', 'users_route_import.id_route_import')
                                            ->where('users_route_import.id_user', $user->id) 
                                            ->get();

        return $user;
    }

    //

    public static function validatechangePassword(Request $request) {

        $validator = Validator::make($request->all(), [
            'old_password'          =>  ["required", "current_password:api"             ],
            'new_password'          =>  ["required", "confirmed"    , "min:6", "max:255"]
        ]);
    
        return $validator;
    }

    public static function changePassword(Request $request, int $id) {

        $user                   =   User::find($id);

        $user->password         =   Hash::make($request->input('new_password'));

        $user->save();
    }

    //

    public static function pointings(Request $request) {

        // decode optional JSON inputs (FormData gives them as strings)
        $route_links_raw    = $request->get('route_links');
        $selected_users_raw = $request->get('selected_users');

        $route_links    = $route_links_raw ? (array) json_decode($route_links_raw, true) : [];
        $selected_users = $selected_users_raw ? (array) json_decode($selected_users_raw, true) : [];

        // normalize and remove empty values
        $route_links    = array_values(array_filter($route_links, fn($v) => $v !== null && $v !== ''));
        $selected_users = array_values(array_filter($selected_users, fn($v) => $v !== null && $v !== ''));

        // parse dates
        try {
            $startDate = Carbon::parse($request->get('start_date'))->startOfDay();
            $endDate   = Carbon::parse($request->get('end_date'))->endOfDay();
        } catch (\Throwable $e) {
            return response()->json([], 200);
        }

        // Build base clients query for the given date range
        $clientsBaseQuery = Client::query()
            ->whereBetween('updated_at', [$startDate, $endDate]);

        // Apply filters only if they are NOT empty (i.e. optional filters)
        if (!empty($route_links)) {
            $clientsBaseQuery->whereIn('id_route_import', $route_links);
        }

        if (!empty($selected_users)) {
            $clientsBaseQuery->whereIn('owner_bo', $selected_users);
        }

        // If selected_users were explicitly provided, use them.
        // Otherwise derive owner IDs from clients and keep only those with role "BackOffice".
        if (!empty($selected_users)) {
            $ownerIds = $selected_users;
        } else {
            // keep only owners that have the BackOffice role
            $ownerIds = User::role(['BackOffice', 'BU Manager'])->pluck('id')->toArray();
        }

        // if no owners left, return empty
        if (empty($ownerIds)) {
            return response()->json([], 200);
        }

        // fetch the user rows for these owners
        $users = User::whereIn('id', $ownerIds)->get();

        // 1) per-user totals (grouped by owner_bo) using the same filters
        $perUserTotals = (clone $clientsBaseQuery)
            ->groupBy('owner_bo')
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
                DB::raw("TIME_FORMAT(TIME(MIN(updated_at)), '%H:%i:%s') as start_time"),
                DB::raw("TIME_FORMAT(TIME(MAX(updated_at)), '%H:%i:%s') as end_time"),
            ])
            ->get()
            ->keyBy('owner_bo');

        // 2) per-user per-day aggregated stats (same filters)
        $perUserPerDay = (clone $clientsBaseQuery)
            ->groupBy('owner_bo', DB::raw('DATE(updated_at)'))
            ->select([
                'owner_bo',
                DB::raw('DATE(updated_at) as day'),
                DB::raw("SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as number_clients_pending"),
                DB::raw("SUM(CASE WHEN status = 'confirmed' THEN 1 ELSE 0 END) as number_clients_confirmed"),
                DB::raw("SUM(CASE WHEN status = 'validated' THEN 1 ELSE 0 END) as number_clients_validated"),
                DB::raw("SUM(CASE WHEN status = 'nonvalidated' THEN 1 ELSE 0 END) as number_clients_nonvalidated"),
                DB::raw("SUM(CASE WHEN status = 'visible' THEN 1 ELSE 0 END) as number_clients_visible"),
                DB::raw("SUM(CASE WHEN status = 'ferme' THEN 1 ELSE 0 END) as number_clients_ferme"),
                DB::raw("SUM(CASE WHEN status = 'refus' THEN 1 ELSE 0 END) as number_clients_refus"),
                DB::raw("SUM(CASE WHEN status = 'introuvable' THEN 1 ELSE 0 END) as number_clients_introuvable"),
                DB::raw("TIME_FORMAT(TIME(MIN(updated_at)), '%H:%i:%s') as start_time"),
                DB::raw("TIME_FORMAT(TIME(MAX(updated_at)), '%H:%i:%s') as end_time"),
            ])
            ->get()
            ->groupBy('owner_bo');

        // Build the period once
        $period = CarbonPeriod::create($startDate, $endDate)->toArray();

        // Map into pointings
        $pointings = $users->map(function ($pointing) use ($perUserTotals, $perUserPerDay, $period, $route_links, $startDate, $endDate) {
            $owner = $pointing->id;

            // attach totals (or defaults)
            $tot = $perUserTotals->get($owner);
            $pointing->details = $tot ? $tot : (object)[
                'number_clients_pending'        =>  0,
                'number_clients_confirmed'      =>  0,
                'number_clients_validated'      =>  0,
                'number_clients_nonvalidated'   =>  0,
                'number_clients_visible'        =>  0,
                'number_clients_ferme'          =>  0,
                'number_clients_refus'          =>  0,
                'number_clients_introuvable'    =>  0,
                'start_time'                    =>  null,
                'end_time'                      =>  null
            ];

            // clients (respect same optional route filter)
            $clientsQuery = Client::where('clients.owner_bo', $owner)
                ->whereBetween('clients.updated_at', [$startDate, $endDate])
                ->join('users as bo', 'clients.owner_bo', '=', 'bo.id')
                ->select('clients.*', 'bo.username as owner_name');

            if (!empty($route_links)) {
                $clientsQuery->whereIn('clients.id_route_import', $route_links);
            }

            $pointing->clients = $clientsQuery->get();

            // build days (fill missing)
            $ownerDays = $perUserPerDay->has($owner) ? $perUserPerDay->get($owner)->keyBy('day') : collect();
            $days = [];

            foreach ($period as $date) {
                $d = $date->format('Y-m-d');

                if ($ownerDays->has($d)) {
                    $r = $ownerDays->get($d);
                    $days[] = (object)[
                        'day'                           =>  $d,
                        'number_clients_pending'        =>  (int)$r->number_clients_pending,
                        'number_clients_confirmed'      =>  (int)$r->number_clients_confirmed,
                        'number_clients_validated'      =>  (int)$r->number_clients_validated,
                        'number_clients_nonvalidated'   =>  (int)$r->number_clients_nonvalidated,
                        'number_clients_visible'        =>  (int)$r->number_clients_visible,
                        'number_clients_ferme'          =>  (int)$r->number_clients_ferme,
                        'number_clients_refus'          =>  (int)$r->number_clients_refus,
                        'number_clients_introuvable'    =>  (int)$r->number_clients_introuvable,
                        'start_time'                    =>  $r->start_time,
                        'end_time'                      =>  $r->end_time,
                    ];
                } else {
                    $days[] = (object)[
                        'day' => $d,
                        'number_clients_pending'        => 0,
                        'number_clients_confirmed'      => 0,
                        'number_clients_validated'      => 0,
                        'number_clients_nonvalidated'   => 0,
                        'number_clients_visible'        => 0,
                        'number_clients_ferme'          => 0,
                        'number_clients_refus'          => 0,
                        'number_clients_introuvable'    => 0,
                        'start_time'                    => null,
                        'end_time'                      => null,
                    ];
                }
            }

            $pointing->days = $days;

            //
            $pointing->total_clients = 
                (int)($pointing->details->number_clients_pending        ?? 0)   +
                (int)($pointing->details->number_clients_confirmed      ?? 0)   +
                (int)($pointing->details->number_clients_validated      ?? 0)   +
                (int)($pointing->details->number_clients_nonvalidated   ?? 0)   +
                (int)($pointing->details->number_clients_visible        ?? 0)   +
                (int)($pointing->details->number_clients_ferme          ?? 0)   +
                (int)($pointing->details->number_clients_refus          ?? 0)   +
                (int)($pointing->details->number_clients_introuvable    ?? 0);

            //
            return $pointing;
        })->sortByDesc('total_clients');

        return $pointings->values();
    }
}