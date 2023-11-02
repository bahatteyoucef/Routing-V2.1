<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class CategorieController extends Controller
{
    //

    public function index()
    {
        
        try {

            $categories          =   Categorie::indexCategorie();
            return User::filterCategories($categories);
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

            $categories          =   Categorie::comboCategorie();
            return $categories;
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
            $validator    =   Categorie::validateStore($request);
            
            if ($validator->fails()) {
                return response()->json([
                    'errors'    =>  $validator->errors(),
                ],422);
            }

            // store
            Categorie::storeCategorie($request);

            //
            DB::commit();
            //

            return response()->json([
                "header"        =>  "Categorie Added !",
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
            $validator    =   Categorie::validateUpdate($request, $id);
            
            if ($validator->fails()) {
                return response()->json([
                    'errors'    =>  $validator->errors(),
                ],422);
            }

            // update
            Categorie::updateCategorie($request, $id);

            //
            DB::commit();
            //

            return response()->json([
                "header"        =>  "Categorie Updated !",
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

            $categorie  =   Categorie::showCategorie($id);
            return $categorie;
        }

        catch(Throwable $erreur) {

            return response()->json([
                'errors'    =>  [$erreur->getMessage()],
            ],422);
        }
    }

    //

    public function categorieTypes(int $id) 
    {

        try {

            $types          =   Categorie::categorieTypes($id);
            return $types;
        }

        catch(Throwable $erreur) {

            return response()->json([
                'errors'    =>  [$erreur->getMessage()],
            ],422);
        }
    }
}
