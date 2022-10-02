<?php

namespace App\Http\Controllers\Radiologi;

use App\Http\Libraries\LibApp;
use App\Models\Radiologi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Routing\Controller as BaseController;

class RadiologiController extends BaseController
{
    function getAllMaster(){
        $mod = new Radiologi();
        return LibApp::response(200, $mod->getAllData(), '');
    }

    function getAllMasterDetail(Request $request){
        $mod = new Radiologi();
        return LibApp::response(200, $mod->getAllDataDetail($request->id_head), '');
    }

    public function DataOrder($tanggal, $status)
    {
        $data = DB::table('emr_test_order')
                ->select('emr_test_order.*', 'emr_status_order.status', 'pasien.nama', 'pasien.norm', 'mst_ruangan.name as nama_ruangan', 'mst_pelaksana.name as dokter')
                ->leftJoin('registrasi', 'registrasi.noreg', '=', 'emr_test_order.noreg')
                ->leftJoin('mst_ruangan', 'mst_ruangan.id', '=', 'registrasi.ruangan')
                ->leftJoin('pasien', 'pasien.id', '=', 'registrasi.id_pasien')
                ->leftJoin('mst_pelaksana', 'mst_pelaksana.id', '=', 'registrasi.dpjp_pelaksana')
                ->leftJoin(DB::raw('(SELECT * FROM emr_test_order_status WHERE active = 1 AND `status` = \''.$status.'\') as emr_status_order'), 'emr_status_order.id_emr_test_order', '=', 'emr_test_order.id')
                ->where(DB::raw('LEFT(emr_test_order.created_at, 10)'), $tanggal)
                ->groupBy('emr_test_order.noreg')
                ->get();

        return LibApp::response(200, $data, '');
    }


}
