<?php

namespace App\Http\Controllers;

use App\Models\Icd9;
use Laravel\Lumen\Routing\Controller as BaseController;

class IcdController extends BaseController
{
    public function Icd9()
    {
        $mod = new Icd9();
        $data = $mod->getAllData()->toJson();
        return $data;
    }
}
