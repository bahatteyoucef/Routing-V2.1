<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class RouteImportTempo extends Model
{
    use HasFactory;

    protected $guarded      = [];
    protected $table        = 'route_import_tempo';
    protected $primaryKey   = 'id';
    public    $timestamps   = false;

    //  //  //  //  //
    //  //  //  //  //  Last Route Import Imported by Auth user
    //  //  //  //  //

    public static function lastTempo() {

        $user = Auth::user();
        if (! $user) {
            throw new Exception('Unauthorized', 403);
        }

        $last = self::where('owner', $user->id)->latest('id')->first();

        if (! $last) {
            throw new Exception('Unauthorized !', 403);
        }

        // Eagerly load clients and districts (ClientTempo::index returns formatted collection)
        $last->clients = ClientTempo::index($last->id);

        $last->districts = RouteImportTempoDistrict::select('RTM_Willaya.DistrictNo', 'RTM_Willaya.DistrictNameE')
            ->join('RTM_Willaya', 'route_import_tempo_districts.DistrictNo', '=', 'RTM_Willaya.DistrictNo')
            ->where('route_import_tempo_districts.id_route_import_tempo', $last->id)
            ->orderBy('RTM_Willaya.DistrictNameE')
            ->get();

        return $last;
    }

    //  //  //  //  //
    //  //  //  //  //  Store Route Import Tempo
    //  //  //  //  //

    public static function validateStore(Request $request) {
        return Validator::make($request->all(), [
            'libelle'   => ['required', 'max:255'],
            'data'      => ['required', 'json'],
            'districts' => ['required'], // accept JSON/array, validated further below
            // 'file'      => ['required', 'file', 'mimetypes:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/octet-stream'],
        ]);
    }

    public static function storeRouteImportTempo(Request $request) {
        $user = Auth::user();
        if (! $user) {
            throw new Exception('Unauthorized', 403);
        }

        // If BU Manager limit applies
        if ($user->hasRole('BU Manager')) {
            $count = RouteImport::where('owner', $user->id)->count();
            if ($count >= (int)$user->max_route_import) {
                throw new Exception('You have achieved the maximum number of route imports !');
            }
        }

        $validator = self::validateStore($request);
        if ($validator->fails()) {
            throw new Exception($validator->errors()->first());
        }

        // Normalize districts input (can be JSON string or array)
        $districtsInput = $request->input('districts');
        if (is_string($districtsInput)) {
            $districtsDecoded = json_decode($districtsInput, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('Invalid districts JSON.');
            }
            $districts = $districtsDecoded;
        } else {
            $districts = (array)$districtsInput;
        }

        if (empty($districts) || !is_array($districts)) {
            throw new Exception('districts is required and must be an array or JSON array.');
        }

        // Transaction: create tempo, file move, district inserts, and store client data.
        $routeImportTempo = null;

        // Create tempo header
        $routeImportTempo = self::create([
            'libelle' => $request->get('libelle'),
            'owner' => $user->id,
        ]);

        // Bulk insert districts (prepare rows)
        $districtRows = [];
        foreach ($districts as $d) {
            // accept object or array
            $districtNo = is_array($d) ? ($d['DistrictNo'] ?? $d['DistrictNo'] ?? null) : ($d->DistrictNo ?? $d->DistrictNo ?? null);
            // if the payload is just the DistrictNo value (not object), handle it
            if ($districtNo === null) {
                $districtNo = is_scalar($d) ? $d : null;
            }
            if ($districtNo === null) continue;

            $districtRows[] = [
                'DistrictNo'           => $districtNo,
                'id_route_import_tempo'=> $routeImportTempo->id,
                'owner'                => $user->id,
            ];
        }

        if (!empty($districtRows)) {
            // Use chunk insert to avoid very large single inserts
            $chunks = array_chunk($districtRows, 500);
            foreach ($chunks as $chunk) {
                RouteImportTempoDistrict::insert($chunk);
            }
        }

        // Save uploaded file (ensure destination dir)
        // $uploaded = $request->file('file');
        // $userDir = public_path('uploads/route_import_tempo/' . $user->id);
        // if (! File::isDirectory($userDir)) {
        //     File::makeDirectory($userDir, 0755, true);
        // }

        // $fileName = uniqid() . '.' . $uploaded->getClientOriginalExtension();
        // $uploaded->move($userDir, $fileName);

        // $routeImportTempo->file                 = $fileName;
        // $routeImportTempo->file_original_name   = $uploaded->getClientOriginalName();
        $routeImportTempo->save();

        // Ensure clients payload (data) passed properly to ClientTempo::storeClients
        // ClientTempo::storeClients expects request->get('data') to be JSON text or array depending on its implementation.
        // If the controller already provided 'data' as JSON string, keep it. Otherwise, if there's 'data' field as array/object, pass it through.
        // We don't modify request param here; we call storeData which delegates to ClientTempo::storeClients
        self::storeData($request, $routeImportTempo->id);
        
        return $routeImportTempo;
    }

    public static function deleteRouteImportTempo() {
        $user = Auth::user();
        if (! $user) return;

        // Delete DB records
        self::where('owner', $user->id)->delete();
        ClientTempo::where('owner', $user->id)->delete();
        RouteImportTempoDistrict::where('owner', $user->id)->delete();

        // Delete tempo directory for the user (if exists)
        // $userDir = public_path('uploads/route_import_tempo/' . $user->id);
        // if (File::exists($userDir)) {
            // Recursively delete directory
            // File::deleteDirectory($userDir);
        // }
    }

    //  //  //  //  //
    //  //  //  //  //  Store Route Import Tempo Clients
    //  //  //  //  //

    public static function storeData(Request $request, int $id_route_import_tempo) {
        // Delegate to ClientTempo (ClientTempo::storeClients handles data normalization)
        ClientTempo::storeClients($request, $id_route_import_tempo);
    }
}