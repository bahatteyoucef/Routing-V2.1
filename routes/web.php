<?php

use App\Http\Controllers\WebController;
use App\Models\Client;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

//
Route::get('/clients/facade_images/update'      ,   function() {

    try {

        $clients    =   Client::all();

        foreach ($clients as $client) {

            // Check if the old file exists
            if($client->CustomerBarCode_image   !=  "") {

                if (file_exists("uploads/clients/".$client->id."/".$client->CustomerBarCode_image)) {
                    
                    // Get Extension
                    $file_path                  =   "/uploads/clients/".$client->id."/".$client->CustomerBarCode_image;

                    // Get the filename with extension
                    $filename_with_extension    =   basename($file_path);

                    // Extract extension using pathinfo()
                    $extension                  =   pathinfo($filename_with_extension, PATHINFO_EXTENSION);

                    // Attempt to rename the file
                    if (rename("uploads/clients/".$client->id."/".$client->CustomerBarCode_image, "uploads/clients/".$client->id."/".$client->id."_".$client->DistrictNo."_".$client->CityNo."_"."_CUSTOMERCODE_".$client->CustomerCode.".".$extension)) {

                        $client->CustomerBarCode_image  =   $client->id."_".$client->DistrictNo."_".$client->CityNo."_"."_CUSTOMERCODE_".$client->CustomerCode.".".$extension;
                    } 
                }
            }

            // Check if the old file exists
            if($client->facade_image    !=  "") {

                if (file_exists("uploads/clients/".$client->id."/".$client->facade_image)) {
                    
                    // Get Extension
                    $file_path                  =   "/uploads/clients/".$client->id."/".$client->facade_image;

                    // Get the filename with extension
                    $filename_with_extension    =   basename($file_path);

                    // Extract extension using pathinfo()
                    $extension                  =   pathinfo($filename_with_extension, PATHINFO_EXTENSION);

                    // Attempt to rename the file Facade
                    if (rename("uploads/clients/".$client->id."/".$client->facade_image         , "uploads/clients/".$client->id."/".$client->id."_".$client->DistrictNo."_".$client->CityNo."_"."_FACADE_".$client->CustomerCode.".".$extension)) {

                        $client->facade_image       =   $client->id."_".$client->DistrictNo."_".$client->CityNo."_"."_FACADE_".$client->CustomerCode.".".$extension;
                    } 
                }
            }

            // Check if the old file exists
            if($client->in_store_image  !=  "") {

                if (file_exists("uploads/clients/".$client->id."/".$client->in_store_image)) {

                    // Get Extension
                    $file_path                  =   "/uploads/clients/".$client->id."/".$client->in_store_image;

                    // Get the filename with extension
                    $filename_with_extension    =   basename($file_path);

                    // Extract extension using pathinfo()
                    $extension                  =   pathinfo($filename_with_extension, PATHINFO_EXTENSION);

                    // Attempt to rename the file
                    if (rename("uploads/clients/".$client->id."/".$client->in_store_image       , "uploads/clients/".$client->id."/".$client->id."_".$client->DistrictNo."_".$client->CityNo."_"."_IN_STORE_".$client->CustomerCode.".".$extension)) {

                        $client->in_store_image     =   $client->id."_".$client->DistrictNo."_".$client->CityNo."_"."_IN_STORE_".$client->CustomerCode.".".$extension;
                    } 
                }
            }

            $client->save();
        }
    }

    catch(Throwable $erreur) {

        return response()->json([
            'errors'    =>  [$erreur->getMessage()],
        ],422);
    }
});

// If Authentificated
Route::get('/'      , [WebController::class, "indexPage"])->name('index');
// 

// If Guest
Route::get('/login' , [WebController::class, "loginPage"])->name('login');
// 

// If Randome Link (Case Auth)
Route::any('{slug}', function () {

    return view('welcome');
    
})->where('slug', '.*');
