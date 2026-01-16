<?php

namespace App\Http\Controllers;

use App\Models\RouteImport;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;
use Illuminate\Support\Facades\Response;

class RouteImportController extends Controller {

    public function index() {
        try {
            $liste_route_import = RouteImport::indexRouteImport();
            return response()->json([
                'liste_route_import'    =>  $liste_route_import,
            ]);
        } catch (Throwable $erreur) {
            return response()->json(['errors' => [$erreur->getMessage()]], 422);
        }
    }

    public function combo() {
        try {
            $liste_route_import = RouteImport::comboRouteImport();
            return response()->json([
                'liste_route_import'    =>  $liste_route_import,
            ]);
        } catch (Throwable $erreur) {
            return response()->json(['errors' => [$erreur->getMessage()]], 422);
        }
    }

    public function store(Request $request) {
        try {

            //
            DB::beginTransaction();
            //

            // Validation
            $validator = RouteImport::validateStore($request);
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            // NOTE: RouteImport::storeRouteImport() performs its own transaction internally.
            $route_import = RouteImport::storeRouteImport($request);

            //
            DB::commit();
            //

            return response()->json([
                "header"       => "Route Added !",
                "message"      => "a new route has been added successfuly!",
                "route_import" => $route_import
            ], 201);
        } catch (Throwable $erreur) {

            //
            DB::rollBack();
            //

            // If the model already handled transaction rollback, this just returns error.
            return response()->json(['errors' => [$erreur->getMessage()]], 422);
        }
    }

    public function update(Request $request, int $id_route_import) {
        try {

            //
            DB::beginTransaction();
            //

            $validator = RouteImport::validateUpdate($request);
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            RouteImport::updateRouteImport($request, $id_route_import);

            DB::commit();

            return response()->json([
                "header"  => "Route Updated !",
                "message" => "a route has been updated successfuly!"
            ]);
        } catch (Throwable $erreur) {
            DB::rollBack();
            return response()->json(['errors' => [$erreur->getMessage()]], 422);
        }
    }

    public function show(int $id_route_import) {
        try {
            $route_import = RouteImport::showRouteImport($id_route_import);
            return response()->json([
                "route_import"      =>  $route_import
            ]);
        } catch (Throwable $erreur) {
            return response()->json(['errors' => [$erreur->getMessage()]], 422);
        }
    }

    public function details(int $id_route_import) {
        try {
            $route_import = RouteImport::detailsRouteImport($id_route_import);
            return response()->json([
                "route_import"      =>  $route_import
            ]);
        } catch (Throwable $erreur) {
            return response()->json(['errors' => [$erreur->getMessage()]], 422);
        }
    }

    public function delete(int $id_route_import) {
        try {

            //
            DB::beginTransaction();
            //

            // NOTE: RouteImport::deleteRouteImport() runs its own DB transaction internally.
            RouteImport::deleteRouteImport($id_route_import);

            //
            DB::commit();
            //

            return response()->json([
                "header"  => "Route Deleted !",
                "message" => "a route has been deleted successfuly!"
            ]);
        } catch (Throwable $erreur) {
            //
            DB::rollBack();
            //

            return response()->json(['errors' => [$erreur->getMessage()]], 422);
        }
    }

    //  //  //  //  //
    //  //  //  //  //
    //  //  //  //  //

    public function userTerritories(int $id_route_import) {
        try {
            $user_territories = RouteImport::userTerritories($id_route_import);
            return response()->json([
                "user_territories"      =>  $user_territories
            ]);
        } catch (Throwable $erreur) {
            return response()->json(['errors' => [$erreur->getMessage()]], 422);
        }
    }

    public function userTerritoriesUtil(Request $request, int $id_route_import) {
        try {
            $user_territories = RouteImport::userTerritoriesUtil($request, $id_route_import);
            return response()->json([
                "user_territories"      =>  $user_territories
            ]);
        } catch (Throwable $erreur) {
            return response()->json(['errors' => [$erreur->getMessage()]], 422);
        }
    }

    public function journeyPlanTerritories(int $id_route_import) {
        try {
            $journey_plan_territories = RouteImport::journeyPlanTerritories($id_route_import);
            return response()->json([
                "journey_plan_territories"      =>  $journey_plan_territories
            ]);
        } catch (Throwable $erreur) {
            return response()->json(['errors' => [$erreur->getMessage()]], 422);
        }
    }

    public function journeyPlanTerritoriesUtil(Request $request, int $id_route_import) {
        try {
            $journey_plan_territories = RouteImport::journeyPlanTerritoriesUtil($request, $id_route_import);
            return response()->json([
                "journey_plan_territories"      =>  $journey_plan_territories
            ]);
        } catch (Throwable $erreur) {
            return response()->json(['errors' => [$erreur->getMessage()]], 422);
        }
    }

    public function journeesTerritories(int $id_route_import) {
        try {
            $journee_territories = RouteImport::journeesTerritories($id_route_import);
            return response()->json([
                "journee_territories"      =>  $journee_territories
            ]);
        } catch (Throwable $erreur) {
            return response()->json(['errors' => [$erreur->getMessage()]], 422);
        }
    }

