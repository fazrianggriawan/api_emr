<?php

namespace App\Http\Controllers;

use App\Models\Pelaksana;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class DokterController extends BaseController
{
    function getAllDokter(){
        $data = array(
            'unit' => 'urologi',
            'group' => 'dokter',
        );
        $client = new Client();
        $req = $client->request('GET', setEndpoint('/master/dokter/dokterBpjs'));
        $json = json_decode($req->getBody()->getContents());
        if( $json->code == 200 ){
            return json_encode($json->data);
        }else{
            return '';
        }
    }

    function getAllDokterByPoli(Request $request)
    {
        $mPelaksana = new Pelaksana();
        $mPelaksana->id_poli = $request->id_poli;
        $data = $mPelaksana->getDokterByPoli();
        return $data;
    }
}
