<?php

namespace App\Http\Controllers;

use App\Models\Diagnosa;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class DiagnosaController extends BaseController
{
    public function save(Request $request)
    {
        $mod = new Diagnosa();
        $insert = array();
        foreach ($request->icd as $index => $row){
            $a['noreg'] = $request->noreg;
            $a['icd10_code'] = $row['id'];
            $a['icd10_name'] = $row['name'];
            $a['is_primary'] = ($index == 0)? 1 : 0;
            array_push($insert, $a);
        }
        $save = $mod->saveData($insert);
        if( !isset($save->errorInfo) ){
            $res = array('status'=>true, 'message'=>'Data berhasil disimpan');
        }else{
            $res = array('status'=>false, 'message'=>$save->errorInfo[2]);
        }
        return json_encode($res);
    }

    public function getAllData(Request $request)
    {
        $mod = new Diagnosa($request->noreg);
        return $mod->getAll();
    }

}
