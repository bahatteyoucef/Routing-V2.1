<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Fluent;

class Type extends Model
{
    //

    protected $guarded      =   [];

    protected $table        =   'types';
    protected $primaryKey   =   'id';

    public    $timestamps   =   false;

    //

    use HasFactory;

    //

    public static function indexType() 
    {
    
        $types      =   DB::table('types')

                        ->select([ 
                            'types.id                   as  id'                     , 

                            'types.nom                  as  nom'                    ,
                            'types.description          as  description'            ,

                            'types.id_categorie         as  id_categorie'           ,
                            'categories.nom             as  libelle_categorie'      ,


                            'types.id_route_import      as  id_route_import'        ,
                            'route_import.libelle       as  libelle_route_import'
                        ])

                        ->join("categories"     , "types.id_categorie"      , "categories.id")
                        ->join("route_import"   , "types.id_route_import"   , "route_import.id")

                        ->get();

        return $types;
    }

    public static function comboType() 
    {
    
        $types      =   DB::table('types')

                        ->select([ 
                            'types.id                as  id'                 , 

                            'types.nom               as  nom'                ,
                            'types.description       as  description'        
                        ])

                        ->get();

        return $types;
    }

    public static function validateStore(Request $request) 
    {

        $validator = Validator::make($request->all(), [
            'nom'               =>  ["required", "max:255"      ],
            'description'       =>  ["required"                 ],

            'id_categorie'      =>  ["required", "integer"      ],
            'id_route_import'   =>  ["required", "integer"      ]
        ]);
    
        return $validator;
    }

    public static function storeType(Request $request) 
    {
        
        $type = new Type([
            'nom'               =>  $request->input('nom')              ,
            'description'       =>  $request->input('description')      ,

            'id_categorie'      =>  $request->input('id_categorie')     ,

            'id_route_import'   =>  $request->input('id_route_import')  ,
            'owner'             =>  Auth::user()->id
        ]);

        $type->save();
    }

    public static function validateUpdate(Request $request, int $id) 
    {

        $validator = Validator::make($request->all(), [
            'nom'               =>  ["required", "max:255"      ],
            'description'       =>  ["required"                 ],
            'id_categorie'      =>  ["required", "integer"      ],
            'id_route_import'   =>  ["required", "integer"      ]
        ]);
    
        return $validator;
    }

    public static function updateType(Request $request, int $id) 
    {

        $user                       =   Type::find($id);

        $user->nom                  =   $request->input('nom');
        $user->description          =   $request->input('description');

        $user->id_categorie         =   $request->input('id_categorie');
        $user->id_route_import      =   $request->input('id_route_import');

        $user->save();
    }       

    public static function showType(int $id) 
    {
    
        $user      =   DB::table('types')
                        ->where('types.id', $id)

                        ->select([ 
                            'types.id                   as  id'                     , 

                            'types.nom                  as  nom'                    ,
                            'types.description          as  description'            ,

                            'types.id_categorie         as  id_categorie'           ,
                            'categories.nom             as  libelle_categorie'      ,


                            'types.id_route_import      as  id_route_import'        ,
                            'route_import.libelle       as  libelle_route_import'
                        ])

                        ->join("categories"     , "types.id_categorie"      , "categories.id")
                        ->join("route_import"   , "types.id_route_import"   , "route_import.id")

                        ->first();

        return $user;
    }
}
