<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class Order extends Model
{
    //

    protected $guarded      =   [];

    protected $table        =   'orders';
    protected $primaryKey   =   'id';

    // 

    use HasFactory;

    //

    public static function storeOrder(Request $request) 
    {

        $client     =   new Order([
            'quantite'              =>  $request->input("quantite")         ,

            'id_produit'            =>  $request->input("id_produit")       ,
            'id_client'             =>  $request->input("id_client")        ,
            'id_route_import'       =>  $request->input("id_route_import")  ,

            'owner'                 =>  Auth::user()->id                    
        ]);

        //
        $client->save();

        return $client;
    }

    public static function storeListeOrders(Request $request) 
    {

        $orders_result    =   json_decode($request->input("orders_result"));

        foreach ($orders_result as $order) {

            $order     =   new Order([
                'quantite'              =>  $order->quantity                    ,

                'id_produit'            =>  $order->id_product                  ,
                'id_client'             =>  $request->input("id_client")        ,
                'id_route_import'       =>  $request->input("id_route_import")  ,

                'owner'                 =>  Auth::user()->id                    
            ]);

            //
            $order->save();
        }
    }
    
}
