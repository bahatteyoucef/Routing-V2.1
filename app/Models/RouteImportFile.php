<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class RouteImportFile extends Model
{
    use HasFactory; 

    protected $guarded      =   [];

    protected $table        =   'route_import_files';
    protected $primaryKey   =   'id';

    public    $timestamps   =   false;

    //

    public static function deleteRouteImportFile($fileName, $directory = "uploads/route_import")
    {

        if (File::exists($directory)) {

            $fullPath = public_path($directory . '/' . $fileName); // Build full path

            if (File::exists($fullPath)) {

                File::delete($fullPath);

                return true;
            }

            $subdirectories = File::directories($directory);

            foreach ($subdirectories as $dir) {

                RouteImportFile::deleteRouteImportFile($fileName, $dir); // Recursive call with subdirectory path
            }
        }

        return false;
    }

}
