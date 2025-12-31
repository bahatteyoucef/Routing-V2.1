<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\RouteImport;
use App\Models\RouteImportCity;
use App\Models\RTMWillaya;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;
use Illuminate\Support\Facades\Response;

class RouteImportController extends Controller
{
    public function index()
    {
        try {
            $liste_route_import = RouteImport::indexRouteImport();
            return response()->json($liste_route_import, 200);
        } catch (Throwable $erreur) {
            return response()->json(['errors' => [$erreur->getMessage()]], 422);
        }
    }

    public function combo()
    {
        try {
            $liste_route_import = RouteImport::comboRouteImport();
            return response()->json($liste_route_import, 200);
        } catch (Throwable $erreur) {
            return response()->json(['errors' => [$erreur->getMessage()]], 422);
        }
    }

    public function store(Request $request)
    {
        try {
            // Validation
            $validator = RouteImport::validateStore($request);
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            // NOTE: RouteImport::storeRouteImport() performs its own transaction internally.
            $route_import = RouteImport::storeRouteImport($request);

            return response()->json([
                "header"       => "Route Added !",
                "message"      => "a new route has been added successfuly!",
                "route_import" => $route_import
            ], 201);
        } catch (Throwable $erreur) {
            // If the model already handled transaction rollback, this just returns error.
            return response()->json(['errors' => [$erreur->getMessage()]], 422);
        }
    }

    public function update(Request $request, int $id_route_import)
    {
        try {
            DB::beginTransaction();

            $validator = RouteImport::validateUpdate($request);
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            RouteImport::updateRouteImport($request, $id_route_import);

            DB::commit();

            return response()->json([
                "header"  => "Route Updated !",
                "message" => "a route has been updated successfuly!"
            ], 200);
        } catch (Throwable $erreur) {
            DB::rollBack();
            return response()->json(['errors' => [$erreur->getMessage()]], 422);
        }
    }

    public function show(int $id_route_import)
    {
        try {
            $route_import = RouteImport::showRouteImport($id_route_import);
            return response()->json($route_import, 200);
        } catch (Throwable $erreur) {
            return response()->json(['errors' => [$erreur->getMessage()]], 422);
        }
    }

    public function details(int $id_route_import)
    {
        try {
            $route_import = RouteImport::detailsRouteImport($id_route_import);
            return response()->json($route_import, 200);
        } catch (Throwable $erreur) {
            return response()->json(['errors' => [$erreur->getMessage()]], 422);
        }
    }

    public function delete(int $id_route_import)
    {
        try {
            // NOTE: RouteImport::deleteRouteImport() runs its own DB transaction internally.
            RouteImport::deleteRouteImport($id_route_import);

            return response()->json([
                "header"  => "Route Deleted !",
                "message" => "a route has been deleted successfuly!"
            ], 200);
        } catch (Throwable $erreur) {
            return response()->json(['errors' => [$erreur->getMessage()]], 422);
        }
    }

    //  //  //  //  //
    //  //  //  //  //
    //  //  //  //  //

    public function userTerritory(int $id_route_import)
    {
        try {
            $liste_user_territory = RouteImport::userTerritory($id_route_import);
            return response()->json($liste_user_territory, 200);
        } catch (Throwable $erreur) {
            return response()->json(['errors' => [$erreur->getMessage()]], 422);
        }
    }

    public function userTerritoryUtil(Request $request, int $id_route_import)
    {
        try {
            $liste_user_territory = RouteImport::userTerritoryUtil($request, $id_route_import);
            return response()->json($liste_user_territory, 200);
        } catch (Throwable $erreur) {
            return response()->json(['errors' => [$erreur->getMessage()]], 422);
        }
    }

    public function journeyPlan(int $id_route_import)
    {
        try {
            $liste_journey_plan = RouteImport::journeyPlan($id_route_import);
            return response()->json($liste_journey_plan, 200);
        } catch (Throwable $erreur) {
            return response()->json(['errors' => [$erreur->getMessage()]], 422);
        }
    }

    public function journeyPlanUtil(Request $request, int $id_route_import)
    {
        try {
            $liste_journey_plan = RouteImport::journeyPlanUtil($request, $id_route_import);
            return response()->json($liste_journey_plan, 200);
        } catch (Throwable $erreur) {
            return response()->json(['errors' => [$erreur->getMessage()]], 422);
        }
    }

    public function journees(int $id_route_import)
    {
        try {
            $journees = RouteImport::journees($id_route_import);
            return response()->json($journees, 200);
        } catch (Throwable $erreur) {
            return response()->json(['errors' => [$erreur->getMessage()]], 422);
        }
    }

