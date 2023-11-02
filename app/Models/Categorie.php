<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Fluent;

class Categorie extends Model
{
    //

    protected $guarded      =   [];

    protected $table        =   'categories';
    protected $primaryKey   =   'id';

    public    $timestamps   =   false;

    // 

    use HasFactory;

    //

    public static function indexCategorie() 
    {
    
        $categories      =   DB::table('categories')

                            ->select([ 
                                'categories.id                  as  id'                     , 

                                'categories.nom                 as  nom'                    ,
                                'categories.description         as  description'            ,

                                'categories.id_route_import     as  id_route_import'        ,
                                'route_import.libelle           as  libelle_route_import'
                            ])

                            ->join("route_import"   , "categories.id_route_import"    , "route_import.id")

                            ->get();

        return $categories;
    }

    public static function comboCategorie() 
    {
    
        $categories      =   DB::table('categories')

                        ->select([ 
                            'categories.id                as  id'                 , 

                            'categories.nom               as  nom'                ,
                            'categories.description       as  description'        
                        ])

                        ->get();

        return $categories;
    }

    public static function validateStore(Request $request) 
    {

        $validator = Validator::make($request->all(), [
            'nom'               =>  ["required", "max:255"      ],
            'description'       =>  ["required"                 ],

            'id_route_import'   =>  ["required", "integer"      ]
        ]);
    
        return $validator;
    }

    public static function storeCategorie(Request $request) 
    {
        
        $categorie = new Categorie([
            'nom'               =>  $request->input('nom')              ,
            'description'       =>  $request->input('description')      ,

            'id_route_import'   =>  $request->input('id_route_import')  ,
            'owner'             =>  Auth::user()->id
        ]);

        $categorie->save();
    }

    public static function validateUpdate(Request $request, int $id) 
    {

        $validator = Validator::make($request->all(), [
            'nom'               =>  ["required", "max:255"      ],
            'description'       =>  ["required"                 ],

            'id_route_import'   =>  ["required", "integer"      ]
        ]);
    
        return $validator;
    }

    public static function updateCategorie(Request $request, int $id) 
    {

        $user                       =   Categorie::find($id);

        $user->nom                  =   $request->input('nom');
        $user->description          =   $request->input('description');

        $user->id_route_import      =   $request->input('id_route_import');

        $user->save();
    }       

    public static function showCategorie(int $id) 
    {
    
        $user      =   DB::table('categories')
                        ->where('categories.id', $id)

                        ->select([ 
                            'categories.id                  as  id'                 , 

                            'categories.nom                 as  nom'                ,
                            'categories.description         as  description'        ,

                            'categories.id_route_import     as  id_route_import'    ,
                            'route_import.libelle           as  libelle_route_import'
                        ])

                        ->join("route_import"   , "categories.id_route_import"    , "route_import.id")

                        ->first();

        return $user;
    }

    //

    public static function categorieTypes(int $id)
    {

        $types      =   DB::table('types')
                        ->where('types.id_categorie', $id)

                        ->select([ 
                            'types.id                   as  id'                     , 

                            'types.nom                  as  nom'                    ,
                            'types.description          as  description'            ,

                            'types.id_route_import      as  id_route_import'        ,
                            'route_import.libelle       as  libelle_route_import'
                        ])

                        ->join("route_import"   ,   "types.id_route_import" ,   "route_import.id")

                        ->get();

        return $types;
    }
}
