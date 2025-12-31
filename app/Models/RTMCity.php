<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RTMCity extends Model
{
    use HasFactory;

    protected $guarded      =   [];

    protected $table        =   'RTM_City';
    protected $primaryKey   =   'CityNo';

    public    $timestamps   =   false;

    //

    public static function detailsCity() {

        $willayas   =   DB::table("RTM_Willaya")->orderBy('DistrictNameE')->get();

        foreach ($willayas as $willaya) {

            $willaya->cites =   DB::table("RTM_City")->where('DistrictNo', $willaya->DistrictNo)->orderBy('CityNameE')->get();
        }

        return $willayas;
    }
}