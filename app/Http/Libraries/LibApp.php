<?php

namespace App\Http\Libraries;

class LibApp
{
    public static function response($code=200, $data=array(), $message='')
    {
        $array = array(
            'code' => $code,
            'data' => $data,
            'message' => $message
        );
        return \json_encode($array);
    }

    public static function response_success($data=array())
    {
        response();
        $array = array(
            'code' => 200,
            'data' => $data,
            'message' => 'Sukses'
        );
        return \json_encode($array);
    }

    public static function dateHuman($tanggal='')
    {
        if( $tanggal != '' ){
            $aTanggal = explode('-', $tanggal);
            return $aTanggal[2].'-'.$aTanggal[1].'-'.$aTanggal[0];
        }
    }

}