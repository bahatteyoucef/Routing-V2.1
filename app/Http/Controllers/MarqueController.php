<?php

namespace App\Http\Controllers;

use App\Models\Marque;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class MarqueController extends Controller
{
    //

    public function index()
    {
        
        try {

            $marques          =   Marque::indexMarque();
            return User::filterMarques($marques);
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

            $marques          =   Marque::comboMarque();
            return $marques;
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
            $validator    =   Marque::validateStore($request);
            
            if ($validator->fails()) {
                return response()->json([
                    'errors'    =>  $validator->errors(),
                ],422);
            }

            // store
            Marque::storeMarque($request);

            //
            DB::commit();
            //

            return response()->json([
                "header"        =>  "Marque Added !",
                "message"       =>  "a new category has been added successfully !"
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
            $validator    =   Marque::validateUpdate($request, $id);
            
            if ($validator->fails()) {
                return response()->json([
                    'errors'    =>  $validator->errors(),
                ],422);
            }

            // update
            Marque::updateMarque($request, $id);

            //
            DB::commit();
            //

            return response()->json([
                "header"        =>  "Marque Updated !",
                "message"       =>  "a category has been updated successfully !"
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

            $marque  =   Marque::showMarque($id);
            return $marque;
        }

        catch(Throwable $erreur) {

            return response()->json([
                'errors'    =>  [$erreur->getMessage()],
            ],422);
        }
    }

}
