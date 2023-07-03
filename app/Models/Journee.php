<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class Journee extends Model
{
    use HasFactory;

    protected $guarded      =   [];

    protected $table        =   'journees';
    protected $primaryKey   =   'id';

    public    $timestamps   =   false;

    //

    public static function storeJournees(Request $request, int $id_route_import) {

        $liste_journee      =   Client::where('id_route_import', $id_route_import)->distinct()->get(['Journee', 'JPlan']);

        foreach ($liste_journee as $Journee) {

            $Journee        =   new Journee([
                'Journee'           =>  $Journee->Journee   ,
                'JPlan'             =>  $Journee->JPlan     ,
                'id_route_import'   =>  $id_route_import    ,
                'owner'             =>  Auth::user()->id
            ]);

            $Journee->save();
        }
    }

    //

    public static function validateStore(Request $request) 
    {

        $validator = Validator::make($request->all(), [
            'Journee'       =>  ["required", "max:255"],
            'JPlan'         =>  ["required", "max:255"],
            'latlngs'       =>  ["required", "max:255"],
            'color'         =>  ["required", "max:255"],
        ]);
    
        return $validator;
    }

    public static function storeJournee(Request $request, int $id_route_import) {

        $journee        =   new Journee([
            'Journee'           =>  $request->get("Journee")    ,
            'JPlan'             =>  $request->get("JPlan")      ,
            'latlngs'           =>  $request->get("latlngs")    ,
            'color'             =>  $request->get("color")      ,
            
            'id_route_import'   =>  $id_route_import            ,
            'owner'             =>  Auth::user()->id
        ]);

        $journee->save();
    }

    //

    public static function validateUpdate(Request $request) 
    {

        $validator = Validator::make($request->all(), [
            'Journee'       =>  ["required", "max:255"],
            'JPlan'         =>  ["required", "max:255"],
            'latlngs'       =>  ["required", "max:255"],
            'color'         =>  ["required", "max:255"],
        ]);
    
        return $validator;
    }

    public static function updateJournee(Request $request, int $id_route_import, int $id_journee) {

        $journee        =   Journee::find($id_journee);

        if($journee) {

            $journee->Journee           =   $request->get("Journee");
            $journee->JPlan             =   $request->get("JPlan");
            $journee->latlngs           =   $request->get("latlngs");
            $journee->color             =   $request->get("color");
            $journee->id_route_import   =   $id_route_import;

            $journee->save();
        }
    }

    //

    public static function deleteJournee(int $id_journee) {

        $journee        =   Journee::find($id_journee);

        if($journee) {

            $journee->delete();
        }

        else {

        }
    }

    //
}
