<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRouteImport extends Model
{
    use HasFactory;

    protected $guarded      =   [];

    protected $table        =   'users_route_import';
    protected $primaryKey   =   'id';

    public    $timestamps   =   false;

    // 

}
