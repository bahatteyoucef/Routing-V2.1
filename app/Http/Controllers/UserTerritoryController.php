<?php

namespace App\Http\Controllers;

use App\Models\UserTerritory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class UserTerritoryController extends Controller
{
    //

    public function storeUserTerritory(Request $request, int $id_route_import)
    {

        try {

            //
            DB::beginTransaction();
            //

            // validate
            $validator    =   UserTerritory::validateStore($request);
            
            if ($validator->fails()) {
                return response()->json([
                    'errors'    =>  $validator->errors(),
                ],422);
            }

            // store 
            UserTerritory::storeUserTerritory($request, $id_route_import);

            //
            DB::commit();
            //

            return response()->json([
                "header"            =>  "User Territory Added !"             ,
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

    public function updateUserTerritory(Request $request, int $id_route_import, int $id_user_territory)
    {

        try {

            //
            DB::beginTransaction();
            //

            // validate
            $validator    =   UserTerritory::validateUpdate($request);
            
            if ($validator->fails()) {
                return response()->json([
                    'errors'    =>  $validator->errors(),
                ],422);
            }

            // update 
            UserTerritory::updateUserTerritory($request, $id_route_import, $id_user_territory);

            //
            DB::commit();
            //

            return response()->json([
                "header"            =>  "User Territory Updated !"                              ,
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

    public function deleteUserTerritory(Request $request, int $id_route_import, int $id_user_territory)
    {

        try {

            //
            DB::beginTransaction();
            //

            // delete 
            UserTerritory::deleteUserTerritory($id_user_territory);

            //
            DB::commit();
            //

            return response()->json([
                "header"            =>  "User Territory Deleted !"              ,
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
