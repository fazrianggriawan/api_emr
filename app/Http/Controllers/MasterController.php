<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class MasterController extends BaseController
{
    public function poliklinik(Request $request)
    {
        $endpoint = setEndpoint('/master/poli/getADataPoliBpjs');
        $client = new Client();

        $req = $client->request('GET', $endpoint);
        $json = json_decode($req->getBody()->getContents());

        return json_encode($json);

        // if( $json->code == 200 ){
        //     return json_encode($json->data);
        // }else{
        //     return '';
        // }

    }

}
