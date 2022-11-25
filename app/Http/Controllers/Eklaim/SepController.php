<?php

namespace App\Http\Controllers\Eklaim;

use App\Http\Libraries\LibApp;
use Laravel\Lumen\Routing\Controller as BaseController;
use App\Http\Libraries\LibEklaim;
use App\Models\Registrasi_sep;


class SepController extends BaseController
{
    public function SepByNoreg($noreg)
    {
        $data = Registrasi_sep::where('noreg', $noreg)->first();

        return LibApp::response(200, $data);
    }
}
