<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class ClientController extends Controller
{
    //

    public function showClient(Request $request, int $id_route_import, int $id)
    {

        try {

            // update 
            $client =   Client::showClient($request, $id_route_import, $id);

            return $client;
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
            $client =   Client::storeClient($request, $id_route_import);

            //
            DB::commit();
            //

            return response()->json([   
                "header"            =>  "Client Added !"                            ,
                "message"           =>  "a new client has been added successfully !",
                "client"            =>  $client
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
            $validator  =   Client::validateUpdate($request);
            
            if ($validator->fails()) {
                return response()->json([
                    'errors'    =>  $validator->errors(),
                ],422);
            }

            // update
            $client     =   Client::updateClient($request, $id_route_import, $id);
            
            //
            DB::commit();
            //

            return response()->json([
                "client"            =>  $client             ,
                "header"            =>  "Client Updated !"  ,
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

    public function updateResumeClients(Request $request)
    {

        try {

            //
            DB::beginTransaction();
            //

            // update 
            Client::updateResumeClients($request);
            
            //
            DB::commit();
            //

            return response()->json([
                "header"            =>  "Clients Updated !"                      ,
                "message"           =>  "the clients have been updated successfully !"
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

    public function multiUpdateClients(Request $request, int $id_route_import)
    {

        try {

            //
            DB::beginTransaction();
            //

            // delete 
            Client::multiUpdateClients($request, $id_route_import);

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

    public function deleteClients(Request $request, int $id_route_import)
    {

        try {

            //
            DB::beginTransaction();
            //

            // delete 
            Client::deleteClients($request, $id_route_import);

            //
            DB::commit();
            //

            return response()->json([
                "header"            =>  "Clients Deleted !"                                      ,
                "message"           =>  "a group of clients has been deleted successfully !"
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

    public function getDoublesClients(Request $request, int $id_route_import) {

        try {

            $getDoublant    =   Client::getDoublesClients($request, $id_route_import);

            return $getDoublant;
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

    public function getDoublesTelClients(Request $request, int $id_route_import) {

        try {

            $getDoublant    =   Client::getDoublesTelClients($request, $id_route_import);

            return $getDoublant;
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

    public function getDoublesCustomerCodeClients(Request $request, int $id_route_import) {

        try {

            $getDoublant    =   Client::getDoublesCustomerCodeClients($request, $id_route_import);

            return $getDoublant;
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

    public function getDoublesCustomerNameEClients(Request $request, int $id_route_import) {

        try {

            $getDoublant    =   Client::getDoublesCustomerNameEClients($request, $id_route_import);

            return $getDoublant;
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

    public function getDoublesGPSClients(Request $request, int $id_route_import) {

        try {

            $getDoublant    =   Client::getDoublesGPSClients($request, $id_route_import);

            return $getDoublant;
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