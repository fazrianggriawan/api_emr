<?php

namespace App\Http\Controllers\Farmasi;

use App\Http\Libraries\LibApp;
use App\Models\Farmasi_depo;
use App\Models\Farmasi_supplier;
use Laravel\Lumen\Routing\Controller as BaseController;

class MasterController extends BaseController
{
    public function Depo()
    {
        $data = Farmasi_depo::get();
        return LibApp::response(200, $data);
    }

    public function Supplier()
    {
        $data = Farmasi_supplier::get();
        return LibApp::response(200, $data);
    }

}
