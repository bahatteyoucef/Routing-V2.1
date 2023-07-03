<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use Laravel\Passport\HasApiTokens;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $guarded      =   [];

    protected $table        =   'users';
    protected $primaryKey   =   'id';

    public    $timestamps   =   false;

    // 

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    //

    public static function indexUser() 
    {
    
        $users      =   DB::table('users')

                            ->select([ 
                                'users.id                   as  id'                 , 

                                'users.nom                  as  nom'                ,
                                'users.email                as  email'              
                            ])

                            ->orderBy('users.id','desc')

                            ->get();

        return $users;
    }

    public static function comboUser() 
    {
    
        $users    =   DB::table('users')

                            ->select([ 
                                'users.id                   as  id'                 , 

                                'users.nom                  as  nom'                ,
                                'users.email                as  email'              
                            ])

                            ->orderBy('users.id','desc')

                            ->get();

        return $users;
    }

    public static function validateStore(Request $request) 
    {

        $validator = Validator::make($request->all(), [
            'nom'               =>  ["required", "max:255"],
            'email'             =>  ["required", Rule::unique('users')  , "email", "max:255"],
            'password'          =>  ["required", "confirmed"            , "min:6", "max:255"]
        ]);
    
        return $validator;
    }

    public static function storeUser(Request $request) 
    {
        
        $user = new User([
            'nom'               =>  $request->input('nom'),
            'email'             =>  $request->input('email'),
            'password'          =>  Hash::make($request->input('password')),
            'owner'             =>  Auth::user()->id
        ]);

        $user->save();
    }

    public static function validateUpdate(Request $request, int $id) 
    {

        $validator = Validator::make($request->all(), [
            'nom'               =>  ["required", "max:255"],
            'email'             =>  ["required", Rule::unique('users')->ignore($id), "email", "max:255"]
        ]);
    
        return $validator;
    }

    public static function updateUser(Request $request, int $id) 
    {

        $user                       =   User::find($id);

        $user->nom                  =   $request->input('nom');
        $user->email                =   $request->input('email');

        $user->save();
    }       

    public static function showUser(int $id) 
    {
    
        $user       =   DB::table('users')
                            ->where('users.id',$id)

                            ->select([ 
                                'users.id                   as  id'                 , 

                                'users.nom                  as  nom'                ,
                                'users.email                as  email'              
                            ])

                            ->first();

        return $user;
    }
}
