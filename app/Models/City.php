<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model {

    use HasFactory;

    protected $guarded      =   [];

    protected $table        =   'RTM_City';
    protected $primaryKey   =   'CityNo';

    public    $timestamps   =   false;
}