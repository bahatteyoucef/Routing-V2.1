<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Fluent;

class Produit extends Model
{
    //

    protected $guarded      =   [];

    protected $table        =   'produits';
    protected $primaryKey   =   'id';

    public    $timestamps   =   false;

    // 

    use HasFactory;

    //

    public static function indexProduit() 
    {
    
        $produits       =   DB::table('produits')

                            ->select([ 
                                'produits.id                as  id'                 , 

                                'produits.nom               as  nom'                ,
                                'produits.description       as  description'        ,

                                'produits.quantite          as  quantite'           ,
                                'produits.prix              as  prix'               ,

                                'produits.class             as  class'              ,

                                'produits.id_marque         as  id_marque'          ,
                                'marques.nom                as  nom_marque'         ,

                                'produits.id_categorie      as  id_categorie'       ,
                                'categories.nom             as  nom_categorie'      ,

                                'produits.id_type           as  id_type'            ,
                                'types.nom                  as  nom_type'           ,

                                'produits.id_route_import   as  id_route_import'    ,
                                'route_import.libelle       as  libelle_route_import'
                            ])

                            ->join("marques"        , "produits.id_marque"          , "marques.id")
                            ->join("categories"     , "produits.id_categorie"       , "categories.id")
                            ->join("types"          , "produits.id_type"            , "types.id")
                            ->join("route_import"   , "produits.id_route_import"    , "route_import.id")

                            ->get();

        return $produits;
    }

    public static function comboProduit() 
    {
    
        $produits      =   DB::table('produits')

                        ->select([ 
                            'produits.id                as  id'                 , 

                            'produits.nom               as  nom'                ,
                            'produits.description       as  description'        ,

                            'produits.quantite          as  quantite'           ,
                            'produits.prix              as  prix'               
                        ])

                        ->get();

        return $produits;
    }

    public static function validateStore(Request $request) 
    {

        $validator = Validator::make($request->all(), [
            'nom'               =>  ["required", "max:255"      ],
            'description'       =>  ["required"                 ],

            'class'             =>  ["required"                 ],

            'id_categorie'      =>  ["required", "integer"      ],
            'id_type'           =>  ["required", "integer"      ],
            'id_route_import'   =>  ["required", "integer"      ]
        ]);
    
        $validator->sometimes(['quantite', 'prix'],  ["required", "numeric"] , function (Fluent $input) {
    
            return $input->class    ==  "stock";
        });

        return $validator;
    }

    public static function storeProduit(Request $request) 
    {
        
        $produit = new Produit([
            'nom'               =>  $request->input('nom')              ,
            'description'       =>  $request->input('description')      ,
            'quantite'          =>  $request->input('quantite')         ,
            'prix'              =>  $request->input('prix')             ,

            'class'             =>  $request->input('class')            ,

            'id_categorie'      =>  $request->input('id_categorie')     ,
            'id_type'           =>  $request->input('id_type')          ,
            'id_route_import'   =>  $request->input('id_route_import')  ,

            'owner'             =>  Auth::user()->id
        ]);

        $produit->save();

        //

        $price = new Price([
            'price'             =>  $request->input('prix')     ,
            'id_produit'        =>  $produit->id                         
        ]);

        $price->save();

    }

    public static function validateUpdate(Request $request, int $id) 
    {

        $validator = Validator::make($request->all(), [
            'nom'               =>  ["required", "max:255"      ],
            'description'       =>  ["required"                 ],

            'class'             =>  ["required"                 ],

            'id_categorie'      =>  ["required", "integer"      ],
            'id_type'           =>  ["required", "integer"      ],
            'id_route_import'   =>  ["required", "integer"      ]
        ]);

        $validator->sometimes(['quantite', 'prix'],  ["required", "numeric"] , function (Fluent $input) {
    
            return $input->class    ==  "stock";
        });
    
        return $validator;
    }

    public static function updateProduit(Request $request, int $id) 
    {

        $produit                    =   Produit::find($id);

        $produit->nom               =   $request->input('nom');
        $produit->description       =   $request->input('description');
        $produit->quantite          =   $request->input('quantite');
        $produit->prix              =   $request->input('prix');

        $produit->class             =   $request->input('class');

        $produit->id_categorie      =   $request->input('id_categorie');
        $produit->id_type           =   $request->input('id_type');
        $produit->id_route_import   =   $request->input('id_route_import');

        $produit->save();

        //

        $price = new Price([
            'price'             =>  $request->input('prix')     ,
            'id_produit'        =>  $id                         
        ]);

        $price->save();
    }       

    public static function showProduit(int $id) 
    {
    
        $produit        =   DB::table('produits')
                            ->where('produits.id', $id)

                            ->select([ 
                                'produits.id                as  id'                 , 

                                'produits.nom               as  nom'                ,
                                'produits.description       as  description'        ,

                                'produits.quantite          as  quantite'           ,
                                'produits.prix              as  prix'               ,

                                'produits.class             as  class'              ,

                                'produits.id_marque         as  id_marque'          ,
                                'marques.nom                as  nom_marque'         ,

                                'produits.id_categorie      as  id_categorie'       ,
                                'categories.nom             as  nom_categorie'      ,

                                'produits.id_type           as  id_type'            ,
                                'types.nom                  as  nom_type'           ,

                                'produits.id_route_import   as  id_route_import'    ,
                                'route_import.libelle       as  libelle_route_import'
                            ])

                            ->join("marques"        , "produits.id_marque"          , "marques.id")
                            ->join("categories"     , "produits.id_categorie"       , "categories.id")
                            ->join("types"          , "produits.id_type"            , "types.id")
                            ->join("route_import"   , "produits.id_route_import"    , "route_import.id")

                            ->first();

        return $produit;
    }

    //

    public static function stockProducts(int $id_route_import)
    {

        $produits       =   DB::table('produits')
                            ->where([['produits.class', "stock"], 
                                    ['produits.id_route_import', $id_route_import]])

                            ->select([ 
                                'produits.id                as  id'                 , 

                                'produits.nom               as  nom'                ,
                                'produits.description       as  description'        ,

                                'produits.quantite          as  quantite'           ,
                                'produits.prix              as  prix'               ,

                                'produits.class             as  class'              
                            ])

                            ->get();

        return $produits;
    }

    public static function competitorsProducts(int $id_route_import)
    {

        $produits       =   DB::table('produits')
                            ->where([['produits.class', "competitors"], 
                                    ['produits.id_route_import', $id_route_import]])

                            ->select([ 
                                'produits.id                as  id'                 , 

                                'produits.nom               as  nom'                ,
                                'produits.description       as  description'        ,

                                'produits.class             as  class'              
                            ])

                            ->get();

        return $produits;
    }

    public static function produitPrices(int $id)
    {

        $prices       =   DB::table('prices')
                            ->where('prices.id_produit', $id)

                            ->select([ 
                                'prices.id              as  id'             , 

                                'prices.price           as  price'          ,
                                'prices.created_at      as  created_at'        
                            ])

                            ->get();

        foreach ($prices as $price) {

            $price->created_at  =   Carbon::parse($price->created_at)->format('d F Y');
        }

        return $prices;
    }
}
