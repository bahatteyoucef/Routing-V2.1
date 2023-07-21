<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RouteImportFile extends Model
{
    use HasFactory; 

    protected $guarded      =   [];

    protected $table        =   'route_import_files';
    protected $primaryKey   =   'id';

    public    $timestamps   =   false;
}
