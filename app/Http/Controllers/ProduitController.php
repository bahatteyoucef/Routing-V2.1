<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class ProduitController extends Controller
{
    //

    public function index()
    {
        
        try {

            $categories          =   Produit::indexProduit();
            return User::filterProduits($categories);
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

            $categories          =   Produit::comboProduit();
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
            $validator    =   Produit::validateStore($request);
            
            if ($validator->fails()) {
                return response()->json([
                    'errors'    =>  $validator->errors(),
                ],422);
            }

            // store
            Produit::storeProduit($request);

            //
            DB::commit();
            //

            return response()->json([
                "header"        =>  "Product Added !",
                "message"       =>  "a new product has been added successfully !"
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
            $validator    =   Produit::validateUpdate($request, $id);
            
            if ($validator->fails()) {
                return response()->json([
                    'errors'    =>  $validator->errors(),
                ],422);
            }

            // update
            Produit::updateProduit($request, $id);

            //
            DB::commit();
            //

            return response()->json([
                "header"        =>  "Product Updated !",
                "message"       =>  "a product has been updated successfully !"
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

            $categorie  =   Produit::showProduit($id);
            return $categorie;
        }

        catch(Throwable $erreur) {

            return response()->json([
                'errors'    =>  [$erreur->getMessage()],
            ],422);
        }
    }

    //

    public function competitorsProducts(int $id_route_import)
    {

        try {

            $products   =   Produit::competitorsProducts($id_route_import);
            return $products;
        }

        catch(Throwable $erreur) {

            return response()->json([
                'errors'    =>  [$erreur->getMessage()],
            ],422);
        }
    }

    public function stockProducts(int $id_route_import)
    {

        try {

            $products   =   Produit::stockProducts($id_route_import);
            return $products;
        }

        catch(Throwable $erreur) {

            return response()->json([
                'errors'    =>  [$erreur->getMessage()],
            ],422);
        }
    }

    public function produitPrices(int $id_route_import, int $id)
    {

        try {

            $prices   =   Produit::produitPrices($id);
            return $prices;
        }

        catch(Throwable $erreur) {

            return response()->json([
                'errors'    =>  [$erreur->getMessage()],
            ],422);
        }
    }
}
