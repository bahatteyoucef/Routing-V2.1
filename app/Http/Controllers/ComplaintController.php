<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class ComplaintController extends Controller
{
    //

    public function index()
    {
        
        try {

            $complaints          =   Complaint::indexComplaint();

            return User::filterComplaints($complaints);
        }

        catch(Throwable $erreur) {

            return response()->json([
                'errors'    =>  [$erreur->getMessage()],
            ],422);
        }
    }

    public function combo()
    {
        
        try {

            $complaints          =   Complaint::comboComplaint();
            return $complaints;
        }

        catch(Throwable $erreur) {

            return response()->json([
                'errors'    =>  [$erreur->getMessage()],
            ],422);
        }
    }

    public function store(Request $request) 
    {    

        try {

            //
            DB::beginTransaction();
            //

            // validate
            $validator    =   Complaint::validateStore($request);
            
            if ($validator->fails()) {
                return response()->json([
                    'errors'    =>  $validator->errors(),
                ],422);
            }

            // store
            Complaint::storeComplaint($request);

            //
            DB::commit();
            //

            return response()->json([
                "header"        =>  "Complaint Added !",
                "message"       =>  "a new complaint has been added successfully !"
            ]);
        }

        catch(Throwable $erreur) {

            //
            DB::rollBack();
            //

            return response()->json([
                'errors'    =>  [$erreur->getMessage()],
            ],422);
        }
    }

    public function update(Request $request, int $id) 
    {

        try {

            //
            DB::beginTransaction();
            //

            // validate
            $validator    =   Complaint::validateUpdate($request, $id);
            
            if ($validator->fails()) {
                return response()->json([
                    'errors'    =>  $validator->errors(),
                ],422);
            }

            // update
            Complaint::updateComplaint($request, $id);

            //
            DB::commit();
            //

            return response()->json([
                "header"        =>  "Complaint Updated !",
                "message"       =>  "a complaint has been updated successfully !"
            ]);
        }

        catch(Throwable $erreur) {

            //
            DB::rollBack();
            //

            return response()->json([
                'errors'    =>  [$erreur->getMessage()],
            ],422);
        }
    }
    
    public function show(int $id)
    {

        try {

            $categorie  =   Complaint::showComplaint($id);
            return $categorie;
        }

        catch(Throwable $erreur) {

            return response()->json([
                'errors'    =>  [$erreur->getMessage()],
            ],422);
        }
    }

    //
}
