<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    //

    protected $guarded      =   [];

    protected $table        =   'prices';
    protected $primaryKey   =   'id';

    // 

    use HasFactory;

    //
}
