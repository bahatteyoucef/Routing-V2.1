<?php

namespace App\Http\Controllers;

use App\Models\RouteImportTempo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class RouteImportTempoController extends Controller
{
    //

    public function lastTempo()
    {

        try {

            $last_route_import     =   RouteImportTempo::lastTempo();
            return $last_route_import;
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
            $validator      =   RouteImportTempo::validateStore($request);
            
            if ($validator->fails()) {
                return response()->json([
                    'errors'    =>  $validator->errors(),
                ],422);
            }

            // store 
            RouteImportTempo::storeRouteImportTempo($request);

            //
            DB::commit();
            //

            return response()->json([
                "header"            =>  "Route Temporarily Added !"                             ,
                "message"           =>  "a new route temporarily has been added successfuly!"   
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

    public function getFile()
    {

        try {

            $route_import_tempo  =   RouteImportTempo::where('owner', Auth::user()->id)->first();

            if($route_import_tempo) {

                // Get the file path or generate it dynamically
                $filePath = public_path('uploads/route_import_tempo/'.$route_import_tempo->file);
                
                // Check if the file exists
                if (file_exists($filePath)) {

                    // Set the appropriate headers for the file
                    $headers = [
                        'Content-Type'          =>  'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                        'Content-Disposition'   =>  'attachment; filename="'+$route_import_tempo->file+'"',
                    ];

                    // Return the file as a response
                    return response()->file($filePath, $headers);

                } else {
                    // File not found, return an error response or redirect
                    return response()->json(['error' => 'File not found.'], 404);
                }
            }

            else {

                return null;
            }
        }

        catch(Throwable $erreur) {

            return response()->json([
                'errors'    =>  [$erreur->getMessage()],
            ],422);
        }
    }
}
