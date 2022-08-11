<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Routing\Controller as BaseController;

class KeluhanController extends BaseController
{
    public function Save(Request $request)
    {
        $data = array(
            'id_pasien' => $request->a,
            'id_registrasi' => $request->a,
            'keluhan' => $request->a,
            'lamaKeluhan' => $request->a,
            'keterangan' => $request->a,
            'dateCreated' => $request->a,
            'userCreated' => $request->a,
            'deleted' => $request->a,
            'userDeleted' => $request->a,
        );

        $insert = DB::table('emr_keluhan')->insert($data);

    }
}
