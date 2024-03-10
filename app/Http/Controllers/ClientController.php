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

    public function updateClients(Request $request, int $id_route_import)
    {

        try {

            //
            DB::beginTransaction();
            //

            // update 
            Client::updateClients($request, $id_route_import);
            
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

    public function validateClient(int $id_route_import, int $id)
    {

        try {

            //
            DB::beginTransaction();
            //

            // update 
            Client::validateClient($id_route_import, $id);
            
            //
            DB::commit();
            //

            return response()->json([
                "header"            =>  "Clients Validated !"                           ,
                "message"           =>  "the client have been validated successfully !"
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

    public function getDoublesClients(int $id_route_import) {

        try {

            $getDoublant    =   Client::getDoublesClients($id_route_import);

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

    public function getDoublesTelClients(int $id_route_import) {

        try {

            $getDoublant    =   Client::getDoublesTelClients($id_route_import);

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

    public function getDoublesCustomerCodeClients(int $id_route_import) {

        try {

            $getDoublant    =   Client::getDoublesCustomerCodeClients($id_route_import);

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

    public function getDoublesCustomerNameEClients(int $id_route_import) {

        try {

            $getDoublant    =   Client::getDoublesCustomerNameEClients($id_route_import);

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

    public function getDoublesGPSClients(int $id_route_import) {

        try {

            $getDoublant    =   Client::getDoublesGPSClients($id_route_import);

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