<?php

namespace App\Http\Controllers\Radiologi;

use App\Models\Radiologi;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class RadiologiController extends BaseController
{
    function getAllMaster(){
        $mod = new Radiologi();
        return json_encode($mod->getAllData());
    }

    function getAllMasterDetail(Request $request){
        $mod = new Radiologi();
        return json_encode($mod->getAllDataDetail($request->id_head));
    }
}
