<?php

namespace App\Http\Controllers\Farmasi;

use App\Http\Libraries\LibApp;
use App\Models\Simrs_lama\Apotik_salak_obatx;
use Laravel\Lumen\Routing\Controller as BaseController;

class ObatController extends BaseController
{

    public function DataObat()
    {
        $data = Apotik_salak_obatx::where('sr_deleted', '')->get();
        return LibApp::response(200, $data);
    }

    public function CariObat($key)
    {
        $data = Apotik_salak_obatx::select(['kode as id', 'nama','hrg_jual1 as harga','pak2 as satuan','sr_recno as id_tarif_harga'])
                ->where('nama', 'like', strtoupper($key).'%')
                ->where('sr_deleted', '')
                ->get();
        return LibApp::response(200, $data);
    }
}
