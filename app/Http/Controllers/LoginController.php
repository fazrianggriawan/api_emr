<?php

namespace App\Http\Controllers;

use App\Models\Login;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Routing\Controller as BaseController;

class LoginController extends BaseController
{
    public function dologin(Request $request)
    {

        $key = '1239JJasu!&&#@nas1Issj';

        //$password = md5($request->password.$key);
        $password = $request->password;

        $data = DB::table('login')->where('username', $request->username)->where('password', $password)->get();

        if( count($data) == 1 ){
            $jwt = getJWT($data[0]->username,$data[0]->password);
            $array = array('auth'=>true,'username'=> $request->username, 'token'=>$jwt, 'role'=>$data[0]->role, 'id_pelaksana'=>$data[0]->id_pelaksana);
        }else{
            $array = array('auth'=>false,'token'=>null, 'role'=>null);
        }
        return $array;

        // 98385608754114cbb18a9a3d51b1bb96{"auth":false,"token":null,"role":null}
    }

}
