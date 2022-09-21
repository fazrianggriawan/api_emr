<?php

namespace App\Http\Controllers\Radiologi;

use App\Http\Libraries\LibApp;
use App\Models\Radiologi;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class RadiologiController extends BaseController
{
    function getAllMaster(){
        $mod = new Radiologi();
        return LibApp::response(200, $mod->getAllData(), '');
    }

    function getAllMasterDetail(Request $request){
        $mod = new Radiologi();
        return LibApp::response(200, $mod->getAllDataDetail($request->id_head), '');
    }
}
