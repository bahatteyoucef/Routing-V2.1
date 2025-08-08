<?php

namespace App\Http\Controllers;

use App\Models\District;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class DistrictController extends Controller {

    public function updateExpectedClients(int $DistrictNo, Request $request) {

        try {

            //
            DB::beginTransaction();
            //

            // update 
            District::updateExpectedClients($DistrictNo, $request);
            
            //
            DB::commit();
            //

            return response()->json([
                "header"            =>  "Expected Clients Updated !"                                        ,
                "message"           =>  "the expected number of clients have been updated successfully !"
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