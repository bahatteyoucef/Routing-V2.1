<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class CompetitorAnalysis extends Model
{
    //

    protected $guarded      =   [];

    protected $table        =   'competitors_analysis';
    protected $primaryKey   =   'id';

    // 

    use HasFactory;

    //

    public static function storeCompetitorAnalysis(Request $request) 
    {

        $client     =   new CompetitorAnalysis([
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

    public static function storeListeCompetitorAnalysis(Request $request) 
    {

        $products_result    =   json_decode($request->input("products_result"));

        foreach ($products_result as $product) {

            $competitor_analysis     =   new CompetitorAnalysis([
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
