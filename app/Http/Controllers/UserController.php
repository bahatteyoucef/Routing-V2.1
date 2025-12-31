<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\Rules;

use App\Models\Role;
use App\Models\RouteImport;
use App\Models\RouteImportDistrict;
use App\Models\UserRouteImport;
use App\Models\UserTerritory;
use Exception;
use Throwable;

use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function login(Request $request) {

        $validator      =   Validator::make($request->all(), [
            'username'  =>  'required|alpha_num|max:255',
            'password'  =>  'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors'    =>  $validator->errors(),
            ],422);
        }

        //  //  //

        $credentials    =   $request->only('username', 'password');

        if (Auth::attempt($credentials)) {

            $user           =   Auth::user();

            if($user->status    ==  "enabled") {

                $user->roles    =   $user->getRoleNames();

                //

                if($user->type_user ==  "FrontOffice") {

                    $user_route_import  =   UserRouteImport::where('id_user', $user->id)->first();  

                    if($user_route_import) {

                        $route_import       =   RouteImport::where('id', $user_route_import->id_route_import)->first();  

                        if($user_route_import) {

                            //
                            $user->id_route_import  =   $route_import->id;
                            $user->districts        =   RouteImportDistrict::where('id_route_import', $user_route_import->id_route_import)->pluck('DistrictNo');
                        }

                        else {

                            $user->id_route_import  =   null;
                            $user->districts        =   [];
                        }
                    }

                    else {

                        $user->id_route_import  =   null;
                        $user->districts        =   [];

                        return response()->json([
                            'header'    =>  "Error !",
                            'errors'    =>  ["Unauthorized !"],
                        ], Response::HTTP_UNAUTHORIZED);
                    }

                    //

                    $user_territories           =   UserTerritory::where('id_user', $user->id)->get();  

                    $user->user_territories     =   $user_territories;
                }

                // 

                return response([
                    'user'          => $user                                                ,
                    'access_token'  => Auth::user()->createToken('authToken')->accessToken
                ], Response::HTTP_OK);
            }

            else {
                return response()->json([
                    'header'    =>  "Error !",
                    'errors'    =>  ["Unauthorized !"],
                ], Response::HTTP_UNAUTHORIZED);
            }
        }

        return response()->json([
            'header'    =>  "Error !",
            'errors'    =>  ["Wrong username or password !"],
        ], Response::HTTP_UNAUTHORIZED);
    }

    public function isAuthentificated(Request $request) {

        if((Auth::guard("api")->check())&&(Auth::guard("api")->user()->status   ==  "enabled")) {

            $user           =   Auth::guard("api")->user();
            $user->roles    =   $user->getRoleNames();

            //

            if($user->type_user ==  "FrontOffice") {

                $user_route_import  =   UserRouteImport::where('id_user', $user->id)->first();  

                if($user_route_import) {

                    $route_import       =   RouteImport::where('id', $user_route_import->id_route_import)->first();  

                    if($user_route_import) {

                        //
                        $user->id_route_import  =   $route_import->id;
                        $user->districts        =   RouteImportDistrict::where('id_route_import', $user_route_import->id_route_import)->pluck('DistrictNo');
                    }

                    else {

                        $user->id_route_import  =   null;
                        $user->districts        =   [];
                    }
                }

                else {

                    $user->id_route_import  =   null;
                    $user->districts        =   [];

                    //
                    return "";
                }

                //

                $user_territories           =   UserTerritory::where('id_user', $user->id)->get();  
                $user->user_territories     =   $user_territories;
            }

            //
            return $user;
        }

        else {
            return "";
        }
    }

    //  //  //  //  //
    //  //  //  //  //
    //  //  //  //  //

    public function index()
    {
        
        try {

            $users          =   User::indexUser();
            return $users;
        }

        catch(Throwable $erreur) {

            return response()->json([
                'errors'    =>  [$erreur->getMessage()],
            ],422);
        }
    }

    public function combo()
    {
        
        try {

            $users          =   User::comboUser();
            return $users;
        }

        catch(Throwable $erreur) {

            return response()->json([
                'errors'    =>  [$erreur->getMessage()],
            ],422);
        }
    }

    public function comboBackOffice()
    {
        
        try {

            $users          =   User::comboBackOffice();
            return $users;
        }

        catch(Throwable $erreur) {

            return response()->json([
                'errors'    =>  [$erreur->getMessage()],
            ],422);
        }
    }

    //  //  //  //  //
    //  //  //  //  //
    //  //  //  //  //

    public function store(Request $request) 
    {    

        try {

            //
            DB::beginTransaction();
            //

            // validate
            $validator    =   User::validateStore($request);
            
            if ($validator->fails()) {
                return response()->json([
                    'errors'    =>  $validator->errors(),
                ],422);
            }

            // store
            User::storeUser($request);

            //
            DB::commit();
            //

            return response()->json([
                "header"        =>  "User Added !",
                "message"       =>  "a new user has been added successfully !"
            ]);
        }

        catch(Throwable $erreur) {

            //
            DB::rollBack();
            //

            return response()->json([
                'errors'    =>  [$erreur->getMessage()],
            ],422);
        }
    }

    public function update(Request $request, int $id_user) 
    {

        try {

            //
            DB::beginTransaction();
            //

            // validate
            $validator    =   User::validateUpdate($request, $id_user);
            
            if ($validator->fails()) {
                return response()->json([
                    'errors'    =>  $validator->errors(),
                ],422);
            }

            // update
            User::updateUser($request, $id_user);

            //
            DB::commit();
            //

            return response()->json([
                "header"        =>  "User Updated !",
                "message"       =>  "a user has been updated successfully !"
            ]);

        }

        catch(Throwable $erreur) {

            //
            DB::rollBack();
            //

            return response()->json([
                'errors'    =>  [$erreur->getMessage()],
            ],422);
        }
    }
    
    public function show(int $id_user)
    {

        try {

            $user  =   User::showUser($id_user);
            return $user;
        }

        catch(Throwable $erreur) {

            return response()->json([
                'errors'    =>  [$erreur->getMessage()],
            ],422);
        }
    }

    //  //  //  //  //
    //  //  //  //  //
    //  //  //  //  //

    public static function pointings(Request $request)
    {

        try {

            $pointings  =   User::pointings($request);
            return $pointings;
        }

        catch(Throwable $erreur) {

            return response()->json([
                'errors'    =>  [$erreur->getMessage()],
            ],422);
        }
    }
}