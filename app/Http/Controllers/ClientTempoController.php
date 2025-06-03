<?php

namespace App\Http\Controllers;

use App\Models\ClientTempo;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class ClientTempoController extends Controller
{

    //

    public function clients(int $id_route_import_tempo) {

        try {

            $clients     =   ClientTempo::index($id_route_import_tempo);
            return $clients;
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

    public function storeClients(Request $request, int $id_route_import_tempo) {

        try {

            //
            DB::beginTransaction();
            //

            // store 
            ClientTempo::storeClients($request, $id_route_import_tempo);

            //
            DB::commit();
            //

            return response()->json([
                "header"            =>  "Clients Added !"                             ,
                "message"           =>  "the clients have been added successfuly!"   
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

    public function updateClient(Request $request, int $id_route_import_tempo, int $id) {

        try {

            //
            DB::beginTransaction();
            //

            // validate
            $validator  =   ClientTempo::validateUpdate($request);
            
            if ($validator->fails()) {
                return response()->json([
                    'errors'    =>  $validator->errors(),
                ],422);
            }

            // store 
            $client     =   ClientTempo::updateClient($request, $id_route_import_tempo, $id);

            //
            DB::commit();
            //

            return response()->json([
                "client"            =>  $client                                         ,
                "header"            =>  "Client Updated !"                              ,
                "message"           =>  "the client has been updated successfuly!"   
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

    public function deleteClient(int $id_route_import_tempo, int $id) {

        try {

            //
            DB::beginTransaction();
            //

            // store 
            ClientTempo::deleteClient($id_route_import_tempo, $id);

            //
            DB::commit();
            //

            return response()->json([
                "header"            =>  "Client Deleted !"                          ,
                "message"           =>  "the client has been deleted successfuly!"   
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

    public function updateClients(Request $request, int $id_route_import_tempo) {

        try {

            //
            DB::beginTransaction();
            //

            // store 
            ClientTempo::updateClients($request, $id_route_import_tempo);

            //
            DB::commit();
            //

            return response()->json([
                "header"            =>  "Clients Updated !"                             ,
                "message"           =>  "the clients have been updated successfuly!"   
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

    public function updateResumeClients(Request $request) {

        try {

            //
            DB::beginTransaction();
            //

            // store 
            ClientTempo::updateResumeClients($request);

            //
            DB::commit();
            //

            return response()->json([
                "header"            =>  "Clients Updated !"                             ,
                "message"           =>  "the clients have been updated successfuly!"   
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

    public function getDoublesClients(int $id_route_import_tempo) {

        try {

            $getDoublant    =   ClientTempo::getDoublesClients($id_route_import_tempo);

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

    public function getDoublesTelClients(int $id_route_import_tempo) {

        try {

            $getDoublant    =   ClientTempo::getDoublesTelClients($id_route_import_tempo);

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

    public function getDoublesCustomerCodeClients(int $id_route_import_tempo) {

        try {

            $getDoublant    =   ClientTempo::getDoublesCustomerCodeClients($id_route_import_tempo);

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

    public function getDoublesCustomerNameEClients(int $id_route_import_tempo) {

        try {

            $getDoublant    =   ClientTempo::getDoublesCustomerNameEClients($id_route_import_tempo);

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

    public function getDoublesGPSClients(int $id_route_import_tempo) {

        try {

            $getDoublant    =   ClientTempo::getDoublesGPSClients($id_route_import_tempo);

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

    //
}
