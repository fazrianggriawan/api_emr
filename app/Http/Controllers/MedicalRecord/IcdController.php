<?php

namespace App\Http\Controllers\MedicalRecord;

use App\Http\Libraries\LibApp;
use App\Models\Icd9;
use App\Models\Icd10;
use Laravel\Lumen\Routing\Controller as BaseController;

class IcdController extends BaseController
{

    public function Icd10()
    {
        $mod = new Icd10();
        $data = $mod->getIcd10();
        return LibApp::response(200, $data, '');
    }

    public function Icd9()
    {
        $mod = new Icd9();
        $data = $mod->getAllData();
        return LibApp::response(200, $data, '');
    }
}
