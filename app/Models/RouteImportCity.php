<?php

namespace App\Models;

use Illuminate\Http\Request;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RouteImportCity extends Model
{
    use HasFactory;

    protected $guarded      =   [];

    protected $table        =   'route_import_cities';
    protected $primaryKey   =   'id';

    public    $timestamps   =   false;
}