<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RTMWillaya extends Model
{
    use HasFactory; 

    protected $guarded      =   [];

    protected $table        =   'RTM_Willaya';
    protected $primaryKey   =   'DistrictNo';

    public    $timestamps   =   false;
}
