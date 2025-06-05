<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RTMWillaya extends Model
{
    use HasFactory; 

    protected $guarded      =   [];

    protected $table        =   'RTM_Willaya';
    protected $primaryKey   =   'DistrictNo';

    public    $timestamps   =   false;

    //

    public static function index() {

        return DB::table("RTM_Willaya")->orderBy('DistrictNameE')->get();
    }
}
