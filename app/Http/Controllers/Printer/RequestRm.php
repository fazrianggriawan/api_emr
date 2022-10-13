<?php

namespace App\Http\Controllers\Printer;

use App\Models\Registrasi_request_rm;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Laravel\Lumen\Routing\Controller as BaseController;

class RequestRm extends BaseController
{

    public function GoPrint($id_request)
    {
        $data = Registrasi_request_rm::with(['r_registrasi', 'r_pasien', 'r_ruangan' => function($q){
            return $q->with('r_jns_perawatan');
        },'r_registrasi_antrian' => function($q){
            return $q->with('r_antrian');
        }
        ])->where('id', $id_request)->first();

        return view('cetak/request-rm', ['data'=>$data,'qrcode'=>QrCode::size(90)->generate($data->noreg.$data->id)]);

    }
}
