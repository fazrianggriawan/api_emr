<?php

namespace App\Http\Controllers\Eklaim;

use App\Http\Libraries\LibApp;
use Laravel\Lumen\Routing\Controller as BaseController;
use App\Http\Libraries\LibEklaim;
use Illuminate\Http\Request;

class FinalisasiController extends BaseController
{
    public function FinalisasiKlaim(Request $request)
    {
        try {
            $data = array(
                'metadata' => array(
                    'method' => 'claim_final'
                ),
                'data' => array(
                    'nomor_sep' => $request->noSep,
                    'coder_nik' => '123123123123'
                )
            );

            return KlaimController::HandleResponse(LibEklaim::exec(json_encode($data)));

        } catch (\Throwable $th) {
            return LibApp::response(201, [], $th->getMessage());
        }
    }
}
