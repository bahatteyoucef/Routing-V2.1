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

            // store 
            Journee::storeJournee($request, $id_route_import);

            //
            DB::commit();
            //

            return response()->json([
                "header"            =>  "Journee Ajouté !"              ,
                "message"           =>  "une journee a été ajoutée !"
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

    public function deleteJournee(Request $request, int $id_route_import, string $Journee)
    {

        try {

            //
            DB::beginTransaction();
            //

            // delete 
            Journee::deleteJournee($request->get("JPlan"), $Journee, $id_route_import);

            //
            DB::commit();
            //

            return response()->json([
                "header"            =>  "Journee Supprimée !"              ,
                "message"           =>  "une journee a été supprimee !"
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
