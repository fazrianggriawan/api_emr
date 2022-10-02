<?php

namespace App\Http\Controllers\Emr;

use App\Http\Libraries\LibApp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Routing\Controller as BaseController;

class TestOrderController extends BaseController
{

    public function SaveOrder(Request $request)
    {
        DB::beginTransaction();

        $sessionId = microtime(true);

        if( $request->unit == 'rad' ){
            $category = DB::table('mst_radiologi')->where('id', $request->order['id_mst_radiologi'])->get();
            $catName = $category[0]->name;
        }

        if( $request->unit == 'lab' ){
            $catName = $request->order['cat_name'];
        }

        $insert = array(
            'noreg' => $request->noreg,
            'unit' => $request->unit,
            'tipe_id' => $request->tipeId,
            'tipe_name' => $request->tipeName,
            'test_id' => $request->order['id'],
            'test_name' => $request->order['name'],
            'test_cat_name' => $catName,
            'created_at' => date('Y-m-d H:i:s'),
            'session_id' => $sessionId
        );
        $data = DB::table('emr_test_order')->insert($insert);

        $newData = DB::table('emr_test_order')->where('session_id', $sessionId)->get();

        $insertStatus = array(
            'id_emr_test_order' => $newData[0]->id,
            'status' => 'waiting',
            'tanggal' => date('Y-m-d'),
            'dateCreated' => date('Y-m-d H:i:s'),
        );

        DB::table('emr_test_order_status')->insert($insertStatus);

        $data = json_decode(self::OrderByNoreg($request->noreg, $request->unit));

        DB::commit();

        return LibApp::response_success($data->data);
    }

    public function OrderByNoreg($noreg, $unit)
    {
        $data = DB::table('emr_test_order')
                ->select('id', 'test_name as name', 'test_cat_name as cat_name')
                ->where('noreg', $noreg)
                ->where('unit', $unit)
                ->where('active', 1)
                ->get();
        return LibApp::response_success($data);
    }

    public function DeleteOrder(Request $request)
    {
        $testOrder = DB::table('emr_test_order')->where('id', $request->id)->get();

        $update = array('active' => 0, 'updated_at'=>date('Y-m-d H:i:s'));

        $delete = DB::table('emr_test_order')
                    ->where('id', $request->id)
                    ->update($update);

        if( $delete ){
            $data = json_decode(self::OrderByNoreg($testOrder[0]->noreg, $testOrder[0]->unit));
            return LibApp::response_success($data->data);
        }
    }


}
