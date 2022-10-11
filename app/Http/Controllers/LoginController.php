<?php

namespace App\Http\Controllers;

use App\Http\Libraries\LibApp;
use App\Http\Libraries\LibVclaim;
use App\Models\App_user;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class LoginController extends BaseController
{
    public function dologin(Request $request)
    {

        $key = '1239JJasu!&&#@nas1Issj';

        //$password = md5($request->password.$key);
        $password = $request->password;

        $data = App_user::GetAllData()->where('username', $request->username)->where('password', $password)->first();

        if( count($data) == 1 ){
            $jwt = getJWT($data[0]->username,$data[0]->password);
            $array = array('auth'=>true,'username'=> $request->username, 'token'=>$jwt, 'role'=>$data[0]->role, 'id_pelaksana'=>$data[0]->id_pelaksana);
            return LibApp::response(200, $array);
        }else{
            return LibApp::response(201, [], 'Username atau Password anda salah.');
        }
    }

    public function Test()
    {
        $data = array(
            "kodebooking" => '39F84A',
            "jenispasien" => 'NON JKN',
            "nomorkartu" => '',
            "nik" => '8171027004940002',
            "nohp" => '9182309128312',
            "kodepoli" => 'ANA',
            "namapoli" => 'ANAK',
            "pasienbaru" => 0,
            "norm" => '818181',
            "tanggalperiksa" => '2022-10-02',
            "kodedokter" => 3732, // * harus sesuai dengan bpjs
            "namadokter" => 'dr. Novelia Z.L Mardin, Sp.A A',
            "jampraktek" => '02:00-02:30',
            "jeniskunjungan" => 2, // *
            "nomorreferensi" => '', // * bisa asal 16 digit atau kosongkan jika jenispasien selain JKN
            "nomorantrean" => 'A1-1',
            "angkaantrean" => '1',
            "estimasidilayani" => 110123100021, // boleh asal
            "sisakuotajkn" => 20,
            "kuotajkn" => 20,
            "sisakuotanonjkn" => 20,
            "kuotanonjkn" => 40,
            "keterangan" => "Peserta harap 30 menit lebih awal guna pencatatan administrasi."
        );

        $url = 'antrean/add';
        return LibVclaim::antrol('POST', $url, json_encode($data));
    }
}
