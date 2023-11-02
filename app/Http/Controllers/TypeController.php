<?php

namespace App\Http\Controllers;

use App\Models\Type;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class TypeController extends Controller
{
    //

    public function index()
    {
        
        try {

            $types          =   Type::indexType();

            return User::filterTypes($types);
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

            $types          =   Type::comboType();
            return $types;
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
            $validator    =   Type::validateStore($request);
            
            if ($validator->fails()) {
                return response()->json([
                    'errors'    =>  $validator->errors(),
                ],422);
            }

            // store
            Type::storeType($request);

            //
            DB::commit();
            //

            return response()->json([
                "header"        =>  "Type Added !",
                "message"       =>  "a new type has been added successfully !"
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
            $validator    =   Type::validateUpdate($request, $id);
            
            if ($validator->fails()) {
                return response()->json([
                    'errors'    =>  $validator->errors(),
                ],422);
            }

            // update
            Type::updateType($request, $id);

            //
            DB::commit();
            //

            return response()->json([
                "header"        =>  "Type Updated !",
                "message"       =>  "a type has been updated successfully !"
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

            $categorie  =   Type::showType($id);
            return $categorie;
        }

        catch(Throwable $erreur) {

            return response()->json([
                'errors'    =>  [$erreur->getMessage()],
            ],422);
        }
    }

    //
}
