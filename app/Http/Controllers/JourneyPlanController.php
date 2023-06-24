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

            // store 
            JourneyPlan::storeJourneyPlan($request, $id_route_import);

            //
            DB::commit();
            //

            return response()->json([
                "header"            =>  "Journey Plan Ajouté !"             ,
                "message"           =>  "une journey plan a été ajoutée !"
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

    public function deleteJourneyPlan(Request $request, int $id_route_import, string $JPlan)
    {

        try {

            //
            DB::beginTransaction();
            //

            // delete 
            JourneyPlan::deleteJourneyPlan($JPlan, $id_route_import);

            //
            DB::commit();
            //

            return response()->json([
                "header"            =>  "Journey Plan Supprimée !"              ,
                "message"           =>  "une journey plan a été supprimee !"
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
