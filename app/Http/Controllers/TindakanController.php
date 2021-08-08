<?php

namespace App\Http\Controllers;

use App\Models\Tindakan;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class TindakanController extends BaseController
{
    public function save(Request $request)
    {
        $mod = new Tindakan();
        $insert = array();
        foreach ($request->icd as $index => $row){
            $a['noreg'] = $request->noreg;
            $a['icd9_code'] = $row['id'];
            $a['icd9_name'] = $row['name'];
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
        $mod = new Tindakan();
        return $mod->getAll();
    }

}
