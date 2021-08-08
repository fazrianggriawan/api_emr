<?php

namespace App\Http\Controllers;

use App\Models\TestOrderDetail;
use App\Models\TestOrderHead;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class TestOrderController extends BaseController
{
    public function save(Request $request)
    {
        $modTestOrderHead = new TestOrderHead();
        $insert = array();
//        $insert['tanggal'] = $request->tanggal;
        $insert['tipe'] = $request->tipe['id'];
        $insert['diagnosa_icd10_code'] = $request->diagnosa['id'];
        $insert['diagnosa_icd10_name'] = $request->diagnosa['name'];
        $insert['physician_id'] = $request->physician;
        $insert['remarks'] = $request->remarks;
        $insert['unit'] = $request->unit;
        $insert['noreg'] = $request->noreg;

        $id_head = $modTestOrderHead->saveData($insert);

        $modTestOrderDetail = new TestOrderDetail();
        $insertDetail = array();
        foreach ($request->data as $index => $row){
            $a = array();
            $a['id_emr_test_order_head'] = $id_head;
            $a['pemeriksaan_id'] = $row['id'];
            $a['pemeriksaan_name'] = $row['name'];
            $a['pemeriksaan_cat_name'] = @$row['cat_name'];
            $a['pemeriksaan_group_name'] = @$row['group_name'];
            array_push($insertDetail, $a);
        }
        $save = $modTestOrderDetail->saveData($insertDetail);
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
