<?php

namespace App\Http\Controllers;

use App\Models\Radiologi;
use Laravel\Lumen\Routing\Controller as BaseController;

class RadiologiController extends BaseController
{
    function getAllMaster(){
        $mod = new Radiologi();
        return json_encode($mod->getAllData());
    }
}
