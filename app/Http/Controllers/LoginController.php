<?php

namespace App\Http\Controllers;

use App\Models\Login;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class LoginController extends BaseController
{
    public function dologin(Request $request)
    {
        $request->input('username');
        $request->input('password');
        $mod = new Login();
        $data = $mod->getLogin()
            ->where('username', $request->input('username'))
            ->where('password', $request->input('password'))
            ->all();
//        if( count($data) == 1 ){
//            $jwt = getJWT($data[0]->username,$data[0]->password);
//            $array = array('auth'=>true,'token'=>$jwt, 'role'=>$data[0]->role, 'id_pelaksana'=>$data[0]->id_pelaksana);
//        }else{
//            $array = array('auth'=>false,'token'=>null, 'role'=>null);
//        }
//        return $array;
    }

    public function aa(){
        return '';
    }
}
