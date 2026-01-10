<?php

namespace App\Http\Controllers;

use App\Models\ClientTempo;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class ClientTempoController extends Controller {

    public function clients(int $id_route_import_tempo) {

        try {

            $clients     =   ClientTempo::index($id_route_import_tempo);
            return response()->json([
                'clients'    =>  $clients,
            ]);
        }

        catch(Throwable $erreur) {

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

    public function updateClient(Request $request, int $id_route_import_tempo, int $id_client_tempo) {

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
            $client     =   ClientTempo::updateClient($request, $id_route_import_tempo, $id_client_tempo);

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

    public function deleteClient(int $id_route_import_tempo, int $id_client_tempo) {

        try {

            //
            DB::beginTransaction();
            //

            // store 
            ClientTempo::deleteClient($id_route_import_tempo, $id_client_tempo);

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

    //  //  //  //  //
    //  //  //  //  //
    //  //  //  //  //

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

    //  //  //  //  //
    //  //  //  //  //
    //  //  //  //  //

    public function getDoublesClients(Request $request, $id_route_import_tempo) {
        $doubles = ClientTempo::getDoublesClients($request, $id_route_import_tempo);        
        return response()->json([   
            "doubles"   =>  $doubles
        ]);
    }

    public function getDoublesTelClients(Request $request, $id_route_import_tempo) {
        $doubles_tel = ClientTempo::findDuplicates($request, $id_route_import_tempo, 'Tel');
        return response()->json([   
            "doubles_tel"   =>  $doubles_tel
        ]);
    }

    public function getDoublesCustomerCodeClients(Request $request, $id_route_import_tempo) {
        $doubles_customer_code = ClientTempo::findDuplicates($request, $id_route_import_tempo, 'CustomerCode');        
        return response()->json([   
            "doubles_customer_code"   =>  $doubles_customer_code
        ]);
    }

    public function getDoublesCustomerNameEClients(Request $request, $id_route_import_tempo) {
        $doubles_customer_namee = ClientTempo::findDuplicates($request, $id_route_import_tempo, 'CustomerNameE');        
        return response()->json([   
            "doubles_customer_namee"   =>  $doubles_customer_namee
        ]);
    }

    public function getDoublesGPSClients(Request $request, $id_route_import_tempo) {
        $doubles_gps = ClientTempo::findDuplicates($request, $id_route_import_tempo, 'GPS'); 
        return response()->json([   
            "doubles_gps"   =>  $doubles_gps
        ]);
    }
}