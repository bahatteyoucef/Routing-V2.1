<?php

namespace App\Http\Controllers;

use App\Models\JourneeTerritory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class JourneeTerritoryController extends Controller {

    public function storeJourneeTerritory(Request $request, int $id_route_import) {

        try {

            //
            DB::beginTransaction();
            //

            // validate
            $validator    =   JourneeTerritory::validateStore($request);
            
            if ($validator->fails()) {
                return response()->json([
                    'errors'    =>  $validator->errors(),
                ],422);
            }

            // store 
            JourneeTerritory::storeJourneeTerritory($request, $id_route_import);

            //
            DB::commit();
            //

            return response()->json([
                "header"            =>  "JourneeTerritory Added !"              ,
                "message"           =>  "a new journee_territory has been added successfuly!"
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

    public function updateJourneeTerritory(Request $request, int $id_route_import, int $id_journee_territory) {

        try {

            //
            DB::beginTransaction();
            //

            // validate
            $validator    =   JourneeTerritory::validateUpdate($request);
            
            if ($validator->fails()) {
                return response()->json([
                    'errors'    =>  $validator->errors(),
                ],422);
            }

            // update 
            JourneeTerritory::updateJourneeTerritory($request, $id_route_import, $id_journee_territory);

            //
            DB::commit();
            //

            return response()->json([
                "header"            =>  "JourneeTerritory Updated !"                     ,
                "message"           =>  "a journee_territory has been updated successfuly!"
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

    public function deleteJourneeTerritory(Request $request, int $id_route_import, int $id_journee_territory) {

        try {

            //
            DB::beginTransaction();
            //

            // delete 
            JourneeTerritory::deleteJourneeTerritory($id_journee_territory);

            //
            DB::commit();
            //

            return response()->json([
                "header"            =>  "JourneeTerritory Deleted !"                     ,
                "message"           =>  "a journee_territory has been deleted successfuly!"
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