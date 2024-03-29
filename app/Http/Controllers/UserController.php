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
use App\Models\UserRouteImport;
use Exception;
use Throwable;

class UserController extends Controller
{

    // Authentification

    public function login(Request $request) {

        if (Auth::attempt($request->all())) {

            $user           =   Auth::user();

            $user->roles    =   $user->getRoleNames();

            // 

            return response([
                'user'          => $user                                                ,
                'access_token'  => Auth::user()->createToken('authToken')->accessToken
            ], Response::HTTP_OK);
        }

        return response()->json([
            'header'    =>  "Error !",
            'errors'    =>  "Wrong email or password !",
        ], Response::HTTP_UNAUTHORIZED);
    }

    public function isAuthentificated(Request $request) {

        return Auth::guard("api")->check();
    }

    // 

    public function index()
    {
        
        try {

            $users          =   User::indexUser();
            return User::filterUsers($users);
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

    public function update(Request $request, int $id) 
    {

        try {

            //
            DB::beginTransaction();
            //

            // validate
            $validator    =   User::validateUpdate($request, $id);
            
            if ($validator->fails()) {
                return response()->json([
                    'errors'    =>  $validator->errors(),
                ],422);
            }

            // update
            User::updateUser($request, $id);

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
    
    public function show(int $id)
    {

        try {

            $user  =   User::showUser($id);
            return $user;
        }

        catch(Throwable $erreur) {

            return response()->json([
                'errors'    =>  [$erreur->getMessage()],
            ],422);
        }
    }

    //

    public function changePassword(Request $request, int $id) 
    {

        try {

            //
            DB::beginTransaction();
            //

            // validate
            $validator    =   User::validateChangePassword($request, $id);
            
            if ($validator->fails()) {
                return response()->json([
                    'errors'    =>  $validator->errors(),
                ],422);
            }

            // update
            User::changePassword($request, $id);

            //
            DB::commit();
            //

            return response()->json([
                "header"        =>  "Password Updated !",
                "message"       =>  "You need to login again !"
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
}
