<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public static function storeJournee(Request $request, int $id_route_import) {

        $old_journee                =   Journee::where([['id_route_import', $id_route_import], ['JPlan', $request->get("old_JPlan")], ['Journee', $request->get("old_Journee")]])->first();

        if($old_journee) {

            $old_journee->latlngs   =   null;
            $old_journee->color     =   null;

            $old_journee->save();
        }

        //

        $journee        =   Journee::where([['id_route_import', $id_route_import], ['JPlan', $request->get("JPlan")], ['Journee', $request->get("Journee")]])->first();

        if($journee) {

            $journee->Journee           =   $request->get("Journee");
            $journee->JPlan             =   $request->get("JPlan");
            $journee->latlngs           =   $request->get("latlngs");
            $journee->color             =   $request->get("color");
            $journee->id_route_import   =   $id_route_import;

            $journee->save();
        }

        else {

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
    }

    public static function deleteJournee(string $JPlan, string $Journee, int $id_route_import) {

        $journee        =   Journee::where([['id_route_import', $id_route_import], ['JPlan', $JPlan], ['Journee', $Journee]])->first();

        if($journee) {

            $journee->latlngs   =   null;
            $journee->save();
        }

        else {

        }
    }

    //
}
