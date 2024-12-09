<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\Rules;

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

    public static function indexUser() 
    {
    
        $users      =   DB::table('users')

                        ->select([ 
                            'users.id                   as  id'                     , 

                            'users.nom                  as  nom'                    ,
                            'users.email                as  email'                  ,

                            'users.tel                  as  tel'                    ,
                            'users.company              as  company'                ,

                            'users.type_user            as  type_user'              ,

                            'users.max_route_import     as  max_route_import'       ,

                            'users.password_non_hashed  as  password_non_hashed'    ,

                            'users.owner                as  owner'   
                        ])

                        ->get();

        return $users;
    }

    public static function comboUser() 
    {
    
        $users      =   DB::table('users')

                        ->select([ 
                            'users.id                   as  id'                     , 

                            'users.nom                  as  nom'                    ,
                            'users.email                as  email'                  ,

                            'users.tel                  as  tel'                    ,
                            'users.company              as  company'                ,

                            'users.type_user            as  type_user'              ,

                            'users.max_route_import     as  max_route_import'       ,

                            'users.password_non_hashed  as  password_non_hashed'    ,

                            'users.owner                as  owner'
                        ])

                        ->get();

        return $users;
    }

    public static function validateStore(Request $request) 
    {

        $validator = Validator::make($request->all(), [
            'nom'               =>  ["required", "alpha_num", Rule::unique('users', 'nom')],
            'email'             =>  ["required", Rule::unique('users')  , "email", "max:255"            ],
            'tel'               =>  ["required", "max:255"                                              ],
            'company'           =>  ["required", "max:255"                                              ],
            'type_user'         =>  ["required", "max:255"                                              ],
            'password'          =>  ["required", "confirmed"            , "min:6", "max:255"            ]
        ]);

        $validator->sometimes('max_route_import',  ["required", "integer"] , function (Fluent $input) {
    
            return ($input->type_user    ==  "BU Manager")||($input->type_user    ==  "BackOffice");
        });
    
        return $validator;
    }

    public static function storeUser(Request $request) 
    {
        
        $user = new User([
            'nom'                   =>  $request->input('nom')                  ,
            'email'                 =>  $request->input('email')                ,
            'tel'                   =>  $request->input('tel')                  ,
            'company'               =>  $request->input('company')              ,
            'max_route_import'      =>  $request->input('max_route_import')     ,
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
        }

        //
    }

    public static function validateUpdate(Request $request, int $id) 
    {

        $validator = Validator::make($request->all(), [
            'nom'               =>  ["required", "alpha_num", Rule::unique('users', 'nom')->ignore($id)],
            'email'             =>  ["required", Rule::unique('users')->ignore($id), "email", "max:255" ],
            'tel'               =>  ["required", "max:255"                                              ],
            'company'           =>  ["required", "max:255"                                              ],
            'type_user'         =>  ["required", "max:255"                                              ]
        ]);

        $validator->sometimes('max_route_import',  ["required", "integer"] , function (Fluent $input) {
    
            return ($input->type_user    ==  "BU Manager")||($input->type_user    ==  "BackOffice");
        });
    
        return $validator;
    }

    public static function updateUser(Request $request, int $id) 
    {

        $user                       =   User::find($id);

        $user->nom                  =   $request->input('nom');
        $user->email                =   $request->input('email');
        $user->tel                  =   $request->input('tel');
        $user->company              =   $request->input('company');
        $user->max_route_import     =   $request->input('max_route_import');
        $user->type_user            =   $request->input('type_user');

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

        if($request->get("type_user")   ==  "FrontOffice") {

            if($request->get("selected_route_import")   !=  "null") {

                $route_import           =   new UserRouteImport([

                    'id_user'           =>  $user->id                                   ,
                    'id_route_import'   =>  $request->get("selected_route_import")
                ]);

                $route_import->save();
            }

            $user->syncRoles(['FrontOffice']);
        }

        //
    }       

    public static function showUser(int $id) 
    {
    
        //
        $user                       =   User::find($id);

        //
        $user->liste_route_import   =   DB::table('users_route_import')
                                        ->where('users_route_import.id_user', $user->id) 
                                        ->pluck('users_route_import.id_route_import') 
                                        ->toArray();

        return $user;
    }

    //

    public static function filterUsers($users) {

        if(Auth::user()->hasRole("Super Admin")) {

            return $users;
        }

        else {

            if(Auth::user()->hasRole("BU Manager")) {

                return $users->where("owner", Auth::user()->id);
            }

            else {

                if(Auth::user()->hasRole("BackOffice")) {

                    $users->filter(function ($user, $key) {

                        return [];
                    });
                }

                else {

                    if(Auth::user()->hasRole("FrontOffice")) {

                        return [];
                    }
                }
            }
        }
    }

    public static function filterRouteImport($liste_route_import) {

        $liste_route_import_final   =   [];

        if(Auth::user()->hasRole("Super Admin")) {

            return $liste_route_import;
        }

        else {

            if(Auth::user()->hasRole("BU Manager")) {

                foreach ($liste_route_import as $route_import) {

                    # code...
                    $liste_user_route_import            =   UserRouteImport::where('id_user', Auth::user()->id)->get();

                    foreach ($liste_user_route_import as $user_route_import) {

                        if($user_route_import->id_route_import  ==  $route_import->id) {

                            array_push($liste_route_import_final, $route_import);
                        }
                    }
                }

                return $liste_route_import_final;
            }

            else {

                if(Auth::user()->hasRole("BackOffice")) {

                    foreach ($liste_route_import as $route_import) {

                        # code...
                        $liste_user_route_import            =   UserRouteImport::where('id_user', Auth::user()->id)->get();

                        foreach ($liste_user_route_import as $user_route_import) {

                            if($user_route_import->id_route_import  ==  $route_import->id) {

                                array_push($liste_route_import_final, $route_import);
                            }
                        }
                    }

                    return $liste_route_import_final;
                }

                else {

                    if(Auth::user()->hasRole("FrontOffice")) {

                        foreach ($liste_route_import as $route_import) {

                            # code...
                            $liste_user_route_import            =   UserRouteImport::where('id_user', Auth::user()->id)->get();

                            foreach ($liste_user_route_import as $user_route_import) {

                                if($user_route_import->id_route_import  ==  $route_import->id) {

                                    array_push($liste_route_import_final, $route_import);
                                }
                            }
                        }

                        return $liste_route_import_final;
                    }
                }
            }
        }

        //
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
