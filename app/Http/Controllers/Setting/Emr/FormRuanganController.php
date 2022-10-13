<?php

namespace App\Http\Controllers\Setting\Emr;

use App\Http\Libraries\LibApp;
use App\Models\Emr_form;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class FormRuanganController extends BaseController
{
    public function FormRuangan()
    {
        return $data = Emr_form::with(['r_ruangan'=>function($q){
            return $q->with('r_jns_perawatan');
        }])->get();
        return LibApp::response(200, $data);
    }
}
