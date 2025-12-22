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

    public function update(Request $request, int $id)
    {
        try {
            DB::beginTransaction();

            $validator = RouteImport::validateUpdate($request);
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            RouteImport::updateRouteImport($request, $id);

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

    public function show(int $id)
    {
        try {
            $route_import = RouteImport::showRouteImport($id);
            return response()->json($route_import, 200);
        } catch (Throwable $erreur) {
            return response()->json(['errors' => [$erreur->getMessage()]], 422);
        }
    }

    public function indexedDBShow(int $id)
    {
        try {
            $route_import = RouteImport::indexedDBShowRouteImport($id);
            return response()->json($route_import, 200);
        } catch (Throwable $erreur) {
            return response()->json(['errors' => [$erreur->getMessage()]], 422);
        }
    }

    public function delete(int $id)
    {
        try {
            // NOTE: RouteImport::deleteRouteImport() runs its own DB transaction internally.
            RouteImport::deleteRouteImport($id);

            return response()->json([
                "header"  => "Route Deleted !",
                "message" => "a route has been deleted successfuly!"
            ], 200);
        } catch (Throwable $erreur) {
            return response()->json(['errors' => [$erreur->getMessage()]], 422);
        }
    }

    // User Territory (read-only; no transaction)
    public function userTerritory(int $id)
    {
        try {
            $liste_user_territory = RouteImport::userTerritory($id);
            return response()->json($liste_user_territory, 200);
        } catch (Throwable $erreur) {
            return response()->json(['errors' => [$erreur->getMessage()]], 422);
        }
    }

    public function userTerritoryUtil(Request $request, int $id)
    {
        try {
            $liste_user_territory = RouteImport::userTerritoryUtil($request, $id);
            return response()->json($liste_user_territory, 200);
        } catch (Throwable $erreur) {
            return response()->json(['errors' => [$erreur->getMessage()]], 422);
        }
    }

    // Journey Plan endpoints (read-only)
    public function journeyPlan(int $id)
    {
        try {
            $liste_journey_plan = RouteImport::journeyPlan($id);
            return response()->json($liste_journey_plan, 200);
        } catch (Throwable $erreur) {
            return response()->json(['errors' => [$erreur->getMessage()]], 422);
        }
    }

    public function journeyPlanUtil(Request $request, int $id)
    {
        try {
            $liste_journey_plan = RouteImport::journeyPlanUtil($request, $id);
            return response()->json($liste_journey_plan, 200);
        } catch (Throwable $erreur) {
            return response()->json(['errors' => [$erreur->getMessage()]], 422);
        }
    }

    // Journees (read-only)
    public function journees(int $id)
    {
        try {
            $journees = RouteImport::journees($id);
            return response()->json($journees, 200);
        } catch (Throwable $erreur) {
            return response()->json(['errors' => [$erreur->getMessage()]], 422);
        }
    }

    public function journeesUtil(Request $request, int $id)
    {
        try {
            $journees = RouteImport::journeesUtil($request, $id);
            return response()->json($journees, 200);
        } catch (Throwable $erreur) {
            return response()->json(['errors' => [$erreur->getMessage()]], 422);
        }
    }

    public function setWillayasCites(Request $request)
    {
        try {
            $clients = RouteImport::setWillayasCites($request);
            return response()->json($clients, 200);
        } catch (Throwable $erreur) {
            return response()->json(['errors' => [$erreur->getMessage()]], 422);
        }
    }

    public function obsDetailsRouteImport(int $id)
    {
        try {
            $route_import = RouteImport::obsDetailsRouteImport($id);
            return response()->json(['route_import' => $route_import], 200);
        } catch (Throwable $erreur) {
            return response()->json(['errors' => [$erreur->getMessage()]], 422);
        }
    }

    public function obsDetailsRouteImportFrontOffice(int $id)
    {
        try {
            $route_import = RouteImport::obsDetailsRouteImportFrontOffice($id);
            return response()->json(['route_import' => $route_import], 200);
        } catch (Throwable $erreur) {
            return response()->json(['errors' => [$erreur->getMessage()]], 422);
        }
    }

    public function clients(int $id)
    {
        try {
            $clients = RouteImport::clients($id);
            $route_import = RouteImport::find($id);

            return response()->json([
                'clients'      => $clients,
                'route_import' => $route_import
            ], 200);
        } catch (Throwable $erreur) {
            return response()->json(['errors' => [$erreur->getMessage()]], 422);
        }
    }

    public function sync(Request $request)
    {
        try {
            DB::beginTransaction();

            RouteImport::sync($request);

            DB::commit();

            return response()->json([
                "header"  => "Synchronisation Perfomed !",
                "message" => "a synchronisation has been performed successfuly!"
            ], 200);
        } catch (Throwable $erreur) {
            DB::rollBack();
            return response()->json(['errors' => [$erreur->getMessage()]], 422);
        }
    }

    public function headerRouteImports()
    {
        try {
            $liste_route_import = RouteImport::headerRouteImports();
            return response()->json($liste_route_import, 200);
        } catch (Throwable $erreur) {
            return response()->json(['errors' => [$erreur->getMessage()]], 422);
        }
    }

    public function indexRouteImports()
    {
        try {
            $liste_route_import = RouteImport::indexRouteImports();
            return response()->json($liste_route_import, 200);
        } catch (Throwable $erreur) {
            return response()->json(['errors' => [$erreur->getMessage()]], 422);
        }
    }

    public function statsRouteImports()
    {
        try {
            $liste_route_import = RouteImport::statsRouteImports();
            return response()->json($liste_route_import, 200);
        } catch (Throwable $erreur) {
            return response()->json(['errors' => [$erreur->getMessage()]], 422);
        }
    }

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

    public static function clientsByStatus(Request $request, int $id_route_import)
    {
        try {
            $clients = RouteImport::clientsByStatus($request, $id_route_import);
            return response()->json($clients, 200);
        } catch (Throwable $erreur) {
            return response()->json(['errors' => [$erreur->getMessage()]], 422);
        }
    }

    public static function routeImportCities(int $id_route_import, string $DistrictNo)
    {
        try {
            $route_import_cities = RouteImport::routeImportCities($id_route_import, $DistrictNo);
            return response()->json(['route_import_cities' => $route_import_cities], 200);
        } catch (Throwable $erreur) {
            return response()->json(['errors' => [$erreur->getMessage()]], 422);
        }
    }

    // Download and zip helpers (use model-level zipper)
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
            $filePath = RouteImport::downloadImages($request);
            return Response::download($filePath)->deleteFileAfterSend(true);
        } catch (Throwable $erreur) {
            return response()->json(['errors' => [$erreur->getMessage()]], 422);
        }
    }

    public function downloadCustomerCodeImages(Request $request)
    {
        try {
            $filePath = RouteImport::downloadCustomerCodeImages($request);
            return Response::download($filePath)->deleteFileAfterSend(true);
        } catch (Throwable $erreur) {
            return response()->json(['errors' => [$erreur->getMessage()]], 422);
        }
    }

    public function downloadFacadeImages(Request $request)
    {
        try {
            $filePath = RouteImport::downloadFacadeImages($request);
            return Response::download($filePath)->deleteFileAfterSend(true);
        } catch (Throwable $erreur) {
            return response()->json(['errors' => [$erreur->getMessage()]], 422);
        }
    }

    public function downloadInStoreImages(Request $request)
    {
        try {
            $filePath = RouteImport::downloadInStoreImages($request);
            return Response::download($filePath)->deleteFileAfterSend(true);
        } catch (Throwable $erreur) {
            return response()->json(['errors' => [$erreur->getMessage()]], 422);
        }
    }
}
