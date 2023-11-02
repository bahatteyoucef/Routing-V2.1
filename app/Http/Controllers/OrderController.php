<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class OrderController extends Controller
{
    //

    public function storeListeOrders(Request $request)
    {

        try {

            //
            DB::beginTransaction();
            //
            
            // store 
            Order::storeListeOrders($request);

            //
            DB::commit();
            //

            return response()->json([   
                "header"            =>  "Orders Added !"                                          ,
                "message"           =>  "a new liste of orders has been added successfully !"
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
