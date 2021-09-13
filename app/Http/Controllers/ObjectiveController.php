<?php
namespace App\Http\Controllers;

use App\Models\Objective;
use Illuminate\Http\Request;


class ObjectiveController extends Controller{
    public function save(Request $request)
    {
        $mod = new Objective();

        try {
            $mod->noreg = $request['noreg'];
            $mod->notes = $request['notes'];
            $mod->save();

        }catch (\Exception $exception){
            dd($exception->getMessage());
        }
//        if( $json->metadata->status == 200 ){
//            if( isset($json->response) ){
//                return json_encode(array('status'=>200, 'data'=>$json->response->data, 'message'=>''));
//            }else{
//                return json_encode(array('status'=>204, 'data'=>'', 'message'=>$json->metadata->message));
//            }
//        }else{
//            return json_encode(array('status'=>204, 'data'=>'', 'message'=>$json->metadata->message));
//        }
    }

    public function getData(Request $request)
    {
        $mod = new Objective();
        $data = $mod->getData($request->noreg);
        return json_encode($data);
    }

}
