<?php

namespace App\Http\Controllers;

use App\Models\CompetitorAnalysis;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;
use Throwable;

class CompetitorAnalysisController extends Controller
{
    //

    public function storeListeCompetitorAnalysis(Request $request)
    {

        try {

            //
            DB::beginTransaction();
            //
            
            // store 
            CompetitorAnalysis::storeListeCompetitorAnalysis($request);

            //
            DB::commit();
            //

            return response()->json([   
                "header"            =>  "Analysis Added !"                                          ,
                "message"           =>  "a new competitors analysis has been added successfully !"
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
