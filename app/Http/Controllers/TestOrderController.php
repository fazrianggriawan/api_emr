<?php

namespace App\Http\Controllers;

use App\Models\TestOrder;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class TestOrderController extends BaseController
{
    public function save(Request $request)
    {
        $modTestOrder = new TestOrder();
        $modTestOrder['noreg'] = $request->noreg;
        $modTestOrder['unit'] = $request->unit;
        $modTestOrder['tipe_id'] = $request->tipe['id'];
        $modTestOrder['tipe_name'] = $request->tipe['name'];
        if( $request->unit == 'lab' ){
            $modTestOrder['test_id'] = $request->data['id'];
            $modTestOrder['test_name'] = $request->data['name'];
            $modTestOrder['test_cat_name'] = $request->data['cat_name'];
            $modTestOrder['test_group_name'] = $request->data['group_name'];
        }elseif ( $request->unit == 'rad' ){
            $modTestOrder['test_id'] = $request->data['id_mst_radiologi'];
            $modTestOrder['test_name'] = $request->data['name'];
            $modTestOrder['test_cat_name'] = $request->data['category']['name'];
        }
        $modTestOrder['remark'] = $request->remarks;
        try {
            $modTestOrder->save();
        }catch (\Exception $e){
            dd($e->getMessage());
        }
    }

    public function getData(Request $request)
    {
        $modTestOrder = new TestOrder();
        $data = $modTestOrder->getData($request->noreg, $request->unit);
        return json_encode($data);
    }

}
