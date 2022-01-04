<?php

namespace App\Http\Controllers;

use App\Models\Tindakan;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class TindakanController extends BaseController
{
    public function save(Request $request)
    {
        $mod = new Tindakan();

        $mod->noreg = $request['noreg'];
        $mod->icd9_code = $request['icd9_code'];
        $mod->icd9_name = $request['icd9_name'];
        $mod->tindakan_id = $request['tindakan']['id'];
        $mod->tindakan_name = $request['tindakan']['name'];
        $mod->operasi_id = $request['operasi']['id'];
        $mod->operasi_name = $request['operasi']['name'];

        try {
            $mod->save();
        } catch (Exception $e) {
            dd($e);
        }

    }

    public function getAllData(Request $request)
    {
        $mod = new Tindakan();
        return $mod->getAll();
    }

}
