<?php

namespace App\Http\Controllers\Registrasi;

use App\Http\Libraries\LibApp;
use App\Models\Antrian;
use App\Models\Mst_golpas;
use App\Models\Mst_poli;
use App\Models\Pasien;
use App\Models\Registrasi_request_rm;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;


class PermintaanRmController extends BaseController
{
    public function GetData(Request $request)
    {
        if( is_array($request->ruangan) ){
            $whereInValue = array();
            foreach ($request->ruangan as $row ) {
                $whereInValue[] = $row['id'];
            }
            return Registrasi_request_rm::with(['r_registrasi','r_pasien','r_ruangan','r_registrasi_antrian' => function($q){
                return $q->with('r_antrian');
            }])->whereIn('id_ruangan', $whereInValue)->where('status', 0)->get();
        }
    }
}
