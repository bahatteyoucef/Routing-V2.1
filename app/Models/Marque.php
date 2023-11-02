<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Fluent;

class Marque extends Model
{
    //

    protected $guarded      =   [];

    protected $table        =   'marques';
    protected $primaryKey   =   'id';

    public    $timestamps   =   false;

    // 

    use HasFactory;

    //

    public static function indexMarque() 
    {
    
        $marques        =   DB::table('marques')

                            ->select([ 
                                'marques.id                 as  id'                     , 

                                'marques.nom                as  nom'                    ,
                                'marques.description        as  description'            ,

                                'marques.id_route_import    as  id_route_import'        ,
                                'route_import.libelle       as  libelle_route_import'
                            ])

                            ->join("route_import"   , "marques.id_route_import"    , "route_import.id")

                            ->get();

        return $marques;
    }

    public static function comboMarque() 
    {
    
        $marques      =   DB::table('marques')

                        ->select([ 
                            'marques.id                as  id'                 , 

                            'marques.nom               as  nom'                ,
                            'marques.description       as  description'        
                        ])

                        ->get();

        return $marques;
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

    public static function storeMarque(Request $request) 
    {
        
        $marque = new Marque([
            'nom'               =>  $request->input('nom')              ,
            'description'       =>  $request->input('description')      ,

            'id_route_import'   =>  $request->input('id_route_import')  ,
            'owner'             =>  Auth::user()->id
        ]);

        $marque->save();
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

    public static function updateMarque(Request $request, int $id) 
    {

        $user                       =   Marque::find($id);

        $user->nom                  =   $request->input('nom');
        $user->description          =   $request->input('description');

        $user->id_route_import      =   $request->input('id_route_import');

        $user->save();
    }       

    public static function showMarque(int $id) 
    {
    
        $user       =   DB::table('marques')
                        ->where('marques.id', $id)

                        ->select([ 
                            'marques.id                 as  id'                     , 

                            'marques.nom                as  nom'                    ,
                            'marques.description        as  description'            ,

                            'marques.id_route_import    as  id_route_import'        ,
                            'route_import.libelle       as  libelle_route_import'
                        ])

                        ->join("route_import"   , "marques.id_route_import"    , "route_import.id")

                        ->first();

        return $user;
    }

    //

    public static function marqueTypes(int $id)
    {

        $types      =   DB::table('types')
                        ->where('types.id_route_import', $id)

                        ->select([ 
                            'types.id                   as  id'                     , 

                            'types.nom                  as  nom'                    ,
                            'types.description          as  description'            ,

                            'types.id_route_import      as  nom_type'               ,
                        ])

                        ->get();

        return $types;
    }
}
