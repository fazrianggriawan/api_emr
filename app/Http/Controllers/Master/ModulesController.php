<?php

namespace App\Http\Controllers\Master;

use App\Http\Libraries\LibApp;
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
