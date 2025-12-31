<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class ClientController extends Controller
{

    public function showClient(Request $request, int $id_route_import, int $id_client) {

        try {
            $client =   Client::showClient($request, $id_route_import, $id_client);
            return response()->json([   
                "client"            =>  $client
            ]);
        }

        catch(Throwable $erreur) {
            return response()->json([
                'errors'    =>  [$erreur->getMessage()],
            ],422);
        }
    }

    //  //  //  //  //
    //  //  //  //  //
    //  //  //  //  //

    public function storeClient(Request $request, int $id_route_import) {

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

    public function updateClient(Request $request, int $id_route_import, int $id_client)
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
            $client     =   Client::updateClient($request, $id_route_import, $id_client);
            
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

    public function deleteClient(int $id_route_import, int $id_client) {

        try {

            //
            DB::beginTransaction();
            //

            // delete 
            Client::deleteClient($id_route_import, $id_client);

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

    //  //  //  //  //
    //  //  //  //  //
    //  //  //  //  //

    public function updateResumeClients(Request $request) {

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

    //  //  //  //  //
    //  //  //  //  //
    //  //  //  //  //

    public function updateClients(Request $request, int $id_route_import) {

        try {

            //
            DB::beginTransaction();
            //

            // delete 
            Client::updateClients($request, $id_route_import);

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

    //  //  //  //  //
    //  //  //  //  //
    //  //  //  //  //

    public function deleteClients(Request $request, int $id_route_import) {

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

    //  //  //  //  //
    //  //  //  //  //
    //  //  //  //  //

    public function getDoublesClients(Request $request, $id_route_import) {
        $data = Client::getDoublesClients($request, $id_route_import);        
        return $data;
    }

    public function getDoublesTelClients(Request $request, $id_route_import) {
        $data = Client::findDuplicates($request, $id_route_import, 'Tel');
        return $data;
    }

    public function getDoublesCustomerCodeClients(Request $request, $id_route_import) {
        $data = Client::findDuplicates($request, $id_route_import, 'CustomerCode');        
        return $data;
    }

    public function getDoublesCustomerNameEClients(Request $request, $id_route_import) {
        $data = Client::findDuplicates($request, $id_route_import, 'CustomerNameE');        
        return $data;
    }

    public function getDoublesGPSClients(Request $request, $id_route_import) {
        $data = Client::findDuplicates($request, $id_route_import, 'GPS'); 
        return $data;
    }
}