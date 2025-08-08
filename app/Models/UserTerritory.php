<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserTerritory extends Model
{
    use HasFactory;

    protected $guarded      =   [];

    protected $table        =   'user_territories';
    protected $primaryKey   =   'id';

    public    $timestamps   =   false;

    //

    public static function validateStore(Request $request) {

        $validator = Validator::make($request->all(), [
            'description'   =>  ["required", "max:255"] ,
            'id_user'       =>  ["required"]            ,
            'latlngs'       =>  ["required"]            ,
            'color'         =>  ["required", "max:255"]
        ]);
    
        return $validator;
    }

    public static function storeUserTerritory(Request $request, int $id_route_import) {

        $user_territory       =   new UserTerritory([
            'description'       =>  $request->get("description")    ,
            'id_user'           =>  $request->get("id_user")        ,
            'latlngs'           =>  $request->get("latlngs")        ,
            'color'             =>  $request->get("color")          ,
            'id_route_import'   =>  $id_route_import                ,
            'owner'             =>  Auth::user()->id
        ]);

        $user_territory->save();
    }

    //

    public static function validateUpdate(Request $request) {

        $validator = Validator::make($request->all(), [
            'description'   =>  ["required", "max:255"] ,
            'id_user'       =>  ["required"]            ,
            'latlngs'       =>  ["required"]            ,
            'color'         =>  ["required", "max:255"]
        ]);
    
        return $validator;
    }

    public static function updateUserTerritory(Request $request, int $id_route_import, int $id_user_territory) {

        $user_territory   =   UserTerritory::find($id_user_territory);

        if($user_territory) {

            $user_territory->description      =   $request->get("description");
            $user_territory->id_user          =   $request->get("id_user");
            $user_territory->latlngs          =   $request->get("latlngs");
            $user_territory->color            =   $request->get("color");
            $user_territory->id_route_import  =   $id_route_import;

            $user_territory->save();
        }
    }

    //

    public static function deleteUserTerritory(int $id_user_territory) {

        // Delete
        $user_territory   =   UserTerritory::find($id_user_territory);

        if($user_territory) {

            $user_territory->delete();
        }
    }
}