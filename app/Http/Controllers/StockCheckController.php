<?php

namespace App\Http\Controllers;

use App\Models\StockCheck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class StockCheckController extends Controller
{
    //

    public function storeListeStockCheck(Request $request)
    {

        try {

            //
            DB::beginTransaction();
            //
            
            // store 
            StockCheck::storeListeStockCheck($request);

            //
            DB::commit();
            //

            return response()->json([   
                "header"            =>  "Check Added !"                                     ,
                "message"           =>  "a new stock check has been added successfully !"
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
