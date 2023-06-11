<?php

namespace App\Imports;

use App\Models\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;

use App\Models\Route;
use Illuminate\Support\Facades\Auth;

class ClientsImport implements ToModel
{
    private $id_upload_client;

    public function __construct(int $id_upload_client) 
    {
        $this->id_upload_client = $id_upload_client;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
   
    }

}