    public function journeesTerritoriesUtil(Request $request, int $id_route_import) {
        try {
            $journee_territories = RouteImport::journeesTerritoriesUtil($request, $id_route_import);
            return response()->json([
                "journee_territories"      =>  $journee_territories
            ]);
        } catch (Throwable $erreur) {
            return response()->json(['errors' => [$erreur->getMessage()]], 422);
        }
    }

    //  //  //  //  //
    //  //  //  //  //
    //  //  //  //  //

    public function obsDetailsRouteImport(int $id_route_import) {
        try {
            $route_import = RouteImport::obsDetailsRouteImport($id_route_import);
            return response()->json(['route_import' => $route_import]);
        } catch (Throwable $erreur) {
            return response()->json(['errors' => [$erreur->getMessage()]], 422);
        }
    }

    public function obsDetailsRouteImportFrontOffice(int $id_route_import) {
        try {
            $route_import = RouteImport::obsDetailsRouteImportFrontOffice($id_route_import);
            return response()->json(['route_import' => $route_import]);
        } catch (Throwable $erreur) {
            return response()->json(['errors' => [$erreur->getMessage()]], 422);
        }
    }

    //  //  //  //  //
    //  //  //  //  //
    //  //  //  //  //

    public function frontOffice(int $id_route_import) {
        try {
            $users = RouteImport::frontOffice($id_route_import);
            return response()->json([
                'users'    =>  $users,
            ]);
        } catch (Throwable $erreur) {
            return response()->json(['errors' => [$erreur->getMessage()]], 422);
        }
    }

    public function users(int $id_route_import) {
        try {
            $users = RouteImport::users($id_route_import);
            return response()->json([
                'users'    =>  $users,
            ]);
        } catch (Throwable $erreur) {
            return response()->json(['errors' => [$erreur->getMessage()]], 422);
        }
    }

    //  //  //  //  //
    //  //  //  //  //
    //  //  //  //  //

    public function clients(int $id_route_import) {
        try {
            $clients = RouteImport::clients($id_route_import);
            $route_import = RouteImport::find($id_route_import);

            return response()->json([
                'clients'      => $clients,
                'route_import' => $route_import
            ]);
        } catch (Throwable $erreur) {
            return response()->json(['errors' => [$erreur->getMessage()]], 422);
        }
    }

    public function clientsByStatus(Request $request, int $id_route_import) {
        try {
            $clients = RouteImport::clientsByStatus($request, $id_route_import);
            return response()->json([
                "clients"   =>  $clients
            ]);
        } catch (Throwable $erreur) {
            return response()->json(['errors' => [$erreur->getMessage()]], 422);
        }
    }

    //  //  //  //  //
    //  //  //  //  //
    //  //  //  //  //

    public function routeImportCities(int $id_route_import) {
        try {
            $cities = RouteImport::routeImportCities($id_route_import);
            return response()->json([
                "cities"   =>  $cities
            ]);
        } catch (Throwable $erreur) {
            return response()->json(['errors' => [$erreur->getMessage()]], 422);
        }
    }

    public function routeImportDistricts(int $id_route_import) {
        try {
            $willayas = RouteImport::routeImportDistricts($id_route_import);
            return response()->json([
                "willayas"   =>  $willayas
            ]);
        } catch (Throwable $erreur) {
            return response()->json(['errors' => [$erreur->getMessage()]], 422);
        }
    }

    //  //  //  //  //
    //  //  //  //  //
    //  //  //  //  //

    public function downloadData(Request $request) {
        try {
            $clients = RouteImport::downloadData($request);
            return response()->json(['clients' => $clients]);
        } catch (Throwable $erreur) {
            return response()->json(['errors' => [$erreur->getMessage()]], 422);
        }
    }

    public function downloadImages(Request $request) {
        try {
            $filePath = RouteImport::downloadImages($request, "all");
            return Response::download($filePath)->deleteFileAfterSend(true);
        } catch (Throwable $erreur) {
            return response()->json(['errors' => [$erreur->getMessage()]], 422);
        }
    }

    public function downloadCustomerCodeImages(Request $request) {
        try {
            $filePath = RouteImport::downloadImages($request, "customer_code");
            return Response::download($filePath)->deleteFileAfterSend(true);
        } catch (Throwable $erreur) {
            return response()->json(['errors' => [$erreur->getMessage()]], 422);
        }
    }

    public function downloadFacadeImages(Request $request) {
        try {
            $filePath = RouteImport::downloadImages($request, "facade");
            return Response::download($filePath)->deleteFileAfterSend(true);
        } catch (Throwable $erreur) {
            return response()->json(['errors' => [$erreur->getMessage()]], 422);
        }
    }

    public function downloadInStoreImages(Request $request) {
        try {
            $filePath = RouteImport::downloadImages($request, "in_store");
            return Response::download($filePath)->deleteFileAfterSend(true);
        } catch (Throwable $erreur) {
            return response()->json(['errors' => [$erreur->getMessage()]], 422);
        }
    }
}