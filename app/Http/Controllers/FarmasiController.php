<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Laravel\Lumen\Routing\Controller as BaseController;

class FarmasiController extends BaseController
{
    public function getMasterObat()
    {
        $endpoint = setEndpoint('/api/master/farmasi/stok/gudang');
        $data = array(
            "fornas" => "1",
            "nama_obat" => "",
        );
        $client = new Client();
        $req = $client->request('POST', $endpoint, ['headers'=>getHeaderEndPoint(), 'body'=>json_encode($data)]);
        $json = json_decode($req->getBody()->getContents());


        if( $json->metadata->status == 200 ){
            return json_encode($json->response->data);
        }else{
            return '';
        }
    }

}