    public function journeesUtil(Request $request, int $id_route_import)
    {
        try {
            $journees = RouteImport::journeesUtil($request, $id_route_import);
            return response()->json($journees, 200);
        } catch (Throwable $erreur) {
            return response()->json(['errors' => [$erreur->getMessage()]], 422);
        }
    }

    //  //  //  //  //
    //  //  //  //  //
    //  //  //  //  //

    public function obsDetailsRouteImport(int $id_route_import)
    {
        try {
            $route_import = RouteImport::obsDetailsRouteImport($id_route_import);
            return response()->json(['route_import' => $route_import], 200);
        } catch (Throwable $erreur) {
            return response()->json(['errors' => [$erreur->getMessage()]], 422);
        }
    }

    public function obsDetailsRouteImportFrontOffice(int $id_route_import)
    {
        try {
            $route_import = RouteImport::obsDetailsRouteImportFrontOffice($id_route_import);
            return response()->json(['route_import' => $route_import], 200);
        } catch (Throwable $erreur) {
            return response()->json(['errors' => [$erreur->getMessage()]], 422);
        }
    }

    //  //  //  //  //
    //  //  //  //  //
    //  //  //  //  //

    public static function frontOffice(int $id_route_import)
    {
        try {
            $users = RouteImport::frontOffice($id_route_import);
            return response()->json($users, 200);
        } catch (Throwable $erreur) {
            return response()->json(['errors' => [$erreur->getMessage()]], 422);
        }
    }

    public static function users(int $id_route_import)
    {
        try {
            $users = RouteImport::users($id_route_import);
            return response()->json($users, 200);
        } catch (Throwable $erreur) {
            return response()->json(['errors' => [$erreur->getMessage()]], 422);
        }
    }

    //  //  //  //  //
    //  //  //  //  //
    //  //  //  //  //

    public function clients(int $id_route_import)
    {
        try {
            $clients = RouteImport::clients($id_route_import);
            $route_import = RouteImport::find($id_route_import);

            return response()->json([
                'clients'      => $clients,
                'route_import' => $route_import
            ], 200);
        } catch (Throwable $erreur) {
            return response()->json(['errors' => [$erreur->getMessage()]], 422);
        }
    }

    public static function clientsByStatus(Request $request, int $id_route_import)
    {
        try {
            $clients = RouteImport::clientsByStatus($request, $id_route_import);
            return response()->json($clients, 200);
        } catch (Throwable $erreur) {
            return response()->json(['errors' => [$erreur->getMessage()]], 422);
        }
    }

    //  //  //  //  //
    //  //  //  //  //
    //  //  //  //  //

    public static function routeImportCities(int $id_route_import)
    {
        try {
            $cities = RouteImport::routeImportCities($id_route_import);
            return $cities;
        } catch (Throwable $erreur) {
            return response()->json(['errors' => [$erreur->getMessage()]], 422);
        }
    }

    public static function routeImportDistricts(int $id_route_import)
    {
        try {
            $districts = RouteImport::routeImportDistricts($id_route_import);
            return $districts;
        } catch (Throwable $erreur) {
            return response()->json(['errors' => [$erreur->getMessage()]], 422);
        }
    }

    //  //  //  //  //
    //  //  //  //  //
    //  //  //  //  //

    public function downloadData(Request $request)
    {
        try {
            $clients = RouteImport::downloadData($request);
            return response()->json(['clients' => $clients], 200);
        } catch (Throwable $erreur) {
            return response()->json(['errors' => [$erreur->getMessage()]], 422);
        }
    }

    public function downloadImages(Request $request)
    {
        try {
            $filePath = RouteImport::downloadImages($request, "all");
            return Response::download($filePath)->deleteFileAfterSend(true);
        } catch (Throwable $erreur) {
            return response()->json(['errors' => [$erreur->getMessage()]], 422);
        }
    }

    public function downloadCustomerCodeImages(Request $request)
    {
        try {
            $filePath = RouteImport::downloadImages($request, "customer_code");
            return Response::download($filePath)->deleteFileAfterSend(true);
        } catch (Throwable $erreur) {
            return response()->json(['errors' => [$erreur->getMessage()]], 422);
        }
    }

    public function downloadFacadeImages(Request $request)
    {
        try {
            $filePath = RouteImport::downloadImages($request, "facade");
            return Response::download($filePath)->deleteFileAfterSend(true);
        } catch (Throwable $erreur) {
            return response()->json(['errors' => [$erreur->getMessage()]], 422);
        }
    }

    public function downloadInStoreImages(Request $request)
    {
        try {
            $filePath = RouteImport::downloadImages($request, "in_store");
            return Response::download($filePath)->deleteFileAfterSend(true);
        } catch (Throwable $erreur) {
            return response()->json(['errors' => [$erreur->getMessage()]], 422);
        }
    }
}