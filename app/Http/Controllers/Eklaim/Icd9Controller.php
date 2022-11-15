<?php

namespace App\Http\Controllers\Eklaim;

use App\Http\Libraries\LibApp;
use Laravel\Lumen\Routing\Controller as BaseController;
use App\Http\Libraries\LibEklaim;
use Illuminate\Http\Request;


class Icd9Controller extends BaseController
{
    public function Cari(Request $request)
    {
        $data = array(
            'metadata' => array(
                'method' => 'search_diagnosis'
            ),
            'data' => array(
                'keyword' => $request->keyword
            )
        );
        $res = LibEklaim::exec(json_encode($data));

        return LibApp::response(200, $this->ParsingData($res));
    }

    public static function ParsingData($data)
    {
        $res = json_decode($data);
        if( is_array($res->response->data) ){
            $array = array();
            foreach ($res->response->data as $row) {
                $item = array(
                    'id' => $row[1],
                    'name' => $row[0]
                );
                array_push($array, $item);
            }
            return $array;
        }
    }
}
