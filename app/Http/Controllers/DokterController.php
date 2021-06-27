<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Laravel\Lumen\Routing\Controller as BaseController;

class DokterController extends BaseController
{
    function getAllDokter(){
        $data = array(
            'unit' => 'urologi',
            'group' => 'dokter',
        );
        $client = new Client();
        $req = $client->request('POST', setEndpoint('/api/master/pelaksana/getdata'), ['headers'=>getHeaderEndPoint(), 'body'=>json_encode($data)]);
        $json = json_decode($req->getBody()->getContents());
        if( $json->metadata->status == 200 ){
            return json_encode($json->response->data);
        }else{
            return '';
        }
    }
}
