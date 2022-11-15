<?php

namespace App\Http\Controllers\Eklaim;

use App\Http\Libraries\LibApp;
use Laravel\Lumen\Routing\Controller as BaseController;
use App\Http\Libraries\LibEklaim;
use Illuminate\Http\Request;

class GroupingController extends BaseController
{
    public function Stage1(Request $request)
    {
        try {
            $data = array(
                'metadata' => array(
                    'method' => 'grouper',
                    'stage' => '1'
                ),
                'data' => array(
                    'nomor_sep' => $request->noSep
                )
            );

            return KlaimController::HandleResponse(LibEklaim::exec(json_encode($data)));

        } catch (\Throwable $th) {
            return LibApp::response(201, [], $th->getMessage());
        }
    }

    public function Stage2(Request $request)
    {
        try {
            $data = array(
                'metadata' => array(
                    'method' => 'grouper',
                    'stage' => '2'
                ),
                'data' => array(
                    'nomor_sep' => $request->noSep,
                    'special_cmg' => KlaimController::ParsingICD($request->specialCmg)
                )
            );

            return KlaimController::HandleResponse(LibEklaim::exec(json_encode($data)));

        } catch (\Throwable $th) {
            return LibApp::response(201, [], $th->getMessage());
        }
    }
}
