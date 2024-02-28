<?php

namespace App\Http\Controllers;

use App\Models\JourneyPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class JourneyPlanController extends Controller
{
    //

    public function storeJourneyPlan(Request $request, int $id_route_import)
    {

        try {

            //
            DB::beginTransaction();
            //

            // validate
            $validator    =   JourneyPlan::validateStore($request);
            
            if ($validator->fails()) {
                return response()->json([
                    'errors'    =>  $validator->errors(),
                ],422);
            }

            // store 
            JourneyPlan::storeJourneyPlan($request, $id_route_import);

            //
            DB::commit();
            //

            return response()->json([
                "header"            =>  "Journey Plan Added !"             ,
                "message"           =>  "a new journey plan has been added successfuly!"
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

    public function updateJourneyPlan(Request $request, int $id_route_import, int $id_journey_plan)
    {

        try {

            //
            DB::beginTransaction();
            //

            // validate
            $validator    =   JourneyPlan::validateUpdate($request);
            
            if ($validator->fails()) {
                return response()->json([
                    'errors'    =>  $validator->errors(),
                ],422);
            }

            // update 
            JourneyPlan::updateJourneyPlan($request, $id_route_import, $id_journey_plan);

            //
            DB::commit();
            //

            return response()->json([
                "header"            =>  "Journey Plan Updated !"             ,
                "message"           =>  "a new journey plan has been updated successfuly!"
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

    public function deleteJourneyPlan(Request $request, int $id_route_import, int $id_journey_plan)
    {

        try {

            //
            DB::beginTransaction();
            //

            // delete 
            JourneyPlan::deleteJourneyPlan($id_journey_plan);

            //
            DB::commit();
            //

            return response()->json([
                "header"            =>  "Journey Plan Deleted !"              ,
                "message"           =>  "a journey plan has been deleted successfuly!"
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
