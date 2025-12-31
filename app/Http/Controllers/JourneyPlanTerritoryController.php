<?php

namespace App\Http\Controllers;

use App\Models\JourneyPlanTerritory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class JourneyPlanTerritoryController extends Controller
{
    public function storeJourneyPlanTerritory(Request $request, int $id_route_import)
    {

        try {

            //
            DB::beginTransaction();
            //

            // validate
            $validator    =   JourneyPlanTerritory::validateStore($request);
            
            if ($validator->fails()) {
                return response()->json([
                    'errors'    =>  $validator->errors(),
                ],422);
            }

            // store 
            JourneyPlanTerritory::storeJourneyPlanTerritory($request, $id_route_import);

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

    public function updateJourneyPlanTerritory(Request $request, int $id_route_import, int $id_journey_plan_territory)
    {

        try {

            //
            DB::beginTransaction();
            //

            // validate
            $validator    =   JourneyPlanTerritory::validateUpdate($request);
            
            if ($validator->fails()) {
                return response()->json([
                    'errors'    =>  $validator->errors(),
                ],422);
            }

            // update 
            JourneyPlanTerritory::updateJourneyPlanTerritory($request, $id_route_import, $id_journey_plan_territory);

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

    public function deleteJourneyPlanTerritory(Request $request, int $id_route_import, int $id_journey_plan_territory)
    {

        try {

            //
            DB::beginTransaction();
            //

            // delete 
            JourneyPlanTerritory::deleteJourneyPlanTerritory($id_journey_plan_territory);

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
}