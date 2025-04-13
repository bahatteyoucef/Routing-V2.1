<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RouteImportTempoDistrict extends Model
{
    use HasFactory;

    protected $guarded      =   [];

    protected $table        =   'route_import_tempo_districts';
    protected $primaryKey   =   'id';

    public    $timestamps   =   false;
}
