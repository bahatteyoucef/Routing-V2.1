<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCity extends Model
{
    use HasFactory;

    protected $guarded      =   [];

    protected $table        =   'users_cities';
    protected $primaryKey   =   'id';

    public    $timestamps   =   false;

    // 
}
