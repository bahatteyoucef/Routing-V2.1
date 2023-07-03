<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class ClientController extends Controller
{
    //

    public function storeClient(Request $request, int $id_route_import)
    {

        try {

            //
            DB::beginTransaction();
            //

            // validate
            $validator    =   Client::validateStore($request);
            
            if ($validator->fails()) {
                return response()->json([
                    'errors'    =>  $validator->errors(),
                ],422);
            }

            // store 
            Client::storeClient($request, $id_route_import);

            //
            DB::commit();
            //

            return response()->json([   
                "header"            =>  "Client Added !"                            ,
                "message"           =>  "a new client has been added successfully !"
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

    public function updateClient(Request $request, int $id_route_import, int $id)
    {

        try {

            //
            DB::beginTransaction();
            //

            // validate
            $validator    =   Client::validateUpdate($request);
            
            if ($validator->fails()) {
                return response()->json([
                    'errors'    =>  $validator->errors(),
                ],422);
            }

            // update 
            Client::updateClient($request, $id_route_import, $id);
            
            //
            DB::commit();
            //

            return response()->json([
                "header"            =>  "Client Updated !"                      ,
                "message"           =>  "a client has been updated successfully !"
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

    public function deleteClient(int $id_route_import, int $id)
    {

        try {

            //
            DB::beginTransaction();
            //

            // delete 
            Client::deleteClient($id_route_import, $id);

            //
            DB::commit();
            //

            return response()->json([
                "header"            =>  "Client Deleted !"                   ,
                "message"           =>  "a client has been deleted successfully !"
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

    public function changeRouteClients(Request $request, int $id_route_import)
    {

        try {

            //
            DB::beginTransaction();
            //

            // delete 
            Client::changeRouteClients($request, $id_route_import);

            //
            DB::commit();
            //

            return response()->json([
                "header"            =>  "Clients Changed Infoes !"                                      ,
                "message"           =>  "a group of clients informations has been updated successfully !"
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
