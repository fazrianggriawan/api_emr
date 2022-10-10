<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Emr_cppt;

class CpptController extends Controller
{
    public function save(Request $request)
    {
        $cppt = new Emr_cppt();

        $cppt->tanggal = $request->tanggal;
        $cppt->noreg = $request->noreg;
        $cppt->s = $request->s;
        $cppt->o = $request->o;
        $cppt->a = $request->a;
        $cppt->p = $request->p;
        $cppt->i = $request->i;
        $cppt->id_pelaksana = $request->id_pelaksana;
        $cppt->dateCreated = $request->username;
        $cppt->userCreated = date('Y-m-d H:i:s');

        return $cppt->save();

    }
}
