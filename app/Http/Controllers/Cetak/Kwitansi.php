<?php

namespace App\Http\Controllers\Cetak;

use App\Http\Libraries\LibApp;
use App\Http\Libraries\PDFBarcode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Routing\Controller as BaseController;

class Kwitansi extends BaseController
{

    public function GoPrint()
    {
        $data = DB::table('mst_rs')->get();

        $pdf = new PDFBarcode();

        return LibApp::response_success($data);
    }

}
