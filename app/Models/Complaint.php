<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class Complaint extends Model
{
    //

    protected $guarded      =   [];

    protected $table        =   'complaints';
    protected $primaryKey   =   'id';

    // 

    use HasFactory;

    //

    public static function indexComplaint() 
    {
    
        $complaints     =   DB::table('complaints')

                            ->select([ 
                                'complaints.id                  as  id'                    , 

                                'complaints.type_complaint      as  type_complaint'         
                            ])

                            ->get();

        return $complaints;
    }

    public static function comboComplaint() 
    {
    
        $complaints     =   DB::table('complaints')

                            ->select([ 
                                'complaints.id                  as  id'                    , 

                                'complaints.type_complaint      as  type_complaint'         
                            ])

                            ->get();

        return $complaints;
    }

    public static function validateStore(Request $request) 
    {

        $validator = Validator::make($request->all(), [
            'type_complaint'    =>  ["required", "max:255"      ],
            'description'       =>  ["required"                 ],

            'id_client'         =>  ["required", "integer"      ],
            'id_route_import'   =>  ["required", "integer"      ]
        ]);
    
        return $validator;
    }

    public static function storeComplaint(Request $request) 
    {
        
        $complaint = new Complaint([
            'type_complaint'    =>  $request->input('type_complaint')   ,
            'description'       =>  $request->input('description')      ,

            'id_client'         =>  $request->input('id_client')        ,
            'id_route_import'   =>  $request->input('id_route_import')  ,

            'owner'             =>  Auth::user()->id
        ]);

        $complaint->save();
    }

    public static function validateUpdate(Request $request, int $id) 
    {

        $validator = Validator::make($request->all(), [
            'type_complaint'    =>  ["required", "max:255"      ],
            'description'       =>  ["required"                 ],

            'id_client'         =>  ["required", "integer"      ],
            'id_route_import'   =>  ["required", "integer"      ]
        ]);
    
        return $validator;
    }

    public static function updateComplaint(Request $request, int $id) 
    {

        $user                       =   Complaint::find($id);

        $user->type_complaint       =   $request->input('type_complaint');
        $user->description          =   $request->input('description');

        $user->id_client            =   $request->input('id_client');
        $user->id_route_import      =   $request->input('id_route_import');

        $user->save();
    }       

    public static function showComplaint(int $id) 
    {
    
        $user       =   DB::table('complaints')
                        ->where('complaints.id', $id)

                        ->select([ 
                            'complaints.id                  as  id'                 , 

                            'complaints.type_complaint      as  type_complaint'     ,   
                            'complaints.description         as  description'         
                        ])

                        ->first();

        return $user;
    }
}
