<?php

namespace App\Http\Controllers\Farmasi;

use App\Http\Libraries\LibApp;
use App\Models\Farmasi_opname_nama_obat;
use App\Models\Farmasi_opname_periode;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Routing\Controller as BaseController;

class OpnameController extends BaseController
{

    public function DataPeriode()
    {
        $data = Farmasi_opname_periode::with('r_depo')->get();
        return LibApp::response(200, $data);
    }

    public function DataStokObat($idPeriode)
    {
        return $data = Farmasi_opname_nama_obat::with(['r_nama_obat','r_stok_obat'=>function($q){
            return $q->select(['*',DB::raw('SUM(jumlah_stok) as total')])->groupBy('id_farmasi_opname_nama_obat');
        }])->where('id_farmasi_opname_periode', $idPeriode)->get();
        return LibApp::response(200, $data);
    }

}
