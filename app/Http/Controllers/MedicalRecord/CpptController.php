<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cppt;

class CpptController extends Controller
{
    public function save(Request $request){
        $data = $request->input();
        $insert = array();
        foreach ($data as $key => $item) {
            if( is_array($item) ){
                array_push($insert, ['key'=>$key, 'value'=>$item['name']]);
            }else{
                array_push($insert, ['key'=>$key, 'value'=>$item]);
            }
        }
        return $insert;
    }
}
