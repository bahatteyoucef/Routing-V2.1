<?php

namespace App\Models;

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
}
