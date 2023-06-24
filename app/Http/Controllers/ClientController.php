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

            // store 
            Client::storeClient($request, $id_route_import);

            //
            DB::commit();
            //

            return response()->json([
                "header"            =>  "Client Ajouté !"                   ,
                "message"           =>  "une nouveau client a été ajouté !"
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

            // update 
            Client::updateClient($request, $id_route_import, $id);
            
            //
            DB::commit();
            //

            return response()->json([
                "header"            =>  "Client Modifiée !"                   ,
                "message"           =>  "une client a été modifié !"
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
                "header"            =>  "Client Supprimé !"                   ,
                "message"           =>  "une client a été supprimé !"
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
                "header"            =>  "Client Supprimé !"                   ,
                "message"           =>  "une client a été supprimé !"
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
