<?php

namespace App\Http\Controllers;

use App\Models\PatAnatomi;
use Laravel\Lumen\Routing\Controller as BaseController;

class PatAnatomiController extends BaseController
{
    function getAllMaster(){
        $mod = new PatAnatomi();
        $data = $mod->getAllData();
        return $data;
    }
}
