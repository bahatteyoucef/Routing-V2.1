<?php

namespace App\Http\Controllers;

use App\Models\Journee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class JourneeController extends Controller
{
    //

    public function storeJournee(Request $request, int $id_route_import)
    {

        try {

            //
            DB::beginTransaction();
            //

            // validate
            $validator    =   Journee::validateStore($request);
            
            if ($validator->fails()) {
                return response()->json([
                    'errors'    =>  $validator->errors(),
                ],422);
            }

            // store 
            Journee::storeJournee($request, $id_route_import);

            //
            DB::commit();
            //

            return response()->json([
                "header"            =>  "Journee Added !"              ,
                "message"           =>  "a new journee has been added successfuly!"
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

    public function updateJournee(Request $request, int $id_route_import, int $id_journee)
    {

        try {

            //
            DB::beginTransaction();
            //

            // validate
            $validator    =   Journee::validateUpdate($request);
            
            if ($validator->fails()) {
                return response()->json([
                    'errors'    =>  $validator->errors(),
                ],422);
            }

            // update 
            Journee::updateJournee($request, $id_route_import, $id_journee);

            //
            DB::commit();
            //

            return response()->json([
                "header"            =>  "Journee Updated !"                     ,
                "message"           =>  "a journee has been updated successfuly!"
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

    public function deleteJournee(Request $request, int $id_route_import, int $id_journee)
    {

        try {

            //
            DB::beginTransaction();
            //

            // delete 
            Journee::deleteJournee($id_journee);

            //
            DB::commit();
            //

            return response()->json([
                "header"            =>  "Journee Deleted !"                     ,
                "message"           =>  "a journee has been deleted successfuly!"
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

    //
}
