<?php

namespace App\Http\Controllers;

use App\Http\Libraries\LibApp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Routing\Controller as BaseController;

class ModulesController extends BaseController
{
    public function Modules($username)
    {
        $modules = DB::table('mst_module')->get();
        return LibApp::response_success($modules);
    }
}
