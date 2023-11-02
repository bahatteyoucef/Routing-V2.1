<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class StockCheck extends Model
{
    //

    protected $guarded      =   [];

    protected $table        =   'stock_check';
    protected $primaryKey   =   'id';

    // 

    use HasFactory;

    //

    public static function storeStockCheck(Request $request) 
    {

        $client     =   new StockCheck([
            'prix'                  =>  $request->input("prix")             ,
            'quantite'              =>  $request->input("quantite")         ,

            'id_product'            =>  $request->input("id_product")       ,
            'id_route_import'       =>  $request->input("id_route_import")  ,

            'owner'                 =>  Auth::user()->id                    
        ]);

        //
        $client->save();

        return $client;
    }

    public static function storeListeStockCheck(Request $request) 
    {

        $products_result    =   json_decode($request->input("products_result"));

        foreach ($products_result as $product) {

            $competitor_analysis     =   new StockCheck([
                'prix'                  =>  $product->price                     ,
                'quantite'              =>  $product->quantity                  ,

                'id_product'            =>  $product->id_product                ,

                'id_client'             =>  $request->input("id_client")        ,
                'id_route_import'       =>  $request->input("id_route_import")  ,

                'owner'                 =>  Auth::user()->id                    
            ]);

            //
            $competitor_analysis->save();
        }
    }
}
